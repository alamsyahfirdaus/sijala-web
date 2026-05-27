<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * LIST RIWAYAT KONSULTASI
     * --------------------------------------------------------------------------
     * Menampilkan seluruh riwayat konsultasi/video call
     * berdasarkan user yang sedang login.
     * --------------------------------------------------------------------------
     */
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
        | Menampilkan consultation dimana user menjadi:
        | - caller
        | - receiver
        |--------------------------------------------------------------------------
        */
        $consultations = Consultation::with([
            'caller',
            'receiver',
        ])
            ->where('caller_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
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

    /**
     * --------------------------------------------------------------------------
     * DETAIL KONSULTASI
     * --------------------------------------------------------------------------
     * Menampilkan detail consultation berdasarkan ID.
     * Hanya caller atau receiver yang dapat mengakses.
     * --------------------------------------------------------------------------
     */
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
        | AMBIL DETAIL CONSULTATION
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

    /**
     * --------------------------------------------------------------------------
     * REQUEST VIDEO CALL
     * --------------------------------------------------------------------------
     * Membuat permintaan video call baru.
     *
     * Jika user adalah:
     * - konseli  → otomatis mencari konselor berdasarkan puskesmas
     * - selain itu → receiver dipilih manual
     * --------------------------------------------------------------------------
     */
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
        | JIKA USER ADALAH KONSELI
        |--------------------------------------------------------------------------
        */
        if ($user->role == 'konseli') {

            /*
            |--------------------------------------------------------------------------
            | CARI KONSELOR BERDASARKAN PUSKESMAS
            |--------------------------------------------------------------------------
            */
            $receiver = User::where([
                'puskesmas_id' => $user->puskesmas_id,
                'role' => 'konselor',
            ])->first();

            /*
            |--------------------------------------------------------------------------
            | JIKA KONSELOR TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */
            if (! $receiver) {

                return response()->json([
                    'status' => false,
                    'message' => 'Konselor tidak ditemukan',
                ], 404);
            }

            $receiverId = $receiver->id;
        }

        /*
        |--------------------------------------------------------------------------
        | USER BUKAN KONSELI
        |--------------------------------------------------------------------------
        */
        else {

            /*
            |--------------------------------------------------------------------------
            | VALIDASI INPUT
            |--------------------------------------------------------------------------
            */
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
            ]);

            $receiverId = $request->input('receiver_id');

            /*
            |--------------------------------------------------------------------------
            | TIDAK BOLEH CALL DIRI SENDIRI
            |--------------------------------------------------------------------------
            */
            if ($receiverId == $user->id) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak dapat melakukan konsultasi dengan diri sendiri',
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | AMBIL RECEIVER
            |--------------------------------------------------------------------------
            */
            $receiver = User::find($receiverId);

            /*
            |--------------------------------------------------------------------------
            | JIKA RECEIVER TIDAK DITEMUKAN
            |--------------------------------------------------------------------------
            */
            if (! $receiver) {

                return response()->json([
                    'status' => false,
                    'message' => 'Penerima konsultasi tidak ditemukan',
                ], 404);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CEK KONSULTASI AKTIF
        |--------------------------------------------------------------------------
        | Mencegah duplicate active consultation.
        |--------------------------------------------------------------------------
        */
        $existingConsultation = Consultation::where(function ($query) use ($user, $receiverId) {

            $query->where('caller_id', $user->id)
                ->where('receiver_id', $receiverId);

        })->orWhere(function ($query) use ($user, $receiverId) {

            $query->where('caller_id', $receiverId)
                ->where('receiver_id', $user->id);

        })->whereIn('status', [
            'calling',
            'accepted',
        ])
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | AUTO END CONSULTATION LAMA
        |--------------------------------------------------------------------------
        */
        if ($existingConsultation) {

            $duration = 0;

            /*
            |--------------------------------------------------------------------------
            | HITUNG DURASI
            |--------------------------------------------------------------------------
            */
            if ($existingConsultation->started_at) {

                $duration = now()->diffInSeconds(
                    $existingConsultation->started_at
                );
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS MENJADI ENDED
            |--------------------------------------------------------------------------
            */
            $existingConsultation->update([
                'status' => 'ended',
                'ended_at' => now(),
                'duration' => $duration,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MEMBUAT CHANNEL NAME AGORA
        |--------------------------------------------------------------------------
        */
        $channelName =
            'consult_'.
            $user->id.'_'.
            $receiverId.'_'.
            time();

        /*
        |--------------------------------------------------------------------------
        | TOKEN AGORA
        |--------------------------------------------------------------------------
        | Sementara menggunakan TEMP_TOKEN.
        | Nanti dapat diganti menggunakan AgoraService.
        |--------------------------------------------------------------------------
        */
        $token = 'TEMP_TOKEN';

        /*
        |--------------------------------------------------------------------------
        | MEMBUAT CONSULTATION BARU
        |--------------------------------------------------------------------------
        */
        $consultation = Consultation::create([
            'caller_id' => $user->id,
            'receiver_id' => $receiverId,
            'channel_name' => $channelName,
            'token' => $token,
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
            'data' => [
                'consultation_id' => $consultation->id,
                'channel_name' => $channelName,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Permintaan konsultasi berhasil dikirim',
            'data' => $consultation,
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * ACCEPT VIDEO CALL
     * --------------------------------------------------------------------------
     * Receiver menerima panggilan video.
     * --------------------------------------------------------------------------
     */
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
        if ($consultation->status != 'calling') {

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
            'type' => 'consultation',
            'data' => [
                'consultation_id' => $consultation->id,
                'channel_name' => $consultation->channel_name,
            ],
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

    /**
     * --------------------------------------------------------------------------
     * REJECT VIDEO CALL
     * --------------------------------------------------------------------------
     * Receiver menolak panggilan video.
     * --------------------------------------------------------------------------
     */
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
        ])->findOrFail(
            $request->consultation_id
        );

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
        if ($consultation->status != 'calling') {

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
            'type' => 'missed_call',
            'data' => [
                'consultation_id' => $consultation->id,
                'channel_name' => $consultation->channel_name,
            ],
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
}
