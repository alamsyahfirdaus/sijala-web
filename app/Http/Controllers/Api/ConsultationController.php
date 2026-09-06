<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\CounselingSession;
use App\Models\Notification;
use App\Models\User;
use App\Services\Agora\AgoraService;
use Illuminate\Http\Request;

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

        /*
        |--------------------------------------------------------------------------
        | DEFAULT SESSION
        |--------------------------------------------------------------------------
        */
        $sessionId = null;

        /*
        |--------------------------------------------------------------------------
        | USER ADALAH KONSELI
        |--------------------------------------------------------------------------
        | KHUSUS UJICOBA:
        | user_id 94-120  -> konselor ID 3
        | user_id 121-124 -> konselor ID 125
        | selain itu      -> berdasarkan puskesmas
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'konseli') {

            // =====================================================
            // KHUSUS UJICOBA
            // =====================================================
            if ($user->id >= 94 && $user->id <= 120) {

                $receiverId = 3;

            } elseif ($user->id >= 121 && $user->id <= 124) {

                $receiverId = 125;

            } else {

                // =====================================================
                // LOGIKA NORMAL
                // =====================================================
                $receiver = User::where([
                    'puskesmas_id' => $user->puskesmas_id,
                    'role' => 'konselor',
                ])
                    ->where('is_active', true)
                    ->first();

                if (! $receiver) {

                    return response()->json([
                        'status' => false,
                        'message' => 'Konselor tidak ditemukan.',
                    ], 404);
                }

                $receiverId = $receiver->id;
            }

            // =====================================================
            // PASTIKAN RECEIVER ADA
            // =====================================================
            $receiver = User::find($receiverId);

            if (! $receiver) {

                return response()->json([
                    'status' => false,
                    'message' => 'Konselor ujicoba tidak ditemukan.',
                ], 404);
            }

            // =====================================================
            // PASTIKAN RECEIVER AKTIF
            // =====================================================
            if (! $receiver->is_active) {

                return response()->json([
                    'status' => false,
                    'message' => 'Konselor sedang tidak aktif.',
                ], 400);
            }
        }
        /*
        |--------------------------------------------------------------------------
        | USER BUKAN KONSELI
        |--------------------------------------------------------------------------
        | Menggunakan sesi konseling yang sudah dijadwalkan.
        |--------------------------------------------------------------------------
        */
        else {

            $request->validate(
                [
                    'counseling_session_id' => 'required|exists:counseling_sessions,id',
                ],
                [
                    'counseling_session_id.required' => 'Sesi konseling wajib dipilih.',
                    'counseling_session_id.exists' => 'Sesi konseling yang dipilih tidak ditemukan.',
                ]
            );

            $sessionId = $request->input('counseling_session_id');

            /*
            |--------------------------------------------------------------------------
            | AMBIL SESI KONSELING
            |--------------------------------------------------------------------------
            */
            $session = CounselingSession::with([
                'elderlyCounselee.counselee',
            ])->find($sessionId);

            /*
            |--------------------------------------------------------------------------
            | VALIDASI PESERTA SESI
            |--------------------------------------------------------------------------
            */
            if (
                ! $session ||
                ! $session->elderlyCounselee ||
                ! $session->elderlyCounselee->counselee
            ) {

                return response()->json([
                    'status' => false,
                    'message' => 'Peserta pada sesi konseling tidak ditemukan.',
                ], 404);
            }

            $receiver = $session->elderlyCounselee->counselee;

            $receiverId = $receiver->id;

            /*
            |--------------------------------------------------------------------------
            | TIDAK BOLEH MELAKUKAN CALL KE DIRI SENDIRI
            |--------------------------------------------------------------------------
            */
            if ($receiverId === $user->id) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak dapat melakukan konsultasi dengan diri sendiri.',
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | RECEIVER TIDAK AKTIF
            |--------------------------------------------------------------------------
            */
            if (! $receiver->is_active) {

                return response()->json([
                    'status' => false,
                    'message' => 'Penerima konsultasi sedang tidak aktif.',
                ], 400);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CEK KONSULTASI AKTIF
        |--------------------------------------------------------------------------
        | Mencegah dua video call aktif antara user yang sama.
        |--------------------------------------------------------------------------
        */
        $existingConsultation = Consultation::where(function ($query) use ($user, $receiverId) {

            $query->where(function ($q) use ($user, $receiverId) {

                $q->where('caller_id', $user->id)
                    ->where('receiver_id', $receiverId);

            })->orWhere(function ($q) use ($user, $receiverId) {

                $q->where('caller_id', $receiverId)
                    ->where('receiver_id', $user->id);

            });

        })
            ->whereIn('status', [
                'calling',
                'accepted',
            ])
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | AKHIRI KONSULTASI LAMA
        |--------------------------------------------------------------------------
        */
        if ($existingConsultation) {

            $duration = 0;

            if ($existingConsultation->started_at) {

                $duration = now()->diffInSeconds(
                    $existingConsultation->started_at
                );
            }

            $existingConsultation->update([
                'status' => 'ended',
                'ended_at' => now(),
                'duration' => $duration,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE CHANNEL AGORA
        |--------------------------------------------------------------------------
        */
        $agora = AgoraService::generateCallData(
            $user->id,
            $receiverId
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::create([
            'counseling_session_id' => $sessionId,
            'caller_id' => $user->id,
            'receiver_id' => $receiverId,
            'channel_name' => $agora['channel_name'],
            'token' => $agora['token'],
            'call_type' => 'video',
            'status' => 'calling',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NOTIFICATION
        |--------------------------------------------------------------------------
        */
        Notification::create([
            'user_id' => $receiverId,
            'title' => 'Incoming Video Call',
            'body' => $user->name.' memanggil Anda',
            'type' => 'incoming_call',
            'data' => json_encode([
                'consultation_id' => $consultation->id,
                'counseling_session_id' => $sessionId,
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
            'message' => 'Permintaan konsultasi berhasil dikirim.',
            'data' => $consultation,
        ]);
    }

    public function requestCall1(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */
        $user = $request->attributes->get('user');

        /*
        |--------------------------------------------------------------------------
        | DEFAULT SESSION
        |--------------------------------------------------------------------------
        */
        $sessionId = null;

        /*
        |--------------------------------------------------------------------------
        | USER ADALAH KONSELI
        |--------------------------------------------------------------------------
        | Konseli akan otomatis menghubungi konselor berdasarkan puskesmas.
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'konseli') {

            $receiver = User::where([
                'puskesmas_id' => $user->puskesmas_id,
                'role' => 'konselor',
            ])
                ->where('is_active', true)
                ->first();

            if (! $receiver) {

                return response()->json([
                    'status' => false,
                    'message' => 'Konselor tidak ditemukan.',
                ], 404);
            }

            $receiverId = $receiver->id;
        }

        /*
        |--------------------------------------------------------------------------
        | USER BUKAN KONSELI
        |--------------------------------------------------------------------------
        | Menggunakan sesi konseling yang sudah dijadwalkan.
        |--------------------------------------------------------------------------
        */
        else {

            $request->validate(
                [
                    'counseling_session_id' => 'required|exists:counseling_sessions,id',
                ],
                [
                    'counseling_session_id.required' => 'Sesi konseling wajib dipilih.',
                    'counseling_session_id.exists' => 'Sesi konseling yang dipilih tidak ditemukan.',
                ]
            );

            $sessionId = $request->input('counseling_session_id');

            /*
            |--------------------------------------------------------------------------
            | AMBIL SESI KONSELING
            |--------------------------------------------------------------------------
            */
            $session = CounselingSession::with([
                'elderlyCounselee.counselee',
            ])->find($sessionId);

            /*
            |--------------------------------------------------------------------------
            | VALIDASI PESERTA SESI
            |--------------------------------------------------------------------------
            */
            if (
                ! $session ||
                ! $session->elderlyCounselee ||
                ! $session->elderlyCounselee->counselee
            ) {

                return response()->json([
                    'status' => false,
                    'message' => 'Peserta pada sesi konseling tidak ditemukan.',
                ], 404);
            }

            $receiver = $session->elderlyCounselee->counselee;

            $receiverId = $receiver->id;

            /*
            |--------------------------------------------------------------------------
            | TIDAK BOLEH MELAKUKAN CALL KE DIRI SENDIRI
            |--------------------------------------------------------------------------
            */
            if ($receiverId === $user->id) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak dapat melakukan konsultasi dengan diri sendiri.',
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | RECEIVER TIDAK AKTIF
            |--------------------------------------------------------------------------
            */
            if (! $receiver->is_active) {

                return response()->json([
                    'status' => false,
                    'message' => 'Penerima konsultasi sedang tidak aktif.',
                ], 400);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CEK KONSULTASI AKTIF
        |--------------------------------------------------------------------------
        | Mencegah dua video call aktif antara user yang sama.
        |--------------------------------------------------------------------------
        */
        $existingConsultation = Consultation::where(function ($query) use ($user, $receiverId) {

            $query->where(function ($q) use ($user, $receiverId) {

                $q->where('caller_id', $user->id)
                    ->where('receiver_id', $receiverId);

            })->orWhere(function ($q) use ($user, $receiverId) {

                $q->where('caller_id', $receiverId)
                    ->where('receiver_id', $user->id);

            });

        })
            ->whereIn('status', [
                'calling',
                'accepted',
            ])
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | AKHIRI KONSULTASI LAMA
        |--------------------------------------------------------------------------
        */
        if ($existingConsultation) {

            $duration = 0;

            if ($existingConsultation->started_at) {

                $duration = now()->diffInSeconds(
                    $existingConsultation->started_at
                );
            }

            $existingConsultation->update([
                'status' => 'ended',
                'ended_at' => now(),
                'duration' => $duration,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE CHANNEL AGORA
        |--------------------------------------------------------------------------
        */
        $agora = AgoraService::generateCallData(
            $user->id,
            $receiverId
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::create([
            'counseling_session_id' => $sessionId,
            'caller_id' => $user->id,
            'receiver_id' => $receiverId,
            'channel_name' => $agora['channel_name'],
            'token' => $agora['token'],
            'call_type' => 'video',
            'status' => 'calling',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NOTIFICATION
        |--------------------------------------------------------------------------
        */
        Notification::create([
            'user_id' => $receiverId,
            'title' => 'Incoming Video Call',
            'body' => $user->name.' memanggil Anda',
            'type' => 'incoming_call',
            'data' => json_encode([
                'consultation_id' => $consultation->id,
                'counseling_session_id' => $sessionId,
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
            'message' => 'Permintaan konsultasi berhasil dikirim.',
            'data' => $consultation,
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
                'message' => 'Anda tidak memiliki akses untuk menerima panggilan ini',
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
                'message' => 'Panggilan tidak dapat diterima',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation->update([
            'status' => 'accepted',
            'started_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NOTIFICATION KE CALLER
        |--------------------------------------------------------------------------
        */
        Notification::create([
            'user_id' => $consultation->caller_id,
            'title' => 'Video Call Diterima',
            'body' => $user->name.' menerima panggilan Anda',
            'type' => 'call_accepted',
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
            'message' => 'Panggilan berhasil diterima',
            'data' => $consultation->fresh([
                'caller',
                'receiver',
            ]),
        ]);
    }

    public function incomingCall(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */
        $user = $request->attributes->get('user');

        /*
        |--------------------------------------------------------------------------
        | CARI PANGGILAN MASUK
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])
            ->where('receiver_id', $user->id)
            ->where('status', 'calling')
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA PANGGILAN
        |--------------------------------------------------------------------------
        */
        if (! $consultation) {

            return response()->json([
                'status' => true,
                'incoming' => false,
                'message' => 'Tidak ada panggilan masuk.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ADA PANGGILAN MASUK
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'incoming' => true,
            'message' => 'Panggilan masuk ditemukan.',
            'data' => [
                'consultation_id' => $consultation->id,
                'caller' => [
                    'id' => $consultation->caller->id,
                    'name' => $consultation->caller->name,
                ],
                'channel_name' => $consultation->channel_name,
                'token' => $consultation->token,
                'call_type' => $consultation->call_type,
                'created_at' => $consultation->created_at,
            ],
        ]);
    }

    public function callStatus(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */
        $user = $request->attributes->get('user');

        /*
        |--------------------------------------------------------------------------
        | AMBIL CONSULTATION
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::with([
            'caller',
            'receiver',
        ])->find($id);

        /*
        |--------------------------------------------------------------------------
        | TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */
        if (! $consultation) {

            return response()->json([
                'status' => false,
                'message' => 'Konsultasi tidak ditemukan.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PESERTA
        |--------------------------------------------------------------------------
        */
        if (
            $consultation->caller_id != $user->id &&
            $consultation->receiver_id != $user->id
        ) {

            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Status konsultasi berhasil diambil.',
            'data' => [
                'consultation_id' => $consultation->id,
                'call_status' => $consultation->status,
                'started_at' => $consultation->started_at,
                'ended_at' => $consultation->ended_at,
                'duration' => $consultation->duration,
                'channel_name' => $consultation->channel_name,
            ],
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
}
