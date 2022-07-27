<?php

namespace App\Http\Controllers\Api;

use App\Models\OutputType;
use Illuminate\Http\Request;
use App\Http\Resources\OutputTypeResource;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreOutputTypeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class OutputTypeController extends ApiController
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
            $data = OutputType::query();
            $data->when($request->has('sort'), function ($q) use ($request) {
                $sortData = explode('_', $request->input('sort'));
                return $q->orderBy($sortData[0], $sortData[1]);
            });

            return OutputTypeResource::collection($data->paginate($perPage));
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
    public function store(StoreOutputTypeRequest $request)
    {
        try {
            $data = OutputType::create($request->all());

            return $this->responseCreated(new OutputTypeResource($data));
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutputType  $OutputType
     * @return \Illuminate\Http\Response
     */
    public function show($OutputType)
    {
        try {
            $data = OutputType::findOrFail($OutputType);
            return $this->responseSuccess(new OutputTypeResource($data));
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
     * @param  \App\Models\OutputType  $OutputType
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOutputTypeRequest $request, OutputType $OutputType)
    {

        try {
            $OutputType->update($request->all());
            return $this->responseUpdated($OutputType);
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutputType  $OutputType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            OutputType::destroy($request->id);
            return $this->responseDeleted();
        } catch (\Exception $e) {
            return $this->responseErrorServer($e);
        }
    }

    public function list()
    {
        $data = OutputType::get();
        return OutputTypeResource::collection($data);
    }
}
