<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponse

{
    protected function successResponse ($data , string $message="Success", int $code = 200):JsonResponse
    {

          if ($data instanceof JsonResource) {
            return $data->additional([
                'success' => true,
                'message' => $message,
            ])->response()->setStatusCode($code);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);

    }

    protected function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function successMessage(string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $code);
    }

    protected function paginatedResponse($collection, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $collection->items(),
            'meta' => [
                'current_page' => $collection->currentPage(),
                'last_page' => $collection->lastPage(),
                'per_page' => $collection->perPage(),
                'total' => $collection->total(),
            ],
        ], 200);
    }

}