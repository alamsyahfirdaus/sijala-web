<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

            // echo json_encode($screenings);

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

    public function updateScore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:fall-risk,empowerment,evaluation',
            'id' => 'required|integer',
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

                    $totalScore = (int) $request->score;

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
                        'total_score' => $totalScore,
                        'risk_level' => $riskLevel,
                        'interpretation' => $interpretation,
                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | PEMBERDAYAAN KELUARGA
                |--------------------------------------------------------------------------
                */
                case 'empowerment':

                    $data = EmpowermentAssessment::findOrFail($request->id);

                    $totalScore = (int) $request->score;

                    /*
                    * Diasumsikan skor yang diedit adalah skor akhir
                    * dalam rentang 0 - 100.
                    */
                    $finalScore = $totalScore;

                    if ($finalScore <= 50) {

                        $level = 'Rendah';

                    } elseif ($finalScore <= 75) {

                        $level = 'Sedang';

                    } else {

                        $level = 'Tinggi';
                    }

                    if ($level === 'Tinggi') {

                        $interpretation =
                            'Tingkat pemberdayaan keluarga tergolong tinggi. '
                            . 'Keluarga memiliki kemampuan yang baik dalam memahami, '
                            . 'mengambil keputusan, serta berperan aktif dalam proses '
                            . 'perawatan dan pemeliharaan kesehatan anggota keluarga.';

                    } elseif ($level === 'Sedang') {

                        $interpretation =
                            'Tingkat pemberdayaan keluarga tergolong sedang. '
                            . 'Keluarga telah menunjukkan kemampuan dalam mendukung '
                            . 'perawatan kesehatan, namun masih terdapat beberapa aspek '
                            . 'yang perlu diperkuat melalui edukasi dan pendampingan.';

                    } else {

                        $interpretation =
                            'Tingkat pemberdayaan keluarga tergolong rendah. '
                            . 'Diperlukan pendampingan yang lebih intensif, peningkatan '
                            . 'pengetahuan, serta penguatan peran keluarga dalam '
                            . 'mendukung perawatan dan pengambilan keputusan terkait '
                            . 'kesehatan.';
                    }

                    $data->update([
                        'total_score' => $totalScore,
                        'empowerment_level' => $level,
                        'interpretation' => $interpretation,
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

                    $totalScore = (int) $request->score;

                    /*
                    * Diasumsikan skor yang diedit sudah dalam bentuk
                    * persentase (0 - 100).
                    *
                    * Jika menggunakan skor mentah, sesuaikan kembali
                    * rumus perhitungannya.
                    */
                    $percentage = $totalScore;

                    if ($percentage >= 76) {

                        $category = 'Baik';

                    } elseif ($percentage >= 56) {

                        $category = 'Cukup';

                    } else {

                        $category = 'Kurang';
                    }

                    $topicName = $data->topic->topic ?? 'materi';

                    if ($category === 'Baik') {

                        $interpretation =
                            'Peserta memiliki pemahaman yang baik terhadap materi "'
                            . $topicName .
                            '". Sebagian besar pertanyaan dapat dijawab dengan benar. '
                            . 'Disarankan untuk mempertahankan pemahaman yang sudah '
                            . 'dimiliki dan terus menerapkan materi yang telah dipelajari.';

                    } elseif ($category === 'Cukup') {

                        $interpretation =
                            'Peserta memiliki pemahaman yang cukup terhadap materi "'
                            . $topicName .
                            '". Masih terdapat beberapa konsep yang perlu diperkuat. '
                            . 'Disarankan untuk mengulang kembali materi dan melakukan '
                            . 'pendampingan lanjutan pada bagian yang belum dipahami.';

                    } else {

                        $interpretation =
                            'Peserta masih mengalami kesulitan dalam memahami materi "'
                            . $topicName .
                            '". Diperlukan edukasi ulang, pendampingan, serta penguatan '
                            . 'materi agar tingkat pemahaman dapat meningkat.';
                    }

                    $data->update([
                        'total_score' => $totalScore,
                        'percentage' => $percentage,
                        'category' => $category,
                        'interpretation' => $interpretation,
                    ]);

                    break;
            }

            return back()->with(
                'success',
                'Skor berhasil diperbarui.'
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

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

            $session = CounselingSession::findOrFail($id);

            $session->delete();

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
