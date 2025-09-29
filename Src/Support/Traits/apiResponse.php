<?php


namespace Support\Traits;

trait apiResponse
{
    public function successResponse($data = null, $code = 200, $message = 'Success')
    {
        return response()->json([
            'code' => $code,
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse($data = null, $code = 400, $message = 'Error')
    {
        return response()->json([
            'code' => $code,
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
