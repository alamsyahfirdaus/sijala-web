<?php

namespace App\Http\Controllers\Web;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\ElderlyCounselee;
use App\Models\EmpowermentAssessment;
use App\Models\Evaluation;
use App\Models\FallRiskScreening;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        //
    }

    public function show($report)
    {
        $reports = [

            'counselee' => [
                'title' => 'Konseli',
                'data' => $this->getCounseleeReport(),
            ],

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

    private function getCounseleeReport()
    {
        $query = ElderlyCounselee::with([
            'counselee.puskesmas.village.district.regency',
        ])
            ->withCount('counselingSessions');

        if (request()->filled('start_date')) {
            $query->whereDate('created_at', '>=', request('start_date'));
        }

        if (request()->filled('end_date')) {
            $query->whereDate('created_at', '<=', request('end_date'));
        }

        $counselees = $query
            ->latest()
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'name' => $item->counselee?->name ?? '-',
                    'gender' => $item->counselee?->gender == 'L' ? 'Laki-Laki' : ($item->counselee?->gender == 'P' ? 'Perempuan' : '-'),
                    'phone' => $item->counselee?->phone ?? '-',
                    'age' => $item->counselee?->birth_date
                        ? Carbon::parse($item->counselee->birth_date)->age.' Tahun'
                        : '-',
                    'occupation' => $item->occupation ?? '-',
                    'education' => $item->education ?? '-',
                    'puskesmas' => $item->counselee?->puskesmas
                        ? collect([
                            $item->counselee->puskesmas->name,
                            $item->counselee->puskesmas->village?->name,
                            $item->counselee->puskesmas->village?->district?->name,
                            $item->counselee->puskesmas->village?->district?->regency?->name,
                        ])->filter()->implode(', ')
                        : '-',

                    // Statistik
                    'jml_counselings' => $item->counseling_sessions_count > 0 ? $item->counseling_sessions_count.' Sesi' : '-',

                    'created_at' => $item->created_at->format('d-m-Y'),
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(User::class),
            'counselees' => $counselees,
        ];
    }

    private function getElderlyReport()
    {
        $query = ElderlyCounselee::with([
            'counselee.puskesmas.village.district.regency',
        ]);

        if (request()->filled('start_date')) {
            $query->whereDate('created_at', '>=', request('start_date'));
        }

        if (request()->filled('end_date')) {
            $query->whereDate('created_at', '<=', request('end_date'));
        }

        $elderlies = $query
            ->latest()
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,

                    // Identitas Lansia
                    'elderly_name' => $item->elderly_name,
                    'elderly_gender' => $item->elderly_gender == 'L' ? 'Laki-Laki' : ($item->elderly_gender == 'P' ? 'Perempuan' : '-'),
                    'elderly_age' => $item->elderly_age,

                    // Pendamping
                    'counselee_name' => $item->counselee?->name ?? '-',
                    'care_duration_months' => $item->care_duration_months
                        ? $item->care_duration_months.' Bulan'
                        : '-',

                    // Riwayat Jatuh
                    'has_fallen' => $item->has_fallen == 1 ? 'Pernah' : ($item->has_fallen == 0 ? 'Belum Pernah' : '-'),

                    // Puskesmas
                    'puskesmas' => $item->counselee?->puskesmas
                        ? collect([
                            $item->counselee->puskesmas->name,
                            $item->counselee->puskesmas->village?->name,
                            $item->counselee->puskesmas->village?->district?->name,
                            $item->counselee->puskesmas->village?->district?->regency?->name,
                        ])->filter()->implode(', ')
                        : '-',

                    // Tanggal Pendaftaran
                    'created_at' => $item->created_at->format('d-m-Y'),
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(ElderlyCounselee::class),
            'elderlies' => $elderlies,
        ];
    }

    private function getCounselorReport()
    {
        $query = User::query()
            ->where('role', 'konselor')
            ->with([
                'puskesmas.village.district.regency',
            ])
            ->withCount([
                'counselingSessions as total_elderlies' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT elderly_counselee_id)'));
                },
            ]);

        if (request()->filled('start_date')) {
            $query->whereDate('created_at', '>=', request('start_date'));
        }

        if (request()->filled('end_date')) {
            $query->whereDate('created_at', '<=', request('end_date'));
        }

        $counselors = $query
            ->latest()
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'gender' => $item->gender == 'L' ? 'Laki-Laki' : ($item->gender == 'P' ? 'Perempuan' : '-'),
                    'phone' => $item->phone ?? '-',

                    'puskesmas' => $item->puskesmas
                        ? collect([
                            $item->puskesmas->name,
                            $item->puskesmas->village?->name,
                            $item->puskesmas->village?->district?->name,
                            $item->puskesmas->village?->district?->regency?->name,
                        ])->filter()->implode(', ')
                        : '-',

                    'total_elderlies' => $item->total_elderlies > 0 ? $item->total_elderlies.' Lansia' : '-',

                    'created_at' => $item->created_at->format('d-m-Y'),
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(User::class),
            'counselors' => $counselors,
        ];
    }

    private function getCounselingReport()
    {
        $query = CounselingSession::query()

            ->when(
                request()->filled('start_date'),
                fn ($query) => $query->whereDate(
                    'created_at',
                    '>=',
                    request('start_date')
                )
            )

            ->when(
                request()->filled('end_date'),
                fn ($query) => $query->whereDate(
                    'created_at',
                    '<=',
                    request('end_date')
                )
            )

            ->selectRaw('
                elderly_counselee_id,
                COUNT(*) AS total_sessions,
                MIN(created_at) AS first_counseling_date,
                MAX(created_at) AS last_counseling_date,
                MAX(id) AS last_session_id
            ')

            ->groupBy('elderly_counselee_id');

        $counselings = CounselingSession::with([
            'counselor.puskesmas.village.district.regency',
            'elderlyCounselee.counselee',
        ])
            ->whereIn('id', $query->pluck('last_session_id'))
            ->get()
            ->keyBy('id');

        $reports = $query
            ->get()
            ->map(function ($item) use ($counselings) {

                $session = $counselings[$item->last_session_id];

                return [

                    'id' => $session->id,

                    // Konseli
                    'counselee_name' => $session->elderlyCounselee?->counselee?->name ?? '-',

                    // Lansia
                    'elderly_name' => $session->elderlyCounselee?->elderly_name ?? '-',

                    'elderly_gender' => match ($session->elderlyCounselee?->elderly_gender) {
                        'L' => 'Laki-Laki',
                        'P' => 'Perempuan',
                        default => '-',
                    },

                    'elderly_age' => $session->elderlyCounselee?->elderly_age
                        ? $session->elderlyCounselee->elderly_age.' Tahun'
                        : '-',

                    // Konselor
                    'counselor_name' => $session->counselor?->name ?? '-',

                    // Puskesmas
                    'puskesmas' => $session->counselor?->puskesmas
                        ? collect([
                            $session->counselor->puskesmas->name,
                            $session->counselor->puskesmas->village?->name,
                            $session->counselor->puskesmas->village?->district?->name,
                            $session->counselor->puskesmas->village?->district?->regency?->name,
                        ])->filter()->implode(', ')
                        : '-',

                    'total_sessions' => $item->total_sessions > 0
                            ? $item->total_sessions.' Sesi'
                            : '-',

                    'first_counseling_date' => Carbon::parse(
                        $item->first_counseling_date
                    )->translatedFormat('d F Y'),

                    'last_counseling_date' => Carbon::parse(
                        $item->last_counseling_date
                    )->translatedFormat('d F Y'),
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(CounselingSession::class),
            'counselings' => $reports,
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

        $query = CounselingSession::query()

            ->where(function ($query) {
                $query->whereHas('fallRiskScreening')
                    ->orWhereHas('empowermentAssessment');
            })

            ->when(
                request()->filled('start_date'),
                fn ($query) => $query->whereDate(
                    'created_at',
                    '>=',
                    request('start_date')
                )
            )

            ->when(
                request()->filled('end_date'),
                fn ($query) => $query->whereDate(
                    'created_at',
                    '<=',
                    request('end_date')
                )
            )

            ->selectRaw('
                elderly_counselee_id,
                MIN(id) as first_session_id,
                MAX(id) as last_session_id
            ')

            ->groupBy('elderly_counselee_id');

        $sessionIds = $query->get()
            ->flatMap(fn ($item) => [
                $item->first_session_id,
                $item->last_session_id,
            ])
            ->unique();

        $sessions = CounselingSession::with([
            'counselor.puskesmas.village.district.regency',
            'elderlyCounselee.counselee',
            'fallRiskScreening',
            'empowermentAssessment',
        ])
            ->whereIn('id', $sessionIds)
            ->get()
            ->keyBy('id');

        $screenings = $query
            ->get()
            ->map(function ($item) use ($sessions) {

                $firstSession = $sessions[$item->first_session_id];
                $lastSession = $sessions[$item->last_session_id];

                // Risiko Jatuh
                $fallRiskPre = $firstSession->fallRiskScreening?->total_score;
                $fallRiskPost = $lastSession->fallRiskScreening?->total_score;

                // Pemberdayaan
                $empowermentPre = $firstSession->empowermentAssessment?->total_score;
                $empowermentPost = $lastSession->empowermentAssessment?->total_score;

                return [
                    'id' => $lastSession->id,
                    // Konseli
                    'counselee_name' => $lastSession->elderlyCounselee?->counselee?->name ?? '-',
                    // Lansia
                    'elderly_name' => $lastSession->elderlyCounselee?->elderly_name ?? '-',
                    'gender' => match ($lastSession->elderlyCounselee?->elderly_gender) {
                        'L' => 'Laki-Laki',
                        'P' => 'Perempuan',
                        default => '-',
                    },
                    'age' => $lastSession->elderlyCounselee?->elderly_age
                        ? $lastSession->elderlyCounselee->elderly_age.' Tahun'
                        : '-',
                    // Konselor
                    'counselor_name' => $lastSession->counselor?->name ?? '-',

                    // Puskesmas
                    'puskesmas' => $lastSession->counselor?->puskesmas
                        ? collect([
                            $lastSession->counselor->puskesmas->name,
                            $lastSession->counselor->puskesmas->village?->name,
                            $lastSession->counselor->puskesmas->village?->district?->name,
                            $lastSession->counselor->puskesmas->village?->district?->regency?->name,
                        ])->filter()->implode(', ')
                        : '-',

                    // Risiko Jatuh
                    'fall_risk_pre_test' => $fallRiskPre ?? '-',
                    'fall_risk_post_test' => $fallRiskPost ?? '-',
                    'fall_risk_difference' => is_numeric($fallRiskPre) && is_numeric($fallRiskPost)
                            ? $fallRiskPost - $fallRiskPre
                            : '-',
                    'fall_risk_category' => $lastSession->fallRiskScreening?->risk_level ?? '-',

                    // Pemberdayaan
                    'empowerment_pre_test' => $empowermentPre ?? '-',
                    'empowerment_post_test' => $empowermentPost ?? '-',
                    'empowerment_difference' => is_numeric($empowermentPre) && is_numeric($empowermentPost)
                            ? $empowermentPost - $empowermentPre
                            : '-',

                    'empowerment_category' => $lastSession->empowermentAssessment?->empowerment_level ?? '-',
                    // Tanggal
                    'first_screening_date' => $firstSession->created_at
                        ->translatedFormat('d F Y'),

                    'last_screening_date' => $lastSession->created_at
                        ->translatedFormat('d F Y'),
                ];
            });

        return [
            'availableDates' => $availableDates,
            'screenings' => $screenings,
        ];
    }

    private function getEvaluationReport()
    {
        $evaluations = Evaluation::query()

            ->with([
                'topic',
                'session.counselor',
                'session.elderlyCounselee.counselee',
            ])

            ->when(
                request()->filled('start_date'),
                fn ($query) => $query->whereDate(
                    'created_at',
                    '>=',
                    request('start_date')
                )
            )

            ->when(
                request()->filled('end_date'),
                fn ($query) => $query->whereDate(
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

                    'counselee_name' => $item->session?->elderlyCounselee?->counselee?->name ?? '-',

                    'elderly_name' => $item->session?->elderlyCounselee?->elderly_name ?? '-',

                    'counselor_name' => $item->session?->counselor?->name ?? '-',

                    'topic_name' => $item->topic?->topic ?? '-',

                    'score' => $item->total_score ?? '-',

                    'category' => $item->category ?? '-',

                    'percentage' => $item->percentage ?? '-',

                    'interpretation' => $item->interpretation ?? '-',

                    // 'notes' =>
                    //     $item->notes ?? '-',

                    'evaluation_date' => $item->created_at,
                ];
            });

        return [
            'availableDates' => $this->getAvailableDates(Evaluation::class),
            'evaluations' => $evaluations,
        ];
    }

    private function getReportData($report)
    {
        return match ($report) {

            'counselee' => [
                'title' => 'Konseli',
                'data' => $this->getCounseleeReport()['counselees']
                    ->values()
                    ->map(function ($item, $index) {
                        return [
                            'No' => $index + 1,
                            'Nama Konseli' => $item['name'],
                            'Jenis Kelamin' => $item['gender'],
                            'Usia' => $item['age'],
                            'Nomor HP' => $item['phone'],
                            'Pekerjaan' => $item['occupation'],
                            'Pendidikan' => $item['education'],
                            'Puskesmas' => $item['puskesmas'],
                            'Jumlah Sesi' => $item['jml_counselings'],
                            'Tanggal Terdaftar' => Carbon::parse(
                                $item['created_at']
                            )->translatedFormat('d F Y'),
                        ];
                    }),
            ],

            'elderly' => [
                'title' => 'Lansia',
                'data' => $this->getElderlyReport()['elderlies']
                    ->values()
                    ->map(function ($item, $index) {
                        return [
                            'No' => $index + 1,
                            'Nama Lansia' => $item['elderly_name'],
                            'Jenis Kelamin' => $item['elderly_gender'],
                            'Usia' => $item['elderly_age'].' Tahun',
                            'Pendamping/Konseli' => $item['counselee_name'],
                            'Lama Merawat' => $item['care_duration_months'],
                            'Pernah Jatuh' => $item['has_fallen'],
                            'Masalah Kesehatan' => $item['health_problems'] ?? '-',
                            'Puskesmas' => $item['puskesmas'],
                            'Tanggal Terdaftar' => Carbon::parse(
                                $item['created_at']
                            )->translatedFormat('d F Y'),
                        ];
                    }),
            ],

            'counselor' => [
                'title' => 'Konselor',
                'data' => $this->getCounselorReport()['counselors']
                    ->values()
                    ->map(function ($item, $index) {
                        return [
                            'No' => $index + 1,
                            'Nama Konselor' => $item['name'],
                            'Jenis Kelamin' => $item['gender'],
                            'Nomor HP' => $item['phone'],
                            'Total Lansia Ditangani' => $item['total_elderlies'],
                            'Puskesmas' => $item['puskesmas'],
                            'Tanggal Terdaftar' => Carbon::parse(
                                $item['created_at']
                            )->translatedFormat('d F Y'),
                        ];
                    }),
            ],

            'counseling' => [
                'title' => 'Konseling',
                'data' => $this->getCounselingReport()['counselings']
                    ->values()
                    ->map(function ($item, $index) {
                        return [
                            'No' => $index + 1,
                            'Konseli' => $item['counselee_name'],
                            'Nama Lansia' => $item['elderly_name'],
                            'Jenis Kelamin' => $item['elderly_gender'],
                            'Usia' => $item['elderly_age'],
                            'Konselor' => $item['counselor_name'],
                            'Puskesmas' => $item['puskesmas'],
                            'Total Sesi' => $item['total_sessions'],
                            'Tanggal Mulai Konseling' => $item['first_counseling_date'],
                            'Konseling Terakhir' => $item['last_counseling_date'],
                        ];
                    }),
            ],

            'screening' => [
                'title' => 'Skrining',
                'data' => $this->getScreeningReport()['screenings']
                    ->values()
                    ->map(function ($item, $index) {
                        return [
                            'No' => $index + 1,

                            // Identitas
                            'Konseli' => $item['counselee_name'],
                            'Nama Lansia' => $item['elderly_name'],
                            'Jenis Kelamin' => $item['gender'],
                            'Usia' => $item['age'],

                            // Pelayanan
                            'Konselor' => $item['counselor_name'],
                            'Puskesmas' => $item['puskesmas'],

                            // Waktu
                            'Tanggal Skrining Awal' => $item['first_screening_date'],
                            'Tanggal Skrining Akhir' => $item['last_screening_date'],

                            // Hasil Risiko Jatuh
                            'Risiko Jatuh (Pre-Test)' => $item['fall_risk_pre_test'],
                            'Risiko Jatuh (Post-Test)' => $item['fall_risk_post_test'],
                            'Selisih Risiko Jatuh' => $item['fall_risk_difference'],
                            'Kategori Risiko Jatuh' => $item['fall_risk_category'],

                            // Hasil Pemberdayaan
                            'Pemberdayaan Keluarga (Pre-Test)' => $item['empowerment_pre_test'],
                            'Pemberdayaan Keluarga (Post-Test)' => $item['empowerment_post_test'],
                            'Selisih Pemberdayaan Keluarga' => $item['empowerment_difference'],
                            'Kategori Pemberdayaan Keluarga' => $item['empowerment_category'],
                        ];
                    }),
            ],

            'evaluation' => [
                'title' => 'Evaluasi',
                'data' => $this->getEvaluationReport()['evaluations']
                    ->values()
                    ->map(function ($item, $index) {
                        return [
                            'No' => $index + 1,

                            'Konseli' => $item['counselee_name'],

                            'Nama Lansia' => $item['elderly_name'],

                            'Konselor' => $item['counselor_name'],

                            'Topik' => $item['topic_name'],

                            'Skor' => $item['score'],

                            'Persentase' => $item['percentage'].'%',

                            'Kategori' => $item['category'],

                            'Keterangan' => $item['interpretation'],

                            'Tanggal Evaluasi' => Carbon::parse(
                                $item['evaluation_date']
                            )->translatedFormat('d F Y'),
                        ];
                    }),
            ],

            default => abort(404),
        };
    }

    public function exportExcel($report)
    {
        $reportData = $this->getReportData($report);

        $filename =
            'Laporan_'.
            $reportData['title'].
            '_'.
            Carbon::now()->format('dmY_His').
            '.xlsx';

        return Excel::download(
            new ReportExport($reportData['data']),
            $filename
        );
    }

    public function exportPdf($report)
    {
        $reportData = $this->getReportData($report);

        $filename =
            'Laporan_'.
            $reportData['title'].
            '_'.
            now()->format('dmY_His').
            '.pdf';

        $pdf = Pdf::loadView(
            'exports.report-pdf',
            [
                'title' => $reportData['title'],
                'data' => $reportData['data'],
                'startDate' => request('start_date'),
                'endDate' => request('end_date'),
            ]
        );

        return $pdf->stream($filename);
    }
}
