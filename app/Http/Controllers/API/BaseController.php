<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    public function sendSuccess($data, $msg)
    {
        return response()->json([
            'success' => 1,
            'data' => $data,
            'message' => $msg
        ]);
    }

    public function sendError($msg)
    {
        return response()->json([
            'success' => 0,
            'message' => $msg
        ], status: 400);
    }
}
