<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\ElderlyCounselee;
use App\Models\EmpowermentAssessment;
use App\Models\FallRiskScreening;
use App\Models\EducationContent;
use App\Models\Evaluation;
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

        // =========================================================
        // VALIDASI ROLE
        // =========================================================
        if ($user->role !== 'konselor') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
                'data' => null,
            ], 403);
        }

        // =========================================================
        // AMBIL DATA SESI KONSELING
        // =========================================================
        $sessions = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])
            ->where('elderly_counselee_id', $elderlyCounseleeId)
            ->where('counselor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // =========================================================
        // VALIDASI DATA
        // =========================================================
        if ($sessions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data sesi konseling tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        // =========================================================
        // DATA UMUM
        // =========================================================
        $firstSession = $sessions->first();

        $elderlyCounselee =
            $firstSession->elderlyCounselee;

        $counselee =
            $elderlyCounselee->counselee ?? null;

        // =========================================================
        // FORMAT RESPONSE
        // =========================================================
        $data = [

            // =====================================================
            // DATA KONSELI
            // =====================================================
            'elderly_counselee_id' =>
                $elderlyCounselee->id ?? null,

            'counselee_id' =>
                $elderlyCounselee->counselee_id ?? null,

            'counselee_name' =>
                $counselee->name ?? null,

            'counselee_phone' =>
                $counselee->phone ?? null,

            // =====================================================
            // DATA LANSIA
            // =====================================================
            'elderly_name' =>
                $elderlyCounselee->elderly_name ?? null,

            'elderly_gender' =>
                $elderlyCounselee->elderly_gender ?? null,

            'elderly_age' =>
                $elderlyCounselee->elderly_age ?? null,

            'health_problems' =>
                $elderlyCounselee->health_problems ?? null,

            'has_fallen' =>
                $elderlyCounselee->has_fallen ?? null,

            // =====================================================
            // DATA KONSELOR
            // =====================================================
            'counselor_id' =>
                $firstSession->counselor_id,

            'counselor_name' =>
                optional($firstSession->counselor)->name,

            'counselor_phone' =>
                optional($firstSession->counselor)->phone,

            // =====================================================
            // RINGKASAN
            // =====================================================
            'total_sessions' => $sessions->count(),

            // =====================================================
            // DAFTAR SESI
            // =====================================================
            'sessions' => $sessions->map(function ($session) {

                // ============================================
                // SCREENING RISIKO JATUH
                // ============================================
                $fallRisk = FallRiskScreening::where(
                    'counseling_session_id',
                    $session->id
                )->first();

                // ============================================
                // ASESMEN PEMBERDAYAAN
                // ============================================
                $empowerment = EmpowermentAssessment::where(
                    'counseling_session_id',
                    $session->id
                )->first();

                // ============================================
                // HASIL EVALUASI
                // ============================================
                $evaluations = Evaluation::with([
                        'topic:id,topic'
                    ])
                    ->where(
                        'counseling_session_id',
                        $session->id
                    )
                    ->orderBy('id', 'asc')
                    ->get();

                return [

                    // ========================================
                    // DATA SESI
                    // ========================================
                    'id' => $session->id,

                    'service_mode' =>
                        $session->service_mode,

                    'status' =>
                        $session->status,

                    'created_at' =>
                        optional(
                            $session->created_at
                        )->format('d-m-Y H:i'),

                    // ========================================
                    // STATUS PENYELESAIAN
                    // ========================================
                    'is_completed' =>
                        $this->isCounselingSessionCompleted(
                            $session->id
                        ),

                    // ========================================
                    // SCREENING RISIKO JATUH
                    // ========================================
                    'fall_risk' => $fallRisk
                        ? [
                            'id' =>
                                $fallRisk->id,

                            'total_score' =>
                                $fallRisk->total_score,

                            'risk_level' =>
                                $fallRisk->risk_level,

                            'interpretation' =>
                                $fallRisk->interpretation,
                        ]
                        : null,

                    // ========================================
                    // ASESMEN PEMBERDAYAAN
                    // ========================================
                    'empowerment' => $empowerment
                        ? [
                            'id' =>
                                $empowerment->id,

                            'total_score' =>
                                $empowerment->total_score,

                            'empowerment_level' =>
                                $empowerment->empowerment_level,

                            'interpretation' =>
                                $empowerment->interpretation ?? null,
                        ]
                        : null,

                    // ========================================
                    // HASIL EVALUASI
                    // ========================================
                    'evaluations' => $evaluations->map(
                        function ($evaluation) {

                            return [
                                'id' =>
                                    $evaluation->id,

                                'evaluation_topic_id' =>
                                    $evaluation->evaluation_topic_id,

                                'topic' =>
                                    optional(
                                        $evaluation->topic
                                    )->topic,

                                'total_questions' =>
                                    $evaluation->total_questions,

                                'correct_answers' =>
                                    $evaluation->correct_answers,

                                'wrong_answers' =>
                                    $evaluation->total_questions -
                                    $evaluation->correct_answers,

                                'total_score' =>
                                    $evaluation->total_score,

                                'percentage' =>
                                    $evaluation->percentage,

                                'category' =>
                                    $evaluation->category,

                                'interpretation' =>
                                    $evaluation->interpretation ?? null,
                            ];
                        }
                    )->values(),
                ];
            })->values(),
        ];

        // =========================================================
        // RESPONSE
        // =========================================================
        return response()->json([
            'success' => true,
            'message' =>
                'Detail sesi konseling berhasil diambil.',
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

    // private function isCounselingSessionCompleted(int $sessionId) 
    // {
    //     $hasScreening = FallRiskScreening::where(
    //         'counseling_session_id',
    //         $sessionId
    //     )->exists();

    //     $hasAssessment = EmpowermentAssessment::where(
    //         'counseling_session_id',
    //         $sessionId
    //     )->exists();

    //     $hasEvaluation = Evaluation::where(
    //         'counseling_session_id',
    //         $sessionId
    //     )->exists();

    //     return
    //         $hasScreening &&
    //         $hasAssessment &&
    //         $hasEvaluation;
    // }

    public function getTodayCounselingSessions(Request $request)
    {
        // ================= AMBIL USER LOGIN =================
        $user = $request->attributes->get('user');

        // Pastikan hanya konselor yang dapat mengakses endpoint ini
        if ($user->role !== 'konselor') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
                'data' => null,
            ], 403);
        }

        // ================= AMBIL SESI KONSELING HARI INI =================
        // Mengambil sesi konseling hari ini milik konselor login,
        // lalu memilih hanya record terakhir (id terbesar)
        // untuk setiap elderly_counselee_id.
        $sessions = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])
            ->where('counselor_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('elderly_counselee_id')
            ->map(function ($items) {
                // Karena sudah diorder desc, item pertama = id terbesar
                return $items->first();
            })
            ->values();

        // ================= FORMAT RESPONSE =================
        $data = $sessions->map(function ($session) {
            $elderlyCounselee = $session->elderlyCounselee;
            $counselee = $elderlyCounselee->counselee ?? null;

            return [
                'counseling_session_id' => $session->id,
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

                // ================= DATA SESI =================
                'service_mode' => $session->service_mode,
                'status' => $session->status,
                'created_at' => optional($session->created_at)
                    ->format('d-m-Y H:i'),

                // ================= STATUS PENYELESAIAN =================
                'is_completed' => $this->isCounselingSessionCompleted($session->id),
            ];
        });

        // ================= RESPONSE =================
        return response()->json([
            'success' => true,
            'message' => 'Daftar konseling hari ini berhasil diambil.',
            'total' => $data->count(),
            'data' => $data->values(),
        ]);
    }

    public function getCounselingStatistics(Request $request)
    {
        // ================= AMBIL USER LOGIN =================
        $user = $request->attributes->get('user');

        // Pastikan hanya konselor yang dapat mengakses endpoint ini
        if ($user->role !== 'konselor') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
                'data' => null,
            ], 403);
        }

        // ================= AMBIL SEMUA SESI KONSELING MILIK KONSELOR =================
        $sessions = CounselingSession::where('counselor_id', $user->id)->get();

        // ================= HITUNG STATUS =================
        $berjalan = 0;
        $selesai = 0;

        foreach ($sessions as $session) {
            if ($this->isCounselingSessionCompleted($session->id) && $session->status === 'completed') {
                $selesai++;
            } else {
                $berjalan++;
            }
        }

        // ================= HITUNG KONSELING HARI INI =================
        $today = CounselingSession::where('counselor_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // ================= HITUNG TOTAL KONSELI UNIK =================
        $totalCounselees = CounselingSession::where('counselor_id', $user->id)
            ->distinct('elderly_counselee_id')
            ->count('elderly_counselee_id');

        // ================= RESPONSE =================
        return response()->json([
            'success' => true,
            'message' => 'Statistik konseling berhasil diambil.',
            'data' => [
                'berjalan' => $berjalan,
                'selesai' => $selesai,
                'today' => $today,
                'total_sessions' => $sessions->count(),
                'total_counselees' => $totalCounselees,
            ],
        ]);
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
}
