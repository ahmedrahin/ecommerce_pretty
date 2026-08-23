<?php

namespace App\Traits;

trait ResponseWithHttpStatus
{
    protected function success($data = [],$message="Data successfully retrieved",$status = 200)
    {
        return response([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function failure($message, $status = 422)
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}