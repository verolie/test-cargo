<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Response sukses.
     *
     * @param string $detail Pesan detail response
     * @param int $code Kode HTTP response, default 200
     * @param mixed $data (Optional) Data tambahan yang ingin dikirim
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($detail, $code = 200, $data = null)
    {
        $response = [
            'status' => 'success',
            'status code'   => $code,
            'detail' => $detail,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Response error.
     *
     * @param mixed $errorMessage Pesan error
     * @param int $code Kode HTTP response, default 500
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($errorMessage, $code = 500)
    {
        $response = [
            'status'        => 'error',
            'status code'          => $code,
            'error message' => $errorMessage,
        ];

        return response()->json($response, $code);
    }
}
