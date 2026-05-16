<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CounselingChat;
use App\Models\CounselingChatMessage;
use App\Models\CounselingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        if (! $session) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi konseling tidak ditemukan',
                'data' => null,
            ], 404);
        }

        // Cek apakah user adalah konselor pada sesi ini
        $isCounselor = $session->counselor_id == $user->id;

        // Cek apakah user adalah konseli yang terkait dengan lansia
        $isCounselee =
            $session->elderlyCounselee &&
            $session->elderlyCounselee->counselee_id == $user->id;

        // Jika user tidak memiliki akses ke sesi ini
        if (! $isCounselor && ! $isCounselee) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke sesi chat ini',
                'data' => null,
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
                'id' => $session->id,
                'elderly_counselee_id' => $session->elderly_counselee_id,
                'counselor_id' => $session->counselor_id,
                'service_mode' => $session->service_mode,
                'status' => $session->status,
            ],

            // Informasi ruang chat
            'chat' => $chat ? [
                'id' => $chat->id,
                'counseling_session_id' => $chat->counseling_session_id,
                'status' => $chat->status,
            ] : null,

            // Daftar pesan
            'messages' => $chat
                ? $chat->messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'sender_role' => $message->sender_role,
                        'sender_name' => $message->sender?->name,
                        'message' => $message->message,
                        'is_read' => $message->is_read,
                        'created_at' => $message->created_at,
                    ];
                })->values()
                : [],
        ];

        // Response sukses
        return response()->json([
            'status' => true,
            'message' => 'Sesi chat berhasil diambil',
            'data' => $data,
        ]);
    }

    public function storeChatMessage(Request $request)
    {
        // Mengambil data user yang sedang login
        $user = $request->attributes->get('user');

        // ==========================================
        // VALIDASI INPUT
        // ==========================================
        $validator = Validator::make(
            $request->all(),
            [
                'counseling_session_id' => 'required|integer|exists:counseling_sessions,id',
                'message' => 'required|string|max:5000',
            ],
            [
                'counseling_session_id.required' => 'Session ID wajib diisi.',
                'counseling_session_id.integer' => 'Session ID harus berupa angka.',
                'counseling_session_id.exists' => 'Sesi konseling tidak ditemukan.',

                'message.required' => 'Pesan wajib diisi.',
                'message.string' => 'Pesan harus berupa teks.',
                'message.max' => 'Pesan maksimal 5000 karakter.',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        // Ambil data yang sudah tervalidasi
        $validated = $validator->validated();

        // Ambil session ID dari body request
        $sessionId = $validated['counseling_session_id'];

        // Mengambil data sesi konseling
        $session = CounselingSession::with([
            'elderlyCounselee',
            'counselor',
        ])->find($sessionId);

        // Jika sesi tidak ditemukan
        if (! $session) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi konseling tidak ditemukan',
                'data' => null,
            ], 404);
        }

        // Cek apakah user adalah konselor
        $isCounselor = $session->counselor_id == $user->id;

        // Cek apakah user adalah konseli
        $isCounselee =
            $session->elderlyCounselee &&
            $session->elderlyCounselee->counselee_id == $user->id;

        // Jika user tidak memiliki akses
        if (! $isCounselor && ! $isCounselee) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke sesi chat ini',
                'data' => null,
            ], 403);
        }

        // Menentukan role pengirim
        $senderRole = $isCounselor ? 'konselor' : 'konseli';

        // Mencari atau membuat chat berdasarkan session ID
        $chat = CounselingChat::firstOrCreate(
            [
                'counseling_session_id' => $session->id,
            ],
            [
                'status' => 'active',
            ]
        );

        // Jika chat sudah ditutup
        if ($chat->status === 'closed') {
            return response()->json([
                'status' => false,
                'message' => 'Sesi chat telah ditutup',
                'data' => null,
            ], 400);
        }

        // Menyimpan pesan baru
        $message = CounselingChatMessage::create([
            'counseling_chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_role' => $senderRole,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // Memuat relasi sender
        $message->load('sender');

        // Format data response
        $data = [
            'id' => $message->id,
            'chat_id' => $chat->id,
            'counseling_session_id' => $session->id,
            'sender_id' => $message->sender_id,
            'sender_role' => $message->sender_role,
            'sender_name' => $message->sender?->name,
            'message' => $message->message,
            'is_read' => $message->is_read,
            'created_at' => $message->created_at,
        ];

        // Response sukses
        return response()->json([
            'status' => true,
            'message' => 'Pesan berhasil dikirim',
            'data' => $data,
        ], 201);
    }

    public function fetchMessages(Request $request, $sessionId)
    {
        // Mengambil data user yang sedang login
        $user = $request->attributes->get('user');

        // Mengambil parameter after_id (opsional)
        $afterId = $request->query('after_id');

        // Mengambil data sesi konseling
        $session = CounselingSession::with([
            'elderlyCounselee',
            'counselor',
        ])->find($sessionId);

        // Jika sesi tidak ditemukan
        if (! $session) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi konseling tidak ditemukan',
                'data' => null,
            ], 404);
        }

        // Cek apakah user adalah konselor
        $isCounselor = $session->counselor_id == $user->id;

        // Cek apakah user adalah konseli
        $isCounselee =
            $session->elderlyCounselee &&
            $session->elderlyCounselee->counselee_id == $user->id;

        // Jika user tidak memiliki akses
        if (! $isCounselor && ! $isCounselee) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke sesi chat ini',
                'data' => null,
            ], 403);
        }

        // Ambil data chat
        $chat = CounselingChat::where(
            'counseling_session_id',
            $sessionId
        )->first();

        // Jika chat belum ada
        if (! $chat) {
            return response()->json([
                'status' => true,
                'message' => 'Belum ada pesan',
                'data' => [
                    'messages' => [],
                    'last_id' => null,
                ],
            ]);
        }

        // Query dasar mengambil pesan beserta pengirim
        $query = CounselingChatMessage::with('sender')
            ->where('counseling_chat_id', $chat->id)
            ->orderBy('id', 'asc');

        // Jika after_id diberikan,
        // hanya ambil pesan dengan ID lebih besar
        if ($afterId) {
            $query->where('id', '>', $afterId);
        }

        // Eksekusi query
        $messages = $query->get();

        // Format response
        $formattedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_role' => $message->sender_role,
                'sender_name' => $message->sender?->name,
                'message' => $message->message,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at,
            ];
        })->values();

        // Ambil ID pesan terakhir
        $lastId = $messages->last()?->id;

        // Response sukses
        return response()->json([
            'status' => true,
            'message' => 'Pesan berhasil diambil',
            'data' => [
                'counseling_chat_id' => $chat->id,
                'last_message_id' => $lastId,
                'messages' => $formattedMessages,
            ],
        ]);
    }

    public function markMessagesAsRead(Request $request, $sessionId)
    {
        // Mengambil data user yang sedang login
        $user = $request->attributes->get('user');

        // Mengambil data sesi konseling beserta relasi
        $session = CounselingSession::with([
            'elderlyCounselee',
            'counselor',
        ])->find($sessionId);

        // Jika sesi tidak ditemukan
        if (! $session) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi konseling tidak ditemukan',
                'data' => null,
            ], 404);
        }

        // Cek apakah user adalah konselor
        $isCounselor = $session->counselor_id == $user->id;

        // Cek apakah user adalah konseli
        $isCounselee =
            $session->elderlyCounselee &&
            $session->elderlyCounselee->counselee_id == $user->id;

        // Jika user tidak memiliki akses
        if (! $isCounselor && ! $isCounselee) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke sesi chat ini',
                'data' => null,
            ], 403);
        }

        // Ambil data chat berdasarkan session ID
        $chat = CounselingChat::where(
            'counseling_session_id',
            $sessionId
        )->first();

        // Jika chat belum tersedia
        if (! $chat) {
            return response()->json([
                'status' => true,
                'message' => 'Belum ada chat',
                'data' => [
                    'updated_count' => 0,
                ],
            ]);
        }

        // Tandai semua pesan milik lawan bicara yang belum dibaca
        $updatedCount = CounselingChatMessage::where(
            'counseling_chat_id',
            $chat->id
        )
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        // Response sukses
        return response()->json([
            'status' => true,
            'message' => 'Pesan berhasil ditandai sebagai sudah dibaca',
            'data' => [
                'updated_count' => $updatedCount,
            ],
        ]);
    }
}
