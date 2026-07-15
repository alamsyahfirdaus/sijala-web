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

        $posters = EducationContent::where('category', 'poster')->get();

        return view('dashboard', compact(
            'videos',
            'posters'
        ));
    }

    public function home()
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
        // PRE TEST - PEMBERDAYAAN KELUARGA
        // =====================================================
        $empowermentPreTest = EmpowermentAssessment::whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('family_empowerment_assessments')
                ->groupBy('counseling_session_id');
        });

        // =====================================================
        // POST TEST - PEMBERDAYAAN KELUARGA
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
            // GRAFIK SKRINING RISIKO JATUH & PEMBERDAYAAN KELUARGA
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
}
