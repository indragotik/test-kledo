<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function successLogin($data = null, $token = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            // keep backward compatibility: include both 'data' and 'user'
            'data' => $data,
            'token' => $token,
        ], $code);
    }

    public static function error($message = 'Error', $code = 400, $errors = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    public static function validationError($errors, $message = 'Validation Error')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    public static function notFound($message = 'Not Found')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 404);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 401);
    }

    public static function forbidden($message = '')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 401);
    }
}
