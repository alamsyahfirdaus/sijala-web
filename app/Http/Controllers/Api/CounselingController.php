<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\ElderlyCounselee;
use App\Models\EmpowermentAssessment;
use App\Models\FallRiskScreening;
use App\Models\EducationContent;
use App\Models\Evaluation;
use App\Models\CounselingResumeOption;
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
                'note' => $session->note,
                'is_latest' => $session->is_latest ? true : false,
                'created_at' => optional($session->created_at)
                    ->format('d-m-Y H:i'),
                'updated_at' => optional($session->updated_at)
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
                            'updated_at' => optional($session->updated_at)
                                ->format('d-m-Y H:i'),
                            'is_latest' => $session->is_latest ? true : false,
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
                    
                    'is_latest' =>
                        $session->is_latest ? true : false,

                    'created_at' =>
                        optional(
                            $session->created_at
                        )->format('d-m-Y H:i'),
                    
                    'updated_at' =>
                        optional(
                            $session->updated_at
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

    public function getCounselingResumeOptions()
    {
        $categories = CounselingResumeOption::with([
                'items:id,category_id,title'
            ])
            ->whereNull('category_id')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get([
                'id',
                'title',
            ]);

        $data = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'title' => $category->title,
                'items' => $category->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar pilihan resume konseling berhasil diambil.',
            'data' => $data->values(),
        ]);
    }

    public function completeCounselingSession(Request $request, $counselingSessionId)
    {
        // =====================================================
        // USER LOGIN
        // =====================================================
        $user = $request->attributes->get('user');

        // =====================================================
        // VALIDASI ROLE
        // =====================================================
        if ($user->role !== 'konselor') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
                'data' => null,
            ], 403);
        }

        // =====================================================
        // VALIDASI INPUT
        // =====================================================
        $validator = Validator::make(
            $request->all(),
            [
                'resume' => 'required|array|min:1',
                'resume.*' => 'integer|distinct|exists:counseling_resume_options,id',
                'note' => 'required|string|max:5000',
                'needs_follow_up' => 'required|boolean',
            ],
            [
                'resume.required' => 'Resume konseling wajib dipilih.',
                'resume.array' => 'Format resume konseling tidak valid.',
                'resume.min' => 'Pilih minimal satu resume konseling.',
                'resume.*.distinct' => 'Pilihan resume tidak boleh duplikat.',
                'resume.*.exists' => 'Pilihan resume konseling tidak valid.',

                'note.required' => 'Tindak lanjut konseling wajib diisi.',
                'note.max' => 'Tindak lanjut maksimal 5000 karakter.',

                'needs_follow_up.required' => 'Status sesi lanjutan wajib dipilih.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        DB::beginTransaction();

        try {

            // =================================================
            // AMBIL USER INPUT
            // =================================================
            $needsFollowUp = $request->boolean('needs_follow_up');

            // =================================================
            // AMBIL SESI
            // =================================================
            $session = CounselingSession::where(
                    'id',
                    $counselingSessionId
                )
                ->where(
                    'counselor_id',
                    $user->id
                )
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi konseling tidak ditemukan.',
                    'data' => null,
                ], 404);
            }

            // =================================================
            // VALIDASI ITEM RESUME
            // Pastikan yang dipilih adalah ITEM,
            // bukan kategori.
            // =================================================
            $invalidResume = CounselingResumeOption::whereIn(
                    'id',
                    $request->resume
                )
                ->whereNull('category_id')
                ->exists();

            if ($invalidResume) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resume yang dipilih tidak valid.',
                    'data' => null,
                ], 422);
            }

            // =================================================
            // HITUNG JUMLAH SESI KONSELING
            // =================================================
            $totalSessions = CounselingSession::where(
                    'elderly_counselee_id',
                    $session->elderly_counselee_id
                )
                ->count();

            // =================================================
            // APAKAH SESI PERTAMA ATAU TERAKHIR
            // =================================================
            $mustValidateCompletion =
                $totalSessions == 1 ||
                $session->is_latest == 1;

            // =================================================
            // VALIDASI KELENGKAPAN
            // =================================================
            if (
                $mustValidateCompletion &&
                !$this->isCounselingSessionCompleted($session->id)
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Skrining Risiko Jatuh dan Asesmen Pemberdayaan harus diselesaikan terlebih dahulu.',
                    'data' => null,
                ], 422);
            }

            // =================================================
            // FORMAT DATA RESUME
            // =================================================
            $resume = collect($request->resume)
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->sort()
                ->values()
                ->toArray();

            // =================================================
            // UPDATE SESI
            // =================================================
            $session->resume = $resume;
            $session->note = trim($request->note);
            $session->status = 'completed';
            $session->is_latest = 0;
            $session->updated_at = now();

            $session->save();

            // =================================================
            // BUAT SESI LANJUTAN
            // =================================================
            $newSession = null;

            if ($needsFollowUp) {

                $newSession = CounselingSession::create([
                    'elderly_counselee_id' =>
                        $session->elderly_counselee_id,

                    'counselor_id' =>
                        $session->counselor_id,

                    'service_mode' =>
                        $session->service_mode,

                    'status' =>
                        'ongoing',

                    'is_latest' =>
                        1,
                ]);
            }

            DB::commit();

            // =================================================
            // RESPONSE
            // =================================================
            return response()->json([
                'success' => true,
                'message' => 'Resume konseling berhasil disimpan dan sesi konseling telah diselesaikan.',
                'data' => [
                    'counseling_session_id' =>
                        $session->id,

                    'resume' =>
                        $session->resume,

                    'status' =>
                        $session->status,

                    'note' =>
                        $session->note,

                    'needs_follow_up' =>
                        $needsFollowUp,

                    'next_counseling_session_id' =>
                        optional($newSession)->id,
                ],
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
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
