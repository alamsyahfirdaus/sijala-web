<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\EmpowermentAssessment;
use App\Models\FallRiskScreening;
use App\Models\Evaluation;
use Illuminate\Contracts\Encryption\DecryptException;
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
            $session->session_count = CounselingSession::where(
                'elderly_counselee_id',
                $session->elderly_counselee_id
            )->count();
        }

        return view('counselings', compact('title', 'counselingSessions'));
    }

    public function session($id)
    {
        try {

            // Dekripsi ID sesi konseling dari URL
            $id = Crypt::decrypt($id);

            $title = 'Konseling';

            // Ambil data sesi konseling beserta relasi:
            // - Data lansia dan konseli
            // - Data konselor dan puskesmas
            $counseling = CounselingSession::with([
                'elderlyCounselee.counselee',
                'counselor.puskesmas',
            ])->findOrFail($id);

            // Ambil seluruh riwayat sesi konseling
            // berdasarkan lansia yang sama
            $sessions = CounselingSession::where(
                'elderly_counselee_id',
                $counseling->elderly_counselee_id
            )
                ->orderBy('created_at', 'asc')
                ->get();

            // Menampung seluruh hasil skrining
            $screenings = [];

            foreach ($sessions as $session) {

                // Ambil hasil skrining risiko jatuh
                $fallRisk = FallRiskScreening::where(
                    'counseling_session_id',
                    $session->id
                )->first();

                // Ambil hasil asesmen pemberdayaan keluarga
                $empowerment = EmpowermentAssessment::where(
                    'counseling_session_id',
                    $session->id
                )->first();

                // Simpan hanya jika terdapat minimal
                // satu hasil skrining pada sesi tersebut
                if ($fallRisk || $empowerment) {
                    $screenings[] = [
                        'session' => $session,
                        'fallRisk' => $fallRisk,
                        'empowerment' => $empowerment,
                    ];
                }
            }

            $evaluations = Evaluation::whereIn(
                'counseling_session_id',
                $sessions->pluck('id')
            )->with('topic')->get();

            $sessionNumbers = $sessions
                ->pluck('id')
                ->flip()
                ->map(fn ($index) => $index + 1);

            // Tampilkan halaman detail konseling
            return view('counseling_session', compact(
                'title',
                'counseling',
                'sessions',
                'screenings',
                'evaluations',
                'sessionNumbers'
            ));

        } catch (DecryptException $e) {

            // Jika ID tidak valid atau gagal didekripsi
            abort(404);
        }
    }
}
