<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\ElderlyCounselee;
use App\Models\EmpowermentAssessment;
use App\Models\FallRiskScreening;
use App\Models\EducationContent;
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

        if ($user->role === 'konseli') {
            return $this->getCounselingSessionsForCounselee($user);
        }

        if ($user->role === 'konselor') {
            return $this->getCounselingSessionsForCounselor($user);
        }

        return response()->json([
            'success' => false,
            'message' => 'Role pengguna tidak dikenali.',
            'data' => null,
        ], 403);
    }

    private function getCounselingSessionsForCounselee($user)
    {
        $sessions = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])
            ->whereHas('elderlyCounselee', function ($query) use ($user) {
                $query->where('counselee_id', $user->id);
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

                // Data lansia
                'elderly_name' => $session->elderlyCounselee->elderly_name ?? null,
                'elderly_gender' => $session->elderlyCounselee->elderly_gender ?? null,
                'elderly_age' => $session->elderlyCounselee->elderly_age ?? null,
                'health_problems' => $session->elderlyCounselee->health_problems ?? null,
                'has_fallen' => $session->elderlyCounselee->has_fallen ?? null,

                // Data konselor
                'counselor_id' => $session->counselor_id,
                'counselor_name' => $session->counselor->name ?? null,
                'counselor_phone' => $session->counselor->phone ?? null,

                // Status penyelesaian
                'is_completed' => $this->isCounselingSessionCompleted($session->id),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar sesi konseling berhasil diambil.',
            'data' => $data,
        ]);
    }

    private function getCounselingSessionsForCounselor($user)
    {
        $sessions = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])
            ->where('counselor_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $groupedData = $sessions
            ->groupBy(function ($session) {
                return $session->elderlyCounselee->counselee_id ?? 0;
            })
            ->map(function ($items) {
                $firstSession = $items->first();
                $elderlyCounselee = $firstSession->elderlyCounselee;
                $counselee = $elderlyCounselee->counselee ?? null;

                return [
                    'elderly_counselee_id' =>  $elderlyCounselee->id ?? null,

                    // ================= DATA KONSELI =================
                    'counselee_id' => $elderlyCounselee->counselee_id ?? null,
                    'counselee_name' => $counselee->name ?? null,
                    'counselee_phone' => $counselee->phone ?? null,

                    // ================= DATA LANSIA =================
                    'elderly_name' => $elderlyCounselee->elderly_name ?? null,
                    'elderly_gender' => $elderlyCounselee->elderly_gender ?? null,
                    'elderly_age' => $elderlyCounselee->elderly_age ?? null,
                    'health_problems' => $elderlyCounselee->health_problems ?? null,
                    'has_fallen' => $elderlyCounselee->has_fallen ?? null,

                    // ================= RINGKASAN =================
                    'total_sessions' => $items->count(),

                    // ================= DAFTAR SESI =================
                    'sessions' => $items->map(function ($session) {
                        return [
                            'id' => $session->id,
                            'service_mode' => $session->service_mode,
                            'status' => $session->status,
                            'created_at' => optional($session->created_at)
                                ->format('d-m-Y H:i'),
                            'is_completed' => $this->isCounselingSessionCompleted($session->id),
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Daftar sesi konseling berhasil diambil.',
            'data' => $groupedData,
        ]);
    }

    public function getCounselingSessionsById(Request $request, $elderlyCounseleeId)
    {
        $user = $request->attributes->get('user');

        // Pastikan hanya konselor yang dapat mengakses endpoint ini
        if ($user->role !== 'konselor') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
                'data' => null,
            ], 403);
        }

        // Ambil seluruh sesi konseling berdasarkan elderly_counselee_id
        // dan hanya milik konselor yang sedang login
        $sessions = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])
            ->where('elderly_counselee_id', $elderlyCounseleeId)
            ->where('counselor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika data tidak ditemukan
        if ($sessions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data sesi konseling tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        // Ambil data umum dari sesi pertama
        $firstSession = $sessions->first();
        $elderlyCounselee = $firstSession->elderlyCounselee;
        $counselee = $elderlyCounselee->counselee ?? null;

        // Format response
        $data = [
            'elderly_counselee_id' => $elderlyCounselee->id ?? null,

            // ================= DATA KONSELI =================
            'counselee_id' => $elderlyCounselee->counselee_id ?? null,
            'counselee_name' => $counselee->name ?? null,
            'counselee_phone' => $counselee->phone ?? null,

            // ================= DATA LANSIA =================
            'elderly_name' => $elderlyCounselee->elderly_name ?? null,
            'elderly_gender' => $elderlyCounselee->elderly_gender ?? null,
            'elderly_age' => $elderlyCounselee->elderly_age ?? null,
            'health_problems' => $elderlyCounselee->health_problems ?? null,
            'has_fallen' => $elderlyCounselee->has_fallen ?? null,

            // ================= DATA KONSELOR =================
            'counselor_id' => $firstSession->counselor_id,
            'counselor_name' => $firstSession->counselor->name ?? null,
            'counselor_phone' => $firstSession->counselor->phone ?? null,

            // ================= RINGKASAN =================
            'total_sessions' => $sessions->count(),

            // ================= DAFTAR SESI =================
            'sessions' => $sessions->map(function ($session) {
                // Ambil hasil screening risiko jatuh
                $fallRisk = FallRiskScreening::where(
                    'counseling_session_id',
                    $session->id
                )->first();

                // Ambil hasil asesmen pemberdayaan
                $empowerment = EmpowermentAssessment::where(
                    'counseling_session_id',
                    $session->id
                )->first();

                return [
                    'id' => $session->id,
                    'service_mode' => $session->service_mode,
                    'status' => $session->status,
                    'created_at' => optional($session->created_at)
                        ->format('d-m-Y H:i'),

                    // Status penyelesaian sesi
                    'is_completed' => $this->isCounselingSessionCompleted($session->id),

                    // Hasil screening risiko jatuh
                    'fall_risk' => $fallRisk ? [
                        'id' => $fallRisk->id,
                        'total_score' => $fallRisk->total_score,
                        'risk_level' => $fallRisk->risk_level,
                        'interpretation' => $fallRisk->interpretation,
                    ] : null,

                    // Hasil asesmen pemberdayaan
                    'empowerment' => $empowerment ? [
                        'id' => $empowerment->id,
                        'total_score' => $empowerment->total_score,
                        'empowerment_level' => $empowerment->empowerment_level,
                    ] : null,
                ];
            })->values(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail sesi konseling berhasil diambil.',
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

    public function showEducationContents() 
    {
        $contents = EducationContent::where('is_active', 1)->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar konten edukasi berhasil diambil',
            'data'  => $contents
        ]);
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
