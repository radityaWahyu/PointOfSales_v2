<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\SaleResource;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Controllers\Api\ApiController;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaleController extends ApiController
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
            $data = Sale::query();
            $data->with(['sale_items', 'users', 'customers']);
            $data->when($request->has('sort'), function ($q) use ($request) {
                $sortData = explode('_', $request->input('sort'));
                return $q->orderBy($sortData[0], $sortData[1]);
            });
            $data->when($request->has('search'), function ($q) use ($request) {
                return $q->where('sales_code', $request->search)->orWhereHas('customers', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
            });
            $data->when($request->has('is_pending'), function ($q) {
                return $q->where('is_pending', true);
            });

            return SaleResource::collection($data->latest()->paginate($perPage));
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
    public function store(StoreSaleRequest $request)
    {
        try {
            $input = $request->except(["items", "id", "sales_code", "due_date"]);

            if ($request->type == 'credit') {
                if (!empty($request->due_date)) {
                    $input += array('due_date' => $request->due_date);
                }
                $input += array('debt_amount' => $request->total_pay - $request->paid);
            }

            //$input += array('sales_code' => IdGenerator::generate(['table' => 'sales', 'field' => 'sales_code', 'length' => 10, 'prefix' => 'TJ' . date('Ymd')]));

            $data = Sale::create($input);
            if (!empty($data->id)) {
                foreach ($request->items as $item) {
                    SaleItem::create([
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


            //return $this->responseCreated(new SaleResource($data));
            return view("prints.struk", ["data" => (new SaleResource($data, "struk"))->resolve()]);
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
            $data = Sale::findOrFail($sale);
            return $this->responseSuccess(new SaleResource($data, "load"));
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
    public function update(Request $request, Sale $sale)
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
                SaleItem::updateOrCreate(
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


            return view("prints.struk", ["data" => (new SaleResource($sale, "struk"))->resolve()]);
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
            Sale::destroy($request->id);
            return $this->responseDeleted();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function deleteItem(Request $request)
    {
        try {

            SaleItem::where('sale_id', $request->sale_id)->where('item_id', $request->item_id)->delete();
            return $this->responseDeleted();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function get_struk(Sale $sale)
    {
        return view("prints.struk", ["data" => (new SaleResource($sale, "struk"))->resolve()]);
    }
}
