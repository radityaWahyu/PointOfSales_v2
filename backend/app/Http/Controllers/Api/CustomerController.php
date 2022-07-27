<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\FormatCustomer;
use App\Imports\CustomerImport;
use App\Http\Resources\ListResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\CustomerResource;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CustomerController extends ApiController
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
            $data = Customer::query();
            $data->when($request->has('sort'), function ($q) use ($request) {
                $sortData = explode('_', $request->input('sort'));
                return $q->orderBy($sortData[0], $sortData[1]);
            });

            $data->when($request->has('search'), function ($q) use ($request) {
                return $q->where('name', 'LIKE', '%' . $request->search . '%');
            });

            return CustomerResource::collection($data->latest()->paginate($perPage));
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
    public function store(StoreCustomerRequest $request)
    {
        try {
            $data = Customer::create($request->all());

            return $this->responseCreated(new CustomerResource($data));
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $Customer
     * @return \Illuminate\Http\Response
     */
    public function show($Customer)
    {
        try {
            $data = Customer::findOrFail($Customer);
            return $this->responseSuccess(new CustomerResource($data));
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
     * @param  \App\Models\Customer  $Customer
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCustomerRequest $request, Customer $Customer)
    {

        try {
            $Customer->update($request->all());
            return $this->responseUpdated($Customer);
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $Customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            Customer::destroy($request->id);
            return $this->responseDeleted();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function listData()
    {
        try {

            $data = Customer::query();
            return ListResource::collection($data->get());
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function download_format_import()
    {
        return (new FormatCustomer)->download('format_import_pelanggan.xlsx');
    }

    public function import(Request $request)
    {

        try {

            $imports = new CustomerImport;
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
}
