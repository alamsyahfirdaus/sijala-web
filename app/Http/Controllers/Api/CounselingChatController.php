<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CounselingChat;
use App\Models\CounselingChatMessage;
use App\Models\CounselingSession;
use Illuminate\Http\Request;

class CounselingChatController extends Controller
{

    public function showChatSessions(Request $request, $sessionId)
    {
        // Mengambil data user yang sedang login
        $user = $request->attributes->get('user');

        // Mengambil data sesi konseling beserta relasi lansia dan konselor
        $session = CounselingSession::with([
            'elderlyCounselee',
            'counselor',
        ])->find($sessionId);

        // Jika sesi tidak ditemukan
        if (!$session) {
            return response()->json([
                'status'  => false,
                'message' => 'Sesi konseling tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        // Cek apakah user adalah konselor pada sesi ini
        $isCounselor = $session->counselor_id == $user->id;

        // Cek apakah user adalah konseli yang terkait dengan lansia
        $isCounselee =
            $session->elderlyCounselee &&
            $session->elderlyCounselee->counselee_id == $user->id;

        // Jika user tidak memiliki akses ke sesi ini
        if (!$isCounselor && !$isCounselee) {
            return response()->json([
                'status'  => false,
                'message' => 'Anda tidak memiliki akses ke sesi chat ini',
                'data'    => null,
            ], 403);
        }

        // Menentukan role user
        $role = $isCounselor ? 'konselor' : 'konseli';

        // Ambil data chat beserta seluruh pesan dan data pengirim
        $chat = CounselingChat::with([
            'session',
            'messages.sender',
        ])
            ->where('counseling_session_id', $sessionId)
            ->first();

        // Format data response yang konsisten
        $data = [
            // Role user saat ini
            'role' => $role,

            // Informasi sesi konseling
            'session' => [
                'id'                   => $session->id,
                'elderly_counselee_id' => $session->elderly_counselee_id,
                'counselor_id'         => $session->counselor_id,
                'service_mode'         => $session->service_mode,
                'status'               => $session->status,
            ],

            // Informasi ruang chat
            'chat' => $chat ? [
                'id'                    => $chat->id,
                'counseling_session_id' => $chat->counseling_session_id,
                'status'                => $chat->status,
            ] : null,

            // Daftar pesan
            'messages' => $chat
                ? $chat->messages->map(function ($message) {
                    return [
                        'id'          => $message->id,
                        'sender_id'   => $message->sender_id,
                        'sender_role' => $message->sender_role,
                        'sender_name' => $message->sender?->name,
                        'message'     => $message->message,
                        'is_read'     => $message->is_read,
                        'created_at'  => $message->created_at,
                    ];
                })->values()
                : [],
        ];

        // Response sukses
        return response()->json([
            'status'  => true,
            'message' => 'Sesi chat berhasil diambil',
            'data'    => $data,
        ]);
    }
}
