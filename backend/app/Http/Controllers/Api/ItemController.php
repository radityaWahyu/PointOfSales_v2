<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Imports\ItemImport;
use Illuminate\Http\Request;
use App\Exports\FormatImportBarang;
use App\Http\Resources\ItemResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class ItemController extends ApiController
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
            $data = Item::query();
            $data->with(['Category', 'Unit']);;
            $data->when($request->has('sort'), function ($q) use ($request) {
                $sortData = explode('_', $request->input('sort'));
                return $q->orderBy($sortData[0], $sortData[1]);
            });
            $data->when($request->has('search'), function ($q) use ($request) {
                return $q->where('name', 'LIKE', '%' . $request->search . '%')->orWhere('barcode', $request->search);
            });

            return ItemResource::collection($data->latest()->paginate($perPage));
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
    public function store(StoreItemRequest $request)
    {
        try {
            $data = Item::create($request->all());

            return $this->responseCreated(new ItemResource($data));
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $Item
     * @return \Illuminate\Http\Response
     */
    public function show($Item)
    {
        try {
            $data = Item::findOrFail($Item);
            return $this->responseSuccess(new ItemResource($data));
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
     * @param  \App\Models\Item  $Item
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request, Item $Item)
    {

        try {
            $Item->update($request->all());
            return $this->responseUpdated($Item);
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $Item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            Item::destroy($request->id);
            return $this->responseDeleted();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function download_format_import()
    {
        return (new FormatImportBarang)->download('format_import_barang.xlsx');
    }

    public function import(Request $request)
    {

        try {

            $imports = new ItemImport;
            Excel::import($imports, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di import',
                'row' => $imports->getRowCount(),
            ], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $row_errors = [];
            foreach ($failures as $failure) {
                array_push($row_errors, ['row' => $failure->row(), 'message' => $failure->errors()[0]]);
                // }
            }

            $tmp = [];
            foreach ($row_errors as $error) {
                $tmp[$error['row']][] = $error['message'];
            }

            $output = [];
            foreach ($tmp as $temp => $messages) {
                $output[] = ['row' => $temp, 'messages' => $messages];
            }

            return response()->json([
                'success' => false,
                'message' => 'Terdapat kesalahan terhadap data',
                'data' => $output,
            ], 200);
        }
    }

    public function findByBarcode(Request $request)
    {
        try {
            $data = Item::where('barcode', $request->barcode);
            return $this->responseSuccess(new ItemResource($data->first()));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan.',
            ], 404);
        }
    }
}
