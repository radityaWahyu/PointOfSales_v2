<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


class ApiController extends Controller
{

    public function responseErrorServer($error)
    {

        switch ($error->getCode()) {
            case '2002':
                $message = 'Could Connect Database Server';
                break;

            default:
                $message = $error->getMessage();
                break;
        }

        return response()->json([
            'success' => false,
            'message' => $message
        ], 500);
    }

    public function responseCreated($resource)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil di Simpan',
            'data' => $resource
        ], 201);
    }

    public function responseUpdated($resource)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil di update',
            'data' => $resource
        ], 201);
    }

    public function responseSuccess($resource)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $resource,
        ], 200);
    }

    public function responseDeleted()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ], 200);
    }

    public function responseNotFound()
    {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
}
