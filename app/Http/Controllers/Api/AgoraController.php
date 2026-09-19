<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Agora\AgoraService;
use Illuminate\Http\JsonResponse;

class AgoraController extends Controller
{
    /**
     * Mengembalikan konfigurasi publik Agora RTC (App ID).
     *
     * PENTING:
     * App Certificate TIDAK BOLEH dikembalikan ke klien atau API.
     * Hanya App ID yang berstatus konfigurasi publik klien.
     *
     * @return JsonResponse
     */
    public function config(): JsonResponse
    {
        $appId = AgoraService::getAppId();

        if (blank($appId)) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Konfigurasi Agora belum diatur di server.',
                'data'    => null,
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'success' => true,
            'message' => 'Konfigurasi Agora berhasil diambil.',
            'data'    => [
                'app_id' => $appId,
            ],
        ]);
    }
}
