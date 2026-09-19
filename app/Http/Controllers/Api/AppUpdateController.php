<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AppUpdateController extends Controller
{
    /**
     * Mengembalikan informasi versi aplikasi Android terbaru.
     *
     * Endpoint publik: GET /api/app/version
     * Tidak memerlukan login / Bearer Token.
     *
     * @return JsonResponse
     */
    public function getVersion(): JsonResponse
    {
        $config = config('app_update');

        $versionName  = (string) ($config['version_name'] ?? '1.0.0');
        $versionCode  = (int) ($config['version_code'] ?? 1);
        $downloadUrl  = (string) ($config['download_url'] ?? '');
        $releaseNotes = is_array($config['release_notes'] ?? null) ? $config['release_notes'] : [];
        $fileSize     = (int) ($config['file_size'] ?? 0);
        $forceUpdate  = (bool) ($config['force_update'] ?? false);

        return response()->json([
            'success' => true,
            'data'    => [
                'version_name'  => $versionName,
                'version_code'  => $versionCode,
                'download_url'  => $downloadUrl,
                'release_notes' => $releaseNotes,
                'file_size'     => $fileSize,
                'force_update'  => $forceUpdate,
            ],
        ]);
    }
}