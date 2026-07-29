<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CounselingResumeOption;
use App\Models\CounselingSession;
use App\Models\EmpowermentAssessment;
use App\Models\EmpowermentQuestion;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\FallRiskScreening;
use App\Models\FallRiskQuestion;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class CounselingController extends Controller
{
    public function index()
    {
        $title = 'Konseling';

        $counselingSessions = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor',
            ])
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('counseling_sessions')
                    ->groupBy('elderly_counselee_id');
            })
            ->orderByDesc('created_at')
            ->get();

        foreach ($counselingSessions as $session) {

            $sessionCount = CounselingSession::where(
                'elderly_counselee_id',
                $session->elderly_counselee_id
            )->count();

            $session->session_count = $sessionCount;

            // ==========================================
            // Menentukan Status Konseling
            // ==========================================

            // Cek apakah terdapat sesi evaluasi (sesi kedua terakhir)
            $isLatest = CounselingSession::where(
                    'elderly_counselee_id',
                    $session->elderly_counselee_id
                )
                ->where('is_latest', 1)
                ->first();

            // Cek apakah sesi terakhir telah selesai
            $isCompleted = CounselingSession::where(
                    'elderly_counselee_id',
                    $session->elderly_counselee_id
                )
                ->where('status', 'completed')
                ->exists();

            // Status konseling
            $session->status_konseling = ($isLatest && $isCompleted)
                ? 'Selesai'
                : 'Berjalan';
        }

        return view('counselings', compact('title', 'counselingSessions'));
    }

    public function session($id)
    {
        try {

            // Dekripsi ID sesi konseling dari URL
            $id = Crypt::decrypt($id);

            $title = 'Konseling';

            // Ambil data sesi konseling
            $counseling = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor.puskesmas',
            ])->findOrFail($id);

            // Seluruh riwayat sesi konseling
            $sessions = CounselingSession::where(
                'elderly_counselee_id',
                $counseling->elderly_counselee_id
            )
                ->orderBy('created_at')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | PRE TEST = SESI PERTAMA
            |--------------------------------------------------------------------------
            */

            $firstSession = $sessions->first();

            /*
            |--------------------------------------------------------------------------
            | POST TEST = SESI TERAKHIR
            |--------------------------------------------------------------------------
            */

            $lastSession = $sessions->last();

            /*
            |--------------------------------------------------------------------------
            | HASIL SKRINING
            |--------------------------------------------------------------------------
            */

            $screening = null;

            if ($firstSession && $lastSession) {

                $preTest = [
                    'session_number' => 1,
                    'session_date' => $firstSession->created_at,
                    'fall_risk' => FallRiskScreening::where(
                        'counseling_session_id',
                        $firstSession->id
                    )->first(),
                    'empowerment' => EmpowermentAssessment::where(
                        'counseling_session_id',
                        $firstSession->id
                    )->first(),
                ];

                $postTest = null;

                if ($sessions->count() > 1) {
                    $postTest = [
                        'session_number' => $sessions->count(),
                        'session_date' => $lastSession->created_at,
                        'fall_risk' => FallRiskScreening::where(
                            'counseling_session_id',
                            $lastSession->id
                        )->latest('id')->first(),
                        'empowerment' => EmpowermentAssessment::where(
                            'counseling_session_id',
                            $lastSession->id
                        )->latest('id')->first(),
                    ];
                }

                $screening = [
                    'pre_test' => $preTest,
                    'post_test' => $postTest,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | TINDAK LANJUT
            |--------------------------------------------------------------------------
            */

            $followUps = $sessions
                ->filter(fn ($session) => ! empty($session->note))
                ->values()
                ->map(function ($session, $index) {
                    return [
                        'session_number' => $index + 1,
                        'session_date' => $session->created_at->format('d-m-Y'),
                        'follow_up' => $session->note,
                    ];
                });

            /*
            |--------------------------------------------------------------------------
            | HASIL EVALUASI
            |--------------------------------------------------------------------------
            */

            $evaluations = Evaluation::whereIn(
                'counseling_session_id',
                $sessions->pluck('id')
            )
                ->with('topic')
                ->orderBy('created_at')
                ->get()
                ->groupBy('counseling_session_id');

            /*
            |--------------------------------------------------------------------------
            | NOMOR SESI
            |--------------------------------------------------------------------------
            */

            $sessionNumbers = $sessions
                ->pluck('id')
                ->flip()
                ->map(fn ($index) => $index + 1);

            /*
            |--------------------------------------------------------------------------
            | RESUME KONSELOR
            |--------------------------------------------------------------------------
            */

            $counselingResumes = $this->getCounselingResumes($sessions);

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN HALAMAN
            |--------------------------------------------------------------------------
            */


            // echo json_encode($evaluations);

            return view('counseling_session', compact(
                'title',
                'counseling',
                'sessions',
                'screening',
                'evaluations',
                'sessionNumbers',
                'counselingResumes',
                'followUps'
            ));

        } catch (DecryptException $e) {

            abort(404);

        }
    }

    private function getCounselingResumes($sessions)
    {
        $result = [];

        foreach ($sessions as $index => $session) {

            $resumeIds = $session->resume ?? [];

            $resumeOptions = CounselingResumeOption::with('category')
                ->whereIn('id', $resumeIds)
                ->orderBy('sort_order')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'category' => $item->category->title ?? '-',
                        'title' => $item->title,
                    ];
                })
                ->groupBy('category')
                ->map(function ($items) {
                    return $items->map(function ($item) {
                        return [
                            'id' => $item['id'],
                            'title' => $item['title'],
                        ];
                    })->values();
                });

            $result[] = [
                'session_number' => $index + 1,
                'session_date' => $session->created_at->format('d-m-Y'),
                'resume_options' => $resumeOptions,
            ];
        }

        return collect($result);
    }

    public function updateScore(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:fall-risk,empowerment,evaluation',
            'id'    => 'required|integer',
            'score' => 'required|numeric|min:0',
        ]);

        try {

            switch ($request->type) {

                /*
                |--------------------------------------------------------------------------
                | SKRINING RISIKO JATUH
                |--------------------------------------------------------------------------
                */
                case 'fall-risk':

                    $data = FallRiskScreening::findOrFail($request->id);

                    // Skor maksimum skrining risiko jatuh
                    $maxScore = FallRiskQuestion::sum(DB::raw('GREATEST(score_yes, score_no)'));

                    // Batasi nilai 0 - maksimum
                    $totalScore = min(
                        max((int) $request->score, 0),
                        $maxScore
                    );

                    if ($totalScore <= 3) {

                        $riskLevel = 'Rendah';

                        $interpretation =
                            'Risiko jatuh minimal.';

                    } elseif ($totalScore <= 7) {

                        $riskLevel = 'Sedang';

                        $interpretation =
                            'Perlu edukasi dan pemantauan.';

                    } else {

                        $riskLevel = 'Tinggi';

                        $interpretation =
                            'Perlu asesmen lanjutan dan intervensi.';
                    }

                    $data->update([
                        'total_score'    => $totalScore,
                        'risk_level'     => $riskLevel,
                        'interpretation' => $interpretation,
                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | KEMANDIRIAN KESEHATAN KELUARGA
                |--------------------------------------------------------------------------
                */
                case 'empowerment':

                    $data = EmpowermentAssessment::findOrFail($request->id);

                    // Maksimum = jumlah pertanyaan × 4
                    $questionCount = EmpowermentQuestion::whereNotNull('dimension_id')
                        ->count();

                    $maxScore = $questionCount * 4;

                    // Batasi nilai 0 - maksimum
                    $totalScore = min(
                        max((int) $request->score, 0),
                        $maxScore
                    );

                    if ($totalScore <= 70) {

                        $level = 'Rendah';

                        $interpretation =
                            'Tingkat kemandirian kesehatan keluarga tergolong rendah. '
                            .'Keluarga masih memerlukan pendampingan, edukasi, serta peningkatan '
                            .'kemampuan dalam mengenali masalah kesehatan, mengambil keputusan, '
                            .'merawat anggota keluarga, memodifikasi lingkungan, dan memanfaatkan '
                            .'fasilitas pelayanan kesehatan.';

                    } elseif ($totalScore <= 105) {

                        $level = 'Sedang';

                        $interpretation =
                            'Tingkat kemandirian kesehatan keluarga tergolong sedang. '
                            .'Keluarga telah memiliki kemampuan dalam mendukung perawatan '
                            .'kesehatan anggota keluarga, namun masih diperlukan penguatan '
                            .'melalui edukasi, motivasi, dan pendampingan secara berkelanjutan.';

                    } else {

                        $level = 'Tinggi';

                        $interpretation =
                            'Tingkat kemandirian kesehatan keluarga tergolong tinggi. '
                            .'Keluarga memiliki kemampuan yang baik dalam mengenali masalah '
                            .'kesehatan, mengambil keputusan, memberikan perawatan, '
                            .'menciptakan lingkungan yang aman, serta memanfaatkan fasilitas '
                            .'pelayanan kesehatan secara optimal.';
                    }

                    $data->update([
                        'total_score'       => $totalScore,
                        'empowerment_level' => $level,
                        'interpretation'    => $interpretation,
                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | EVALUASI PEMBELAJARAN
                |--------------------------------------------------------------------------
                */
                case 'evaluation':

                    $data = Evaluation::with('topic')
                        ->findOrFail($request->id);

                    /*
                    |--------------------------------------------------------------------------
                    | HITUNG TOTAL BOBOT MAKSIMUM
                    |--------------------------------------------------------------------------
                    */
                    $maxScore = EvaluationQuestion::where(
                            'evaluation_topic_id',
                            $data->evaluation_topic_id
                        )
                        ->where('is_active', true)
                        ->sum('score');

                    // Batasi nilai 0 - maksimum
                    $totalScore = min(
                        max((int) $request->score, 0),
                        $maxScore
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | HITUNG PERSENTASE
                    |--------------------------------------------------------------------------
                    */
                    $percentage = $maxScore > 0
                        ? round(($totalScore / $maxScore) * 100, 2)
                        : 0;

                    /*
                    |--------------------------------------------------------------------------
                    | KATEGORI
                    |--------------------------------------------------------------------------
                    */
                    if ($percentage >= 76) {

                        $category = 'Baik';

                    } elseif ($percentage >= 56) {

                        $category = 'Cukup';

                    } else {

                        $category = 'Kurang';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | INTERPRETASI
                    |--------------------------------------------------------------------------
                    */
                    $topicName = $data->topic->topic ?? 'materi';

                    if ($category === 'Baik') {

                        $interpretation =
                            'Peserta memiliki pemahaman yang baik terhadap materi "'
                            .$topicName.
                            '". Sebagian besar pertanyaan dapat dijawab dengan benar. '
                            .'Disarankan untuk mempertahankan pemahaman yang sudah dimiliki '
                            .'dan terus menerapkan materi yang telah dipelajari.';

                    } elseif ($category === 'Cukup') {

                        $interpretation =
                            'Peserta memiliki pemahaman yang cukup terhadap materi "'
                            .$topicName.
                            '". Masih terdapat beberapa konsep yang perlu diperkuat. '
                            .'Disarankan untuk mengulang kembali materi dan melakukan '
                            .'pendampingan lanjutan pada bagian yang belum dipahami.';

                    } else {

                        $interpretation =
                            'Peserta masih mengalami kesulitan dalam memahami materi "'
                            .$topicName.
                            '". Diperlukan edukasi ulang, pendampingan, serta penguatan '
                            .'materi agar tingkat pemahaman dapat meningkat.';
                    }

                    $data->update([
                        'total_score'    => $totalScore,
                        'percentage'     => $percentage,
                        'category'       => $category,
                        'interpretation' => $interpretation,
                    ]);

                    break;
            }

            return back()->with(
                'success',
                'Skor berhasil diperbarui.'
            );

        } catch (ModelNotFoundException $e) {

            return back()->with(
                'error',
                'Data yang akan diperbarui tidak ditemukan.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Terjadi kesalahan saat memperbarui skor.'
            );
        }
    }

    public function destroy($id)
    {
        try {
            $id = decrypt($id);

            DB::transaction(function () use ($id) {
                $session = CounselingSession::findOrFail($id);

                CounselingSession::where('elderly_counselee_id', $session->elderly_counselee_id)
                    ->where('counselor_id', $session->counselor_id)
                    ->delete();
            });

            return redirect()
                ->route('counselings')
                ->with('success', 'Data konseling berhasil dihapus.');
        } catch (DecryptException $e) {
            return redirect()
                ->route('counselings')
                ->with('error', 'Data tidak ditemukan.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('counselings')
                ->with('error', 'Data gagal dihapus.');
        }
    }
}
