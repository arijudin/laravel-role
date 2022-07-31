<?php

namespace App\Concerns;

use Illuminate\Http\JsonResponse;

trait BasicResponse
{
    /**
     * Success REST response.
     *
     * @param array  $data
     * @param string $message
     *
     * @return JsonResponse
     */
    public function success(array $data = [], $message = 'SUCCESS'): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], 200);
    }

    public function successNotArray($data, $message = 'SUCCESS')
    {
        $arr = $data->toArray();
        $data_arr = $arr['data'];
        unset($arr['data']);

        return [
            'code' => 0,
            'message' => $message,
            'data' => $data_arr,
            'pagination' => $arr,
            'errors' => null,
        ];
    }

    public function error($code = 500, $message = 'ERROR', array $errors = null): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Success NO CONTENT response.
     *
     * @return JsonResponse
     */
    public function noContent(): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'message' => 'NO_CONTENT',
            'data' => null,
            'errors' => null,
        ], 204);
    }

    /**
     * Additional array to API Resource or Collection
     *
     * @return array
     */
    public function setAdditionalData(int $code = 0, string $message = 'SUCCESS'): array
    {
        return [
            'code' => $code,
            'message' => $message,
            'errors' => null,
        ];
    }
}
