<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * SIMPAN FCM TOKEN
     * --------------------------------------------------------------------------
     * Menyimpan token device Firebase user.
     * Digunakan untuk push notification.
     * --------------------------------------------------------------------------
     */
    public function saveToken(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */
        $user = $request->attributes->get('user');

        /*
        |--------------------------------------------------------------------------
        | VALIDASI INPUT
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'nullable|string',
            'device_name' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE TOKEN
        |--------------------------------------------------------------------------
        */
        $device = UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'fcm_token' => $request->fcm_token,
            ],
            [
                'device_type' =>
                    $request->device_type ?? 'android',

                'device_name' =>
                    $request->device_name,

                'app_version' =>
                    $request->app_version,

                'is_active' => true,

                'last_login_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'FCM token berhasil disimpan',
            'data' => $device,
        ]);
    }
}