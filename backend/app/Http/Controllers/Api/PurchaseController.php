<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Str;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Http\Controllers\Api\ApiController;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $perPage = 20)
    {
        $perPage = $request->has('per_page') ? $request->per_page : $perPage;

        try {
            $data = Purchase::query();
            $data->with(['purchase_items', 'users', 'suppliers']);
            $data->when($request->has('sort'), function ($q) use ($request) {
                $sortData = explode('_', $request->input('sort'));
                return $q->orderBy($sortData[0], $sortData[1]);
            });
            $data->when($request->has('search'), function ($q) use ($request) {
                return $q->where('sales_code', $request->search)->orWhereHas('suppliers', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
            });

            return PurchaseResource::collection($data->latest()->paginate($perPage));
        } catch (\Exception $e) {

            return $this->responseErrorServer($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        try {
            $input = $request->except(["items", "id", "due_date"]);

            if ($request->type == 'credit') {
                if (!empty($request->due_date)) {
                    $input += array('due_date' => $request->due_date);
                }
                $input += array('debt_amount' => $request->total_pay - $request->paid);
            }


            $data = Purchase::create($input);
            if (!empty($data->id)) {
                foreach ($request->items as $item) {
                    PurchaseItem::create([
                        "sale_id" => $data->id,
                        "item_id" => $item['item_id'],
                        "price" => $item['price'],
                        "amount" => $item['amount'],
                        "subtotal" => $item['subtotal'],
                    ]);
                    if ($request->is_pending == false) {
                        Item::find($item['item_id'])->decrement('stock', $item['amount']);
                    }
                }
            }

            return view("prints.struk", ["data" => (new PurchaseResource($data, "struk"))->resolve()]);
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sale)
    {
        try {
            $data = Purchase::findOrFail($sale);
            return $this->responseSuccess(new PurchaseResource($data, "load"));
        } catch (ModelNotFoundException) {
            return $this->responseNotFound();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $sale)
    {

        try {
            $input = $request->except(["items", "due_date"]);

            if ($request->type == 'credit') {
                if (!empty($request->due_date)) {
                    $input += array('due_date' => $request->due_date);
                }
                $input += array('debt_amount' => $request->total_pay - $request->paid);
            }

            //$input += array('sales_code' => IdGenerator::generate(['table' => 'sales', 'field' => 'sales_code', 'length' => 10, 'prefix' => 'TJ' . date('Ymd')]));

            $sale->update($input);

            foreach ($request->items as $item) {
                PurchaseItem::updateOrCreate(
                    [
                        'sale_id' => $sale->id,
                        'item_id' => $item['item_id']
                    ],
                    [
                        "price" => $item['price'],
                        "amount" => $item['amount'],
                        "subtotal" => $item['subtotal']
                    ]
                );

                Item::find($item['item_id'])->decrement('stock', $item['amount']);
            }


            return view("prints.struk", ["data" => (new PurchaseResource($sale, "struk"))->resolve()]);
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            Purchase::destroy($request->id);
            return $this->responseDeleted();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function deleteItem(Request $request)
    {
        try {

            PurchaseItem::where('sale_id', $request->sale_id)->where('item_id', $request->item_id)->delete();
            return $this->responseDeleted();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function get_struk(Purchase $sale)
    {
        return view("prints.struk", ["data" => (new PurchaseResource($sale, "struk"))->resolve()]);
    }
}
