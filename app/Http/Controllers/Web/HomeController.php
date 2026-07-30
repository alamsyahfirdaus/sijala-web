<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\EducationContent;
use App\Models\ElderlyCounselee;
use App\Models\EmpowermentAssessment;
use App\Models\FallRiskScreening;
use App\Models\Evaluation;
use App\Models\Puskesmas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $videos = Cache::remember(
            'youtube_videos',
            now()->addHours(1),
            function () {

                $response = Http::get(
                    'https://www.googleapis.com/youtube/v3/search',
                    [
                        'part' => 'snippet',
                        'channelId' => env('YOUTUBE_CHANNEL_ID'),
                        'maxResults' => 10,
                        'order' => 'date',
                        'type' => 'video',
                        'key' => env('YOUTUBE_API_KEY'),
                    ]
                );

                return $response->json()['items'] ?? [];
            }
        );

        if (empty($videos)) {
            $videos = EducationContent::where('category', 'video')
                ->get();
        }

        $posters = EducationContent::where('category', 'poster')->get();

        return view('dashboard', compact(
            'videos',
            'posters'
        ));
    }

    public function home1()
    {
        // =====================================================
        // PRE TEST - RISIKO JATUH
        // =====================================================
        $fallRiskPreTest = FallRiskScreening::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('elderly_fall_risk_screenings')
                ->groupBy('counseling_session_id');
        });

        // =====================================================
        // POST TEST - RISIKO JATUH
        // =====================================================
        $fallRiskPostTest = FallRiskScreening::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('elderly_fall_risk_screenings')
                ->groupBy('counseling_session_id');
        });

        // =====================================================
        // PRE TEST - KEMANDIRIAN KESEHATAN KELUARGA
        // =====================================================
        $empowermentPreTest = EmpowermentAssessment::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('family_empowerment_assessments')
                ->groupBy('counseling_session_id');
        });

        // =====================================================
        // POST TEST - KEMANDIRIAN KESEHATAN KELUARGA
        // =====================================================
        $empowermentPostTest = EmpowermentAssessment::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('family_empowerment_assessments')
                ->groupBy('counseling_session_id');
        });

        // =====================================================
        // KATEGORI GRAFIK
        // =====================================================
        $testCategories = [
            'Pre-Test',
            'Post-Test',
        ];

        // =====================================================
        // DATA GRAFIK
        // =====================================================
        $fallRiskChart = [
            $fallRiskPreTest->count(),
            $fallRiskPostTest->count(),
        ];

        $empowermentChart = [
            $empowermentPreTest->count(),
            $empowermentPostTest->count(),
        ];

        // =====================================================
        // HASIL EVALUASI PER TOPIK
        // =====================================================
        $evaluationData = Evaluation::query()
            ->join(
                'evaluation_topics',
                'evaluation_topics.id',
                '=',
                'evaluations.evaluation_topic_id'
            )
            ->selectRaw("
                evaluation_topics.topic,
                AVG(evaluations.total_score) AS average_score
            ")
            ->groupBy(
                'evaluation_topics.id',
                'evaluation_topics.topic'
            )
            ->orderBy('evaluation_topics.topic')
            ->get();

        $evaluationCategories = $evaluationData
            ->pluck('topic')
            ->toArray();

        $evaluationChart = $evaluationData
            ->pluck('average_score')
            ->map(fn ($score) => round($score, 2))
            ->toArray();

        // =====================================================
        // RETURN VIEW
        // =====================================================
        return view('home', [

            // =================================================
            // INFO BOX
            // =================================================
            'totalKonselor'  => User::where('role', 'konselor')->count(),
            'totalKonseli'   => User::where('role', 'konseli')->count(),
            'totalLansia'    => ElderlyCounselee::count(),
            'totalPuskesmas' => Puskesmas::count(),

            // =================================================
            // GRAFIK SKRINING RISIKO JATUH & KEMANDIRIAN KESEHATAN KELUARGA
            // =================================================
            'testCategories'   => $testCategories,
            'fallRiskChart'    => $fallRiskChart,
            'empowermentChart' => $empowermentChart,

            // =================================================
            // GRAFIK HASIL EVALUASI
            // =================================================
            'evaluationCategories' => $evaluationCategories,
            'evaluationChart'      => $evaluationChart,
        ]);
    }

    public function home()
    {
        // =====================================================
        // RATA-RATA SKOR SKRINING RISIKO JATUH
        // =====================================================

        // Pre-Test (skrining pertama pada setiap sesi konseling)
        $fallRiskPreTestAverage = FallRiskScreening::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('elderly_fall_risk_screenings')
                ->groupBy('counseling_session_id');
        })->avg('total_score') ?? 0;

        // Post-Test (skrining terakhir pada sesi yang memiliki lebih dari satu skrining)
        $fallRiskPostTestAverage = FallRiskScreening::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('elderly_fall_risk_screenings')
                ->groupBy('counseling_session_id')
                ->havingRaw('COUNT(*) > 1');
        })->avg('total_score') ?? 0;

        // =====================================================
        // RATA-RATA SKOR PEMBERDAYAAN KESEHATAN KELUARGA
        // =====================================================

        // Pre-Test (penilaian pertama pada setiap sesi konseling)
        $empowermentPreTestAverage = EmpowermentAssessment::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('family_empowerment_assessments')
                ->groupBy('counseling_session_id');
        })->avg('total_score') ?? 0;

        // Post-Test (penilaian terakhir pada sesi yang memiliki lebih dari satu penilaian)
        $empowermentPostTestAverage = EmpowermentAssessment::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('family_empowerment_assessments')
                ->groupBy('counseling_session_id')
                ->havingRaw('COUNT(*) > 1');
        })->avg('total_score') ?? 0;

        // =====================================================
        // KATEGORI GRAFIK
        // =====================================================
        $testCategories = [
            'Pre-Test',
            'Post-Test',
        ];

        // =====================================================
        // DATA GRAFIK
        // =====================================================
        $fallRiskChart = [
            round($fallRiskPreTestAverage, 2),
            round($fallRiskPostTestAverage, 2),
        ];

        $empowermentChart = [
            round($empowermentPreTestAverage, 2),
            round($empowermentPostTestAverage, 2),
        ];

        // =====================================================
        // HASIL EVALUASI PER TOPIK
        // =====================================================
        $evaluationData = Evaluation::query()
            ->join(
                'evaluation_topics',
                'evaluation_topics.id',
                '=',
                'evaluations.evaluation_topic_id'
            )
            ->selectRaw("
                evaluation_topics.topic,
                AVG(evaluations.total_score) AS average_score
            ")
            ->groupBy(
                'evaluation_topics.id',
                'evaluation_topics.topic'
            )
            ->orderBy('evaluation_topics.topic')
            ->get();

        $evaluationCategories = $evaluationData
            ->pluck('topic')
            ->toArray();

        $evaluationChart = $evaluationData
            ->pluck('average_score')
            ->map(fn ($score) => round($score, 2))
            ->toArray();

        // =====================================================
        // RETURN VIEW
        // =====================================================
        return view('home', [

            // =================================================
            // INFO BOX
            // =================================================
            'totalKonselor'  => User::where('role', 'konselor')->count(),
            'totalKonseli'   => User::where('role', 'konseli')->count(),
            'totalLansia'    => ElderlyCounselee::count(),
            'totalPuskesmas' => Puskesmas::count(),

            // =================================================
            // GRAFIK RATA-RATA SKOR SKRINING
            // =================================================
            'testCategories'   => $testCategories,
            'fallRiskChart'    => $fallRiskChart,
            'empowermentChart' => $empowermentChart,

            // =================================================
            // GRAFIK HASIL EVALUASI
            // =================================================
            'evaluationCategories' => $evaluationCategories,
            'evaluationChart'      => $evaluationChart,
        ]);
    }

    public function home3()
    {
        $response = Http::withHeaders([
            'Authorization' => 'xyPjYLXVq1cv92cLdNEC',
        ])->post('https://api.fonnte.com/send', [
            'target'  => '6282215161998',
            'message' => 'Test WhatsApp dari SIJALA berhasil.',
        ]);

        return response()->json([
            'http_status' => $response->status(),
            'success'     => $response->successful(),
            'response'    => $response->json(),
            'raw'         => $response->body(),
        ]);
    }
}
