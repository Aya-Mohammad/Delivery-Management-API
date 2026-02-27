<?php

namespace App\Traits;

use PHPUnit\Framework\Attributes\UsesFunction;

trait Responses
{

    protected function ok($message, $data, $statusCode = 200)
    {
        return $this->success($message, $data, 200);
    }

    protected function message($message, $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ]);
    }
    protected function success($message, $data, $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function error($message, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ]);
    }
}