<?php

namespace App\Http\Utility\Api;

use Illuminate\Http\JsonResponse;

class ApiJsonResponse
{
    /**
     * @param string $message
     * @param mixed|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(string $message = '', mixed $data = null, int $statusCode = 200): JsonResponse {

        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * @param string $message
     * @param mixed|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message = '', mixed $data = null, int $statusCode = 400): JsonResponse {

        $response = [
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $statusCode);
    }


    /**
     * @param string $message
     * @param $data
     * @return JsonResponse
     */
    public static function created(string $message = '', $data = null): JsonResponse
    {
        return self::success($message, $data, 201);
    }


    /**
     * @param string $message
     * @param mixed|null $data
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found', mixed $data = null): JsonResponse
    {
        return self::error($message, $data, 404);
    }


    /**
     * @param $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function validationError($errors, int $statusCode = 422): JsonResponse
    {
        $response = [

            'success' => false,
            'message' => 'Validation error',
            'data'    => $errors,
        ];

        return response()->json($response, $statusCode);
    }


    /**
     * @param \Exception $exception
     * @return JsonResponse
     */
    public static function exception(\Exception $exception): JsonResponse
    {
        $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

        $response = [
            'success' => false,
            'message' => $exception->getMessage(),
            'data'    => null,
        ];

        return response()->json($response, $statusCode);
    }

}