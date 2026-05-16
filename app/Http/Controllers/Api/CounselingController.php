<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\ElderlyCounselee;
use App\Models\EmpowermentAssessment;
use App\Models\FallRiskScreening;
use App\Models\LogBook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CounselingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('user');

        $sessions = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])
            ->when($user->role === 'konseli', function ($q) use ($user) {
                $q->whereHas('elderlyCounselee', function ($subQuery) use ($user) {
                    $subQuery->where('counselee_id', $user->id);
                });
            })
            ->when($user->role === 'konselor', function ($q) use ($user) {
                $q->where('counselor_id', $user->id);
            })
            ->orderBy('id', 'asc')
            ->get();

        $data = $sessions->map(function ($session) {
            return [
                'id' => $session->id,

                // Informasi sesi
                'service_mode' => $session->service_mode,
                'status' => $session->status,
                'created_at' => optional($session->created_at)
                    ->format('d-m-Y H:i'),

                // Data konseli (pendamping)
                'counselee_id' =>
                    $session->elderly_counselee->counselee_id ?? null,
                'counselee_name' =>
                    $session->elderly_counselee->counselee->name ?? null,
                'counselee_phone' =>
                    $session->elderly_counselee->counselee->phone ?? null,

                // Data lansia
                'elderly_name' =>
                    $session->elderly_counselee->elderly_name ?? null,
                'elderly_gender' =>
                    $session->elderly_counselee->elderly_gender ?? null,
                'elderly_age' =>
                    $session->elderly_counselee->elderly_age ?? null,
                'health_problems' =>
                    $session->elderly_counselee->health_problems ?? null,
                'has_fallen' =>
                    $session->elderly_counselee->has_fallen ?? null,

                // Data konselor
                'counselor_id' => $session->counselor_id,
                'counselor_name' =>
                    $session->counselor->name ?? null,
                'counselor_phone' =>
                    $session->counselor->phone ?? null,

                // Session Completion Status
                'is_completed' => $this->isCounselingSessionCompleted($session->id),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar sesi konseling berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function countCounselingSessions(Request $request)
    {
        // ================= AMBIL USER LOGIN =================
        $user = $request->attributes->get('user');

        // ================= HITUNG JUMLAH SESI =================
        $total = CounselingSession::query()
            ->when($user->role === 'konseli', function ($q) use ($user) {
                // Konseli hanya melihat sesi miliknya
                $q->whereHas('elderlyCounselee', function ($subQuery) use ($user) {
                    $subQuery->where('counselee_id', $user->id);
                });
            })
            ->when($user->role === 'konselor', function ($q) use ($user) {
                // Konselor hanya melihat sesi yang ditangani
                $q->where('counselor_id', $user->id);
            })
            ->count();

        // ================= RESPONSE =================
        return response()->json([
            'success' => true,
            'message' => 'Jumlah sesi konseling berhasil dihitung.',
            'total'   => $total,
        ]);
    }

    private function isCounselingSessionCompleted(int $sessionId)
    {
        $hasScreening = FallRiskScreening::where('counseling_session_id', $sessionId)->exists();
        $hasAssessment = EmpowermentAssessment::where('counseling_session_id', $sessionId)->exists();

        return $hasScreening && $hasAssessment;
    }

    /* private function formatStatus($status)
    {
        return match ($status) {
            'pending' => 'Menunggu',
            'ongoing' => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak diketahui',
        };
    }

    public function store(Request $request)
    {
        // Ambil user yang sedang login (dari middleware)
        $user = $request->attributes->get('user');

        // =========================
        // VALIDASI INPUT
        // =========================
        $validator = Validator::make($request->all(), [
            'id'             => 'nullable|exists:counseling_sessions,id', // untuk mode update
            'counselor_id'   => 'required|exists:counselors,id',
            'elderly_id'     => 'nullable|exists:elderly,id',
            'schedule_date'  => 'required|date|after_or_equal:today',
            'schedule_time'  => 'required|date_format:H:i',
            'note'           => 'nullable|string',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'data'    => $validator->errors()
            ], 422);
        }

        // Ambil hanya data yang lolos validasi
        $data = $validator->validated();

        // =========================
        // CEK MODE (CREATE / UPDATE)
        // =========================
        $counseling = !empty($data['id'])
            ? CounselingSession::find($data['id'])
            : null;

        $isUpdate = $counseling ? true : false;

        // =========================
        // HANDLE elderly_id
        // =========================
        if ($isUpdate && $user->role !== 'konseli') {
            // Jika UPDATE & bukan konseli → tidak boleh ubah lansia
            $data['elderly_id'] = $counseling->elderly_id;
        } else {
            // Jika konseli → validasi kepemilikan lansia
            $elderlyIds = Elderly::where('counselee_id', $user->id)->pluck('id');

            if (empty($data['elderly_id'])) {

                // Jika hanya 1 lansia → otomatis gunakan
                if ($elderlyIds->count() === 1) {
                    $data['elderly_id'] = $elderlyIds->first();

                    // Jika tidak punya lansia
                } elseif ($elderlyIds->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data lansia tidak ditemukan'
                    ], 404);

                    // Jika lebih dari 1 → wajib pilih
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Pilih salah satu lansia dari input'
                    ], 422);
                }

                // Validasi bahwa lansia milik user
            } elseif (!$elderlyIds->contains($data['elderly_id'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lansia tidak valid / bukan milik Anda'
                ], 403);
            }
        }

        // =========================
        // CEK BENTROK JADWAL
        // =========================
        $isConflict = CounselingSession::where('counselor_id', $data['counselor_id'])
            ->where('schedule_date', $data['schedule_date'])
            ->where('schedule_time', $data['schedule_time'])
            ->when($isUpdate, fn($q) => $q->where('id', '!=', $data['id'])) // abaikan dirinya sendiri saat update
            ->exists();

        if ($isConflict) {
            return response()->json([
                'status'  => false,
                'message' => 'Jadwal bentrok! Konselor sudah memiliki jadwal di waktu tersebut.'
            ], 409);
        }

        // =========================
        // SIMPAN DATA
        // =========================
        if ($isUpdate) {
            // Update data
            $counseling->update($data + ['updated_by' => $user->id]);
        } else {
            // Insert data baru (tambahkan counselee_id dari user login)
            $counseling = CounselingSession::create(
                $data + ['counselee_id' => $user->id]
            );
        }

        // =========================
        // RESPONSE
        // =========================
        return response()->json([
            'status'  => true,
            'message' => $isUpdate
                ? 'Konseling berhasil diperbarui'
                : 'Konseling berhasil ditambahkan',
            'data'    => $counseling->fresh() // ambil data terbaru dari DB
        ], $isUpdate ? 200 : 201);
    }

    public function saveLogBook(Request $request)
    {
        // ================= VALIDASI =================
        $validator = Validator::make($request->all(), [
            'counseling_session_id' => 'required|exists:counseling_sessions,id',
            'score' => 'nullable|integer|min:1|max:5',
            'activity' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'data'    => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // ================= AMBIL SESSION =================
            $session = CounselingSession::find($request->counseling_session_id);

            if (!$session) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sesi konseling tidak ditemukan'
                ], 404);
            }

            // ================= CEK DUPLIKAT =================
            $existing = LogBook::where(
                'counseling_session_id',
                $request->counseling_session_id
            )->first();

            if ($existing) {
                return response()->json([
                    'status' => false,
                    'message' => 'Log Book sudah pernah diisi untuk sesi ini'
                ], 400);
            }

            // ================= SIMPAN LOG BOOK =================
            $logBook = LogBook::create([
                'counseling_session_id' => $request->counseling_session_id,
                'score' => $request->score,
                'activity' => $request->activity ?? null,
            ]);

            // ================= UPDATE STATUS =================
            $session->update([
                'status' => 'completed'
            ]);

            DB::commit();

            // ================= RESPONSE =================
            return response()->json([
                'status'  => true,
                'message' => 'Log Book berhasil disimpan & konseling selesai',
                'data'    => [
                    'log_book' => $logBook,
                    'session'  => $session
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getSessionScores($sessionId)
    {
        // ================= CEK SESSION =================
        $session = CounselingSession::find($sessionId);

        if (!$session) {
            return response()->json([
                'status'  => false,
                'message' => 'Sesi konseling tidak ditemukan'
            ], 404);
        }

        // ================= AMBIL DATA =================
        $fallRiskScreening = FallRiskScreening::where(
            'counseling_session_id',
            $session->id
        )->first();

        $empowermentAssessment = EmpowermentAssessment::where(
            'counseling_session_id',
            $session->id
        )->first();

        $logBook = LogBook::where(
            'counseling_session_id',
            $session->id
        )->first();

        // ================= RESPONSE =================
        return response()->json([
            'status'  => true,
            'message' => 'Data skor berhasil diambil',
            'data'    => [
                'fall_risk_screening'    => $fallRiskScreening,
                'empowerment_assessment' => $empowermentAssessment,
                'log_book'               => $logBook,
            ]
        ]);
    } */
}
