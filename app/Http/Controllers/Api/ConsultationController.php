<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\CounselingSession;
use App\Models\Notification;
use App\Models\User;
use App\Services\Agora\AgoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */
        $user = $request->attributes->get('user');

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA KONSULTASI
        |--------------------------------------------------------------------------
        | Menampilkan seluruh riwayat konsultasi dimana user menjadi:
        | - Caller
        | - Receiver
        |--------------------------------------------------------------------------
        */
        $consultations = Consultation::with([
            'caller',
            'receiver',
        ])
            ->where(function ($query) use ($user) {

                $query->where('caller_id', $user->id)
                    ->orWhere('receiver_id', $user->id);

            })
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Daftar riwayat konsultasi berhasil diambil',
            'data' => $consultations,
        ]);
    }

    public function consultationDetail(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */
        $user = $request->attributes->get('user');

        /*
        |--------------------------------------------------------------------------
        | AMBIL DETAIL KONSULTASI
        |--------------------------------------------------------------------------
        | Hanya caller atau receiver yang dapat melihat detail konsultasi.
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])
            ->where('id', $id)
            ->where(function ($query) use ($user) {

                $query->where('caller_id', $user->id)
                    ->orWhere('receiver_id', $user->id);

            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | JIKA DATA TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */
        if (! $consultation) {

            return response()->json([
                'status' => false,
                'message' => 'Konsultasi tidak ditemukan',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Detail konsultasi berhasil diambil',
            'data' => $consultation,
        ]);
    }

    public function requestCall(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */
        $user = $request->attributes->get('user');

        $sessionId = $request->input('counseling_session_id');
        $receiver = null;
        $receiverId = null;

        /*
        |--------------------------------------------------------------------------
        | JIKA COUNSELING SESSION ID DIBERIKAN
        |--------------------------------------------------------------------------
        */
        if (!blank($sessionId)) {
            $session = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])->find($sessionId);

            if (!$session) {
                return response()->json([
                    'status'  => false,
                    'success' => false,
                    'message' => 'Sesi konseling tidak ditemukan.',
                ], 404);
            }

            if ($user->role === 'konseli') {
                $isCounselee = $session->elderlyCounselee &&
                    $session->elderlyCounselee->counselee_id == $user->id;

                if (!$isCounselee) {
                    return response()->json([
                        'status'  => false,
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke sesi konseling ini.',
                    ], 403);
                }

                $receiver = $session->counselor;
            } else {
                $isCounselor = $session->counselor_id == $user->id;

                if (!$isCounselor) {
                    return response()->json([
                        'status'  => false,
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke sesi konseling ini.',
                    ], 403);
                }

                $receiver = $session->elderlyCounselee?->counselee;
            }

            if (!$receiver) {
                return response()->json([
                    'status'  => false,
                    'success' => false,
                    'message' => 'Peserta pada sesi konseling tidak ditemukan.',
                ], 404);
            }

            $receiverId = $receiver->id;
        } else {
            /*
            |--------------------------------------------------------------------------
            | JIKA COUNSELING SESSION ID TIDAK DIBERIKAN
            |--------------------------------------------------------------------------
            */
            if ($user->role === 'konseli') {
                // 1. Coba cari sesi konseling aktif / ongoing milik konseli
                $activeSession = CounselingSession::with([
                    'elderlyCounselee.counselee',
                    'counselor',
                ])
                    ->whereHas('elderlyCounselee', function ($q) use ($user) {
                        $q->where('counselee_id', $user->id);
                    })
                    ->where('status', 'ongoing')
                    ->latest()
                    ->first();

                if ($activeSession && $activeSession->counselor) {
                    $sessionId = $activeSession->id;
                    $receiver = $activeSession->counselor;
                    $receiverId = $receiver->id;
                } else {
                    // Fallback logika lama (ujicoba & puskesmas) jika sesi belum dibuat
                    if (($user->id >= 94 && $user->id <= 120) || $user->id == 70) {
                        $receiverId = 3;
                    } elseif ($user->id >= 121 && $user->id <= 124) {
                        $receiverId = 125;
                    } else {
                        $puskesmasCounselor = User::where([
                            'puskesmas_id' => $user->puskesmas_id,
                            'role'         => 'konselor',
                        ])
                            ->where('is_active', true)
                            ->first();

                        if (!$puskesmasCounselor) {
                            return response()->json([
                                'status'  => false,
                                'success' => false,
                                'message' => 'Konselor tidak ditemukan.',
                            ], 404);
                        }

                        $receiverId = $puskesmasCounselor->id;
                    }

                    $receiver = User::find($receiverId);
                }
            } else {
                // User konselor wajib menyertakan counseling_session_id
                $request->validate(
                    [
                        'counseling_session_id' => 'required|exists:counseling_sessions,id',
                    ],
                    [
                        'counseling_session_id.required' => 'Sesi konseling wajib dipilih.',
                        'counseling_session_id.exists'   => 'Sesi konseling yang dipilih tidak ditemukan.',
                    ]
                );
            }
        }

        // =====================================================
        // VALIDASI RECEIVER
        // =====================================================
        if (!$receiver) {
            $receiver = User::find($receiverId);
        }

        if (!$receiver) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Penerima panggilan tidak ditemukan.',
            ], 404);
        }

        if ($receiver->id === $user->id) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Tidak dapat melakukan konsultasi dengan diri sendiri.',
            ], 400);
        }

        if (!$receiver->is_active) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Penerima konsultasi sedang tidak aktif.',
            ], 400);
        }

        $receiverId = $receiver->id;

        /*
        |--------------------------------------------------------------------------
        | AKHIRI PANGGILAN LAMA YANG MASIH AKTIF
        |--------------------------------------------------------------------------
        */
        Consultation::where(function ($query) use ($user, $receiverId, $sessionId) {
            if ($sessionId) {
                $query->where('counseling_session_id', $sessionId);
            } else {
                $query->where(function ($q) use ($user, $receiverId) {
                    $q->where('caller_id', $user->id)
                        ->where('receiver_id', $receiverId);
                })->orWhere(function ($q) use ($user, $receiverId) {
                    $q->where('caller_id', $receiverId)
                        ->where('receiver_id', $user->id);
                });
            }
        })
            ->whereIn('status', ['calling', 'accepted'])
            ->update([
                'status'   => 'ended',
                'ended_at' => now(),
            ]);

        /*
        |--------------------------------------------------------------------------
        | GENERATE CHANNEL AGORA (KONSISTEN)
        |--------------------------------------------------------------------------
        */
        $channelName = $sessionId
            ? AgoraService::generateChannelName($sessionId)
            : AgoraService::generateFixedChannel($user->id, $receiverId);

        /*
        |--------------------------------------------------------------------------
        | GENERATE UID & TOKEN PER-USER
        |--------------------------------------------------------------------------
        */
        $callerUid = AgoraService::generateUid($user);
        $receiverUid = AgoraService::generateUid($receiver);

        $callerToken = AgoraService::generateVideoCallToken($channelName, $callerUid);
        $receiverToken = AgoraService::generateVideoCallToken($channelName, $receiverUid);
        $expiresAt = AgoraService::getExpiredTimestamp();

        Log::info('Agora Video Call: Request Call initiated', [
            'session_id'   => $sessionId,
            'channel_name' => $channelName,
            'caller_id'    => $user->id,
            'caller_uid'   => $callerUid,
            'receiver_id'  => $receiverId,
            'receiver_uid' => $receiverUid,
            'expires_at'   => $expiresAt,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::updateOrCreate(
            ['channel_name' => $channelName],
            [
                'counseling_session_id' => $sessionId,
                'caller_id'             => $user->id,
                'receiver_id'           => $receiverId,
                'token'                 => $callerToken,
                'call_type'             => 'video',
                'status'                => 'calling',
                'started_at'            => null,
                'ended_at'              => null,
                'duration'              => 0,
                'metadata'              => [
                    'caller_uid'     => $callerUid,
                    'caller_token'   => $callerToken,
                    'receiver_uid'   => $receiverUid,
                    'receiver_token' => $receiverToken,
                    'app_id'         => AgoraService::getAppId(),
                    'channel_name'   => $channelName,
                    'expires_at'     => $expiresAt,
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NOTIFIKASI UNTUK RECEIVER
        |--------------------------------------------------------------------------
        */
        Notification::create([
            'user_id' => $receiverId,
            'title'   => 'Incoming Video Call',
            'body'    => $user->name . ' memanggil Anda',
            'type'    => 'incoming_call',
            'data'    => json_encode([
                'consultation_id'       => $consultation->id,
                'counseling_session_id' => $sessionId,
                'channel_name'          => $consultation->channel_name,
                'app_id'                => AgoraService::getAppId(),
                'uid'                   => $receiverUid,
                'token'                 => $receiverToken,
                'expires_at'            => $expiresAt,
            ]),
        ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        $responseData = array_merge($consultation->toArray(), [
            'app_id'       => AgoraService::getAppId(),
            'channel_name' => $channelName,
            'uid'          => $callerUid,
            'token'        => $callerToken,
            'expires_at'   => $expiresAt,
            'expired_at'   => $expiresAt,
        ]);

        return response()->json([
            'status'  => true,
            'success' => true,
            'message' => 'Permintaan konsultasi berhasil dikirim.',
            'data'    => $responseData,
        ]);
    }

    public function acceptCall(Request $request)
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
            'consultation_id' => 'required|exists:consultations,id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])->find($request->consultation_id);

        if (!$consultation) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Konsultasi tidak ditemukan',
            ], 404);
        }

        if ($consultation->receiver_id != $user->id) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menerima panggilan ini',
            ], 403);
        }

        if ($consultation->status !== 'calling') {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Panggilan tidak dapat diterima',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation->update([
            'status'     => 'accepted',
            'started_at' => now(),
        ]);

        $receiverUid = AgoraService::generateUid($user);
        $receiverToken = $consultation->metadata['receiver_token'] ?? null;
        if (empty($receiverToken)) {
            $receiverToken = AgoraService::generateVideoCallToken($consultation->channel_name, $receiverUid);
        }
        $expiresAt = $consultation->metadata['expires_at'] ?? AgoraService::getExpiredTimestamp();

        Log::info('Agora Video Call: Call accepted', [
            'consultation_id' => $consultation->id,
            'channel_name'    => $consultation->channel_name,
            'receiver_id'     => $user->id,
            'receiver_uid'    => $receiverUid,
            'expires_at'      => $expiresAt,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NOTIFICATION KE CALLER
        |--------------------------------------------------------------------------
        */
        Notification::create([
            'user_id' => $consultation->caller_id,
            'title'   => 'Video Call Diterima',
            'body'    => $user->name . ' menerima panggilan Anda',
            'type'    => 'call_accepted',
            'data'    => json_encode([
                'consultation_id' => $consultation->id,
                'channel_name'    => $consultation->channel_name,
            ]),
        ]);

        $responseData = array_merge($consultation->fresh(['caller', 'receiver'])->toArray(), [
            'app_id'       => AgoraService::getAppId(),
            'channel_name' => $consultation->channel_name,
            'uid'          => $receiverUid,
            'token'        => $receiverToken,
            'expires_at'   => $expiresAt,
            'expired_at'   => $expiresAt,
        ]);

        return response()->json([
            'status'  => true,
            'success' => true,
            'message' => 'Panggilan berhasil diterima',
            'data'    => $responseData,
        ]);
    }

    public function incomingCall(Request $request)
    {
        $user = $request->attributes->get('user');

        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])
            ->where('receiver_id', $user->id)
            ->where('status', 'calling')
            ->latest()
            ->first();

        if (!$consultation) {
            return response()->json([
                'status'   => true,
                'success'  => true,
                'incoming' => false,
                'message'  => 'Tidak ada panggilan masuk.',
            ]);
        }

        $receiverUid = AgoraService::generateUid($user);
        $receiverToken = $consultation->metadata['receiver_token'] ?? null;
        if (empty($receiverToken)) {
            $receiverToken = AgoraService::generateVideoCallToken($consultation->channel_name, $receiverUid);
        }
        $expiresAt = $consultation->metadata['expires_at'] ?? AgoraService::getExpiredTimestamp();

        return response()->json([
            'status'   => true,
            'success'  => true,
            'incoming' => true,
            'message'  => 'Panggilan masuk ditemukan.',
            'data'     => [
                'consultation_id'       => $consultation->id,
                'counseling_session_id' => $consultation->counseling_session_id,
                'caller'                => [
                    'id'   => $consultation->caller->id,
                    'name' => $consultation->caller->name,
                ],
                'app_id'                => AgoraService::getAppId(),
                'channel_name'          => $consultation->channel_name,
                'uid'                   => $receiverUid,
                'token'                 => $receiverToken,
                'call_type'             => $consultation->call_type,
                'expires_at'            => $expiresAt,
                'expired_at'            => $expiresAt,
                'created_at'            => $consultation->created_at,
            ],
        ]);
    }

    public function callStatus(Request $request, $id)
    {
        $user = $request->attributes->get('user');

        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])->find($id);

        if (!$consultation) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Konsultasi tidak ditemukan.',
            ], 404);
        }

        if (
            $consultation->caller_id != $user->id &&
            $consultation->receiver_id != $user->id
        ) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Anda tidak memiliki akses.',
            ], 403);
        }

        $isCaller = $consultation->caller_id == $user->id;
        $uid = AgoraService::generateUid($user);

        if ($isCaller) {
            $token = $consultation->metadata['caller_token'] ?? $consultation->token;
        } else {
            $token = $consultation->metadata['receiver_token'] ?? AgoraService::generateVideoCallToken($consultation->channel_name, $uid);
        }

        $expiresAt = $consultation->metadata['expires_at'] ?? AgoraService::getExpiredTimestamp();

        return response()->json([
            'status'  => true,
            'success' => true,
            'message' => 'Status konsultasi berhasil diambil.',
            'data'    => [
                'consultation_id'       => $consultation->id,
                'counseling_session_id' => $consultation->counseling_session_id,
                'call_status'           => $consultation->status,
                'started_at'            => $consultation->started_at,
                'ended_at'              => $consultation->ended_at,
                'duration'              => $consultation->duration,
                'app_id'                => AgoraService::getAppId(),
                'channel_name'          => $consultation->channel_name,
                'uid'                   => $uid,
                'token'                 => $token,
                'expires_at'            => $expiresAt,
                'expired_at'            => $expiresAt,
            ],
        ]);
    }

    public function sessionCredentials(Request $request, $sessionId)
    {
        $user = $request->attributes->get('user');

        $session = CounselingSession::with([
            'elderlyCounselee.counselee',
            'counselor',
        ])->find($sessionId);

        if (!$session) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Sesi konseling tidak ditemukan.',
            ], 404);
        }

        $isCounselor = $session->counselor_id == $user->id;
        $isCounselee = $session->elderlyCounselee &&
            $session->elderlyCounselee->counselee_id == $user->id;

        if (!$isCounselor && !$isCounselee) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke sesi konseling ini.',
            ], 403);
        }

        $credentials = AgoraService::getVideoCallCredentials($sessionId, $user);

        Log::info('Agora Video Call: Session credentials retrieved', [
            'session_id'   => $sessionId,
            'user_id'      => $user->id,
            'role'         => $user->role,
            'channel_name' => $credentials['channel_name'],
            'uid'          => $credentials['uid'],
            'expires_at'   => $credentials['expires_at'],
        ]);

        return response()->json([
            'status'  => true,
            'success' => true,
            'message' => 'Kredensial Video Call sesi konseling berhasil diambil.',
            'data'    => $credentials,
        ]);
    }

    public function rejectCall(Request $request)
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
            'consultation_id' => 'required|exists:consultations,id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])->find($request->consultation_id);

        /*
        |--------------------------------------------------------------------------
        | JIKA CONSULTATION TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */
        if (! $consultation) {

            return response()->json([
                'status' => false,
                'message' => 'Konsultasi tidak ditemukan',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI RECEIVER
        |--------------------------------------------------------------------------
        */
        if ($consultation->receiver_id != $user->id) {

            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk menolak panggilan ini',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI STATUS
        |--------------------------------------------------------------------------
        */
        if ($consultation->status !== 'calling') {

            return response()->json([
                'status' => false,
                'message' => 'Panggilan tidak dapat ditolak',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation->update([
            'status' => 'rejected',
            'ended_at' => now(),
            'duration' => 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NOTIFICATION KE CALLER
        |--------------------------------------------------------------------------
        */
        Notification::create([
            'user_id' => $consultation->caller_id,
            'title' => 'Video Call Ditolak',
            'body' => $user->name.' menolak panggilan Anda',
            'type' => 'call_rejected',
            'data' => json_encode([
                'consultation_id' => $consultation->id,
                'channel_name' => $consultation->channel_name,
            ]),
        ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Panggilan berhasil ditolak',
            'data' => $consultation->fresh([
                'caller',
                'receiver',
            ]),
        ]);
    }

    public function endCall(Request $request)
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
            'consultation_id' => 'required|exists:consultations,id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])->find($request->consultation_id);

        /*
        |--------------------------------------------------------------------------
        | JIKA CONSULTATION TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */
        if (! $consultation) {

            return response()->json([
                'status' => false,
                'message' => 'Konsultasi tidak ditemukan',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI AKSES
        |--------------------------------------------------------------------------
        | Hanya caller atau receiver yang dapat mengakhiri panggilan.
        |--------------------------------------------------------------------------
        */
        if (
            $consultation->caller_id != $user->id &&
            $consultation->receiver_id != $user->id
        ) {

            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk mengakhiri panggilan ini',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI STATUS
        |--------------------------------------------------------------------------
        */
        if (! in_array($consultation->status, [
            'calling',
            'accepted',
        ], true)) {

            return response()->json([
                'status' => false,
                'message' => 'Panggilan sudah berakhir',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG DURASI
        |--------------------------------------------------------------------------
        */
        $duration = 0;

        if ($consultation->started_at) {

            $duration = now()->diffInSeconds(
                $consultation->started_at
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation->update([
            'status' => 'ended',
            'ended_at' => now(),
            'duration' => $duration,
        ]);

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PENERIMA NOTIFIKASI
        |--------------------------------------------------------------------------
        */
        $receiverId = $consultation->caller_id == $user->id
            ? $consultation->receiver_id
            : $consultation->caller_id;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NOTIFICATION
        |--------------------------------------------------------------------------
        */
        Notification::create([
            'user_id' => $receiverId,
            'title' => 'Video Call Berakhir',
            'body' => $user->name.' mengakhiri panggilan',
            'type' => 'call_ended',
            'data' => json_encode([
                'consultation_id' => $consultation->id,
                'channel_name' => $consultation->channel_name,
            ]),
        ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Panggilan berhasil diakhiri',
            'data' => $consultation->fresh([
                'caller',
                'receiver',
            ]),
        ]);
    }

    /**
     * Endpoint diagnostik aman untuk memvalidasi konfigurasi Agora tanpa membocorkan rahasia.
     */
    public function debugAgoraSession(Request $request, $sessionId)
    {
        $user = $request->attributes->get('user') ?? auth()->user();

        $session = CounselingSession::with([
            'elderlyCounselee.counselee',
            'counselor',
        ])->find($sessionId);

        if (!$session) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Sesi konseling tidak ditemukan.',
            ], 404);
        }

        $counseleeUser = $session->elderlyCounselee?->counselee;
        $counselorUser = $session->counselor;

        $counseleeUid = $counseleeUser ? AgoraService::generateUid($counseleeUser) : null;
        $counselorUid = $counselorUser ? AgoraService::generateUid($counselorUser) : null;
        $channelName  = AgoraService::generateChannelName($session->id);

        $appId = AgoraService::getAppId();
        $hasAppId = !empty($appId);
        $hasCert  = !empty(config('services.agora.app_certificate', env('AGORA_APP_CERTIFICATE')));

        $counseleeToken = ($hasAppId && $hasCert && $counseleeUid)
            ? AgoraService::generateVideoCallToken($channelName, $counseleeUid)
            : null;

        $counselorToken = ($hasAppId && $hasCert && $counselorUid)
            ? AgoraService::generateVideoCallToken($channelName, $counselorUid)
            : null;

        return response()->json([
            'status' => true,
            'data'   => [
                'session_id'            => (int) $session->id,
                'channel_name'          => $channelName,
                'app_id_configured'     => $hasAppId,
                'certificate_configured'=> $hasCert,
                'app_id_prefix'         => $hasAppId ? substr($appId, 0, 6) . '...' : null,
                'counselee'             => [
                    'user_id'         => $counseleeUser?->id,
                    'role'            => $counseleeUser?->role,
                    'uid'             => $counseleeUid,
                    'token_generated' => !empty($counseleeToken),
                    'token_length'    => $counseleeToken ? strlen($counseleeToken) : 0,
                ],
                'counselor'             => [
                    'user_id'         => $counselorUser?->id,
                    'role'            => $counselorUser?->role,
                    'uid'             => $counselorUid,
                    'token_generated' => !empty($counselorToken),
                    'token_length'    => $counselorToken ? strlen($counselorToken) : 0,
                ],
                'duration_seconds'      => AgoraService::getExpireSeconds(),
            ],
        ]);
    }

    /**
     * Memperbarui (renew) token Agora RTC saat privilege mendekati kedaluwarsa.
     */
    public function renewToken(Request $request)
    {
        $user = $request->attributes->get('user') ?? auth()->user();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $request->validate([
            'consultation_id'       => 'nullable|exists:consultations,id',
            'counseling_session_id' => 'nullable|exists:counseling_sessions,id',
        ]);

        $consultationId = $request->input('consultation_id');
        $sessionId = $request->input('counseling_session_id');

        $channelName = null;

        if (!empty($consultationId)) {
            $consultation = Consultation::find($consultationId);
            if (!$consultation) {
                return response()->json([
                    'status'  => false,
                    'success' => false,
                    'message' => 'Konsultasi tidak ditemukan.',
                ], 404);
            }

            if ($consultation->caller_id != $user->id && $consultation->receiver_id != $user->id) {
                return response()->json([
                    'status'  => false,
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke konsultasi ini.',
                ], 403);
            }

            $channelName = $consultation->channel_name;
        } elseif (!empty($sessionId)) {
            $session = CounselingSession::with(['elderlyCounselee.counselee', 'counselor'])->find($sessionId);
            if (!$session) {
                return response()->json([
                    'status'  => false,
                    'success' => false,
                    'message' => 'Sesi konseling tidak ditemukan.',
                ], 404);
            }

            $isCounselee = $session->elderlyCounselee && $session->elderlyCounselee->counselee_id == $user->id;
            $isCounselor = $session->counselor_id == $user->id;

            if (!$isCounselee && !$isCounselor) {
                return response()->json([
                    'status'  => false,
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke sesi ini.',
                ], 403);
            }

            $channelName = AgoraService::generateChannelName($sessionId);
        } else {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'consultation_id atau counseling_session_id wajib diisi.',
            ], 422);
        }

        $uid = AgoraService::generateUid($user);
        $renewedData = AgoraService::renewToken($channelName, $uid);

        Log::info('Agora Video Call: Token renewed successfully', [
            'user_id'      => $user->id,
            'channel_name' => $channelName,
            'uid'          => $uid,
            'expires_at'   => $renewedData['expires_at'],
        ]);

        return response()->json([
            'status'  => true,
            'success' => true,
            'message' => 'Token Agora berhasil diperbarui.',
            'data'    => $renewedData,
        ]);
    }
}
