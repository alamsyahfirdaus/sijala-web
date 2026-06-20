<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\ElderlyCounselee;
use App\Models\EmpowermentAssessment;
use App\Models\Evaluation;
use App\Models\FallRiskScreening;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        //
    }

    public function show($report)
    {
        $reports = [

            'elderly' => [
                'title' => 'Lansia',
                'data' => $this->getElderlyReport(),
            ],

            'counselor' => [
                'title' => 'Konselor',
                'data' => $this->getCounselorReport(),
            ],

            'counseling' => [
                'title' => 'Konseling',
                'data' => $this->getCounselingReport(),
            ],

            'screening' => [
                'title' => 'Skrining',
                'data' => $this->getScreeningReport(),
            ],

            'evaluation' => [
                'title' => 'Evaluasi',
                'data' => $this->getEvaluationReport(),
            ],

        ];

        abort_unless(isset($reports[$report]), 404);

        return view('reports', [
            'title' => $reports[$report]['title'],
            'report' => $report,
            'data' => $reports[$report]['data'],
        ]);
    }

    private function getAvailableDates($model)
    {
        return $model::query()
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderByRaw('DATE(created_at) DESC')
            ->pluck('date');
    }

    private function getElderlyReport()
    {
        $query = ElderlyCounselee::with('counselee');

        if (request()->filled('start_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                request('start_date')
            );
        }

        if (request()->filled('end_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                request('end_date')
            );
        }

        $elderlies = $query
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id'                   => $item->id,
                    'elderly_name'         => $item->elderly_name,
                    'elderly_gender'       => $item->elderly_gender,
                    'elderly_age'          => $item->elderly_age,
                    'counselee_name'       => $item->counselee?->name,
                    'care_duration_months' => $item->care_duration_months,
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(ElderlyCounselee::class),
            'elderlies'      => $elderlies,
        ];
    }

    private function getCounselorReport()
    {
        $counselors = User::query()

            ->where('role', 'konselor')

            ->with([
                'puskesmas'
            ])

            ->withCount([
                'counselingSessions as total_counselings'
            ])

            ->when(
                request()->filled('start_date'),
                fn ($query) =>
                $query->whereDate(
                    'created_at',
                    '>=',
                    request('start_date')
                )
            )

            ->when(
                request()->filled('end_date'),
                fn ($query) =>
                $query->whereDate(
                    'created_at',
                    '<=',
                    request('end_date')
                )
            )

            ->latest()
            ->get()

            ->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'name'              => $item->name,
                    'phone'             => $item->phone ?? '-',
                    'puskesmas_name'    => $item->puskesmas?->name ?? '-',
                    'total_counselings' => $item->total_counselings,
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(User::class),
            'counselors'     => $counselors,
        ];
    }
    
    private function getCounselingReport()
    {
        $counselings = CounselingSession::query()

            ->with([
                'counselor',
                'elderlyCounselee'
            ])

            ->selectRaw('
                elderly_counselee_id,
                COUNT(*) as total_sessions,
                MAX(id) as last_session_id
            ')

            ->when(
                request()->filled('start_date'),
                fn ($query) =>
                $query->whereDate(
                    'created_at',
                    '>=',
                    request('start_date')
                )
            )

            ->when(
                request()->filled('end_date'),
                fn ($query) =>
                $query->whereDate(
                    'created_at',
                    '<=',
                    request('end_date')
                )
            )

            ->groupBy('elderly_counselee_id')

            ->get()

            ->map(function ($item) {

                $lastSession = CounselingSession::with([
                    'counselor',
                    'elderlyCounselee'
                ])->find($item->last_session_id);

                return [
                    'id'               => $lastSession->id,
                    'counselor_name'   => $lastSession->counselor?->name ?? '-',
                    'elderly_name'     => $lastSession->elderlyCounselee?->elderly_name ?? '-',
                    'elderly_gender'   => $lastSession->elderlyCounselee?->elderly_gender ?? '-',
                    'elderly_age'      => $lastSession->elderlyCounselee?->elderly_age ?? '-',
                    'total_sessions'   => $item->total_sessions,
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(CounselingSession::class),
            'counselings'    => $counselings,
        ];
    }

    private function getScreeningReport()
    {
        $availableDates = collect()
            ->merge($this->getAvailableDates(FallRiskScreening::class))
            ->merge($this->getAvailableDates(EmpowermentAssessment::class))
            ->unique()
            ->sortDesc()
            ->values();

        $counselingSessionIds = collect()

            ->merge(
                FallRiskScreening::query()
                    ->pluck('counseling_session_id')
            )

            ->merge(
                EmpowermentAssessment::query()
                    ->pluck('counseling_session_id')
            )

            ->unique()
            ->values();

        $screenings = CounselingSession::query()

        ->with([
            'elderlyCounselee',
            'fallRiskScreening',
            'empowermentAssessment'
        ])

        ->whereIn(
            'id',
            $counselingSessionIds
        )

        ->when(
            request()->filled('start_date'),
            fn ($query) =>
            $query->whereDate(
                'created_at',
                '>=',
                request('start_date')
            )
        )

        ->when(
            request()->filled('end_date'),
            fn ($query) =>
            $query->whereDate(
                'created_at',
                '<=',
                request('end_date')
            )
        )

        ->latest()

        ->get()

        ->map(function ($item) {

            return [
                'id' => $item->id,

                'elderly_name' =>
                    $item->elderlyCounselee?->elderly_name ?? '-',

                'gender' =>
                    $item->elderlyCounselee?->elderly_gender ?? '-',

                'age' =>
                    $item->elderlyCounselee?->elderly_age ?? '-',

                'fall_risk_score' =>
                    $item->fallRiskScreening?->total_score ?? '-',
                
                'fall_risk_category' =>
                    $item->fallRiskScreening?->risk_level ?? '-',

                'empowerment_score' =>
                    $item->empowermentAssessment?->total_score ?? '-',
                
                'empowerment_category' =>
                    $item->empowermentAssessment?->empowerment_level ?? '-',

                'screening_date' =>
                    $item->created_at,
            ];
        });

        return [
            'availableDates' => $availableDates,
            'screenings'     => $screenings,
        ];
    }

    private function getEvaluationReport()
    {
        return [
            'availableDates' => $this->getAvailableDates(Evaluation::class),
            'evaluations' => Evaluation::all(),
        ];
    }
}
