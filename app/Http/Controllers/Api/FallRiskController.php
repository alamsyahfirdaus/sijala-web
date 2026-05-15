<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FallRiskAnswer;
use App\Models\FallRiskQuestion;
use App\Models\FallRiskScreening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FallRiskController extends Controller
{
    public function index()
    {
        $questions = FallRiskQuestion::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar pertanyaan risiko jatuh',
            'data' => $questions
        ]);
    }

    public function store(Request $request)
    {
        // ================= VALIDASI =================
        $validator = Validator::make($request->all(), [
            'counseling_session_id' => 'required|exists:counseling_sessions,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:elderly_fall_risk_questions,id',
            'answers.*.answer' => 'required|in:ya,tidak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'data'    => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // ================= HITUNG TOTAL SKOR =================
            $totalScore = 0;
            $answersData = [];

            foreach ($request->answers as $item) {
                $question = FallRiskQuestion::find($item['question_id']);

                if (!$question) {
                    throw new \Exception('Pertanyaan tidak ditemukan.');
                }

                // Tentukan skor berdasarkan jawaban
                $score = $item['answer'] === 'ya'
                    ? (int) $question->score_yes
                    : (int) $question->score_no;

                $totalScore += $score;

                $answersData[] = [
                    'question_id' => $question->id,
                    'answer'      => $item['answer'],
                    'score'       => $score,
                ];
            }

            // ================= INTERPRETASI RISIKO =================
            if ($totalScore <= 3) {
                $riskLevel = 'Rendah';
                $interpretation = 'Risiko jatuh minimal.';
            } elseif ($totalScore <= 7) {
                $riskLevel = 'Sedang';
                $interpretation = 'Perlu edukasi dan pemantauan.';
            } else {
                $riskLevel = 'Tinggi';
                $interpretation = 'Perlu asesmen lanjutan dan intervensi.';
            }

            // ================= SIMPAN / UPDATE SCREENING =================
            $fallRiskScreening = FallRiskScreening::updateOrCreate(
                [
                    // Kunci unik: satu sesi hanya boleh memiliki satu screening
                    'counseling_session_id' => $request->counseling_session_id,
                ],
                [
                    'total_score'    => $totalScore,
                    'risk_level'     => $riskLevel,
                    'interpretation' => $interpretation,
                ]
            );

            // ================= HAPUS JAWABAN LAMA =================
            // Agar tidak duplikat saat user mengisi ulang
            FallRiskAnswer::where(
                'screening_id',
                $fallRiskScreening->id
            )->delete();

            // ================= SIMPAN DETAIL JAWABAN =================
            foreach ($answersData as $answer) {
                FallRiskAnswer::create([
                    'screening_id' => $fallRiskScreening->id,
                    'question_id'  => $answer['question_id'],
                    'answer'       => $answer['answer'],
                    'score'        => $answer['score'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Skrining risiko jatuh berhasil disimpan.',
                'data'    => [
                    'id'                     => $fallRiskScreening->id,
                    'counseling_session_id'  => $request->counseling_session_id,
                    'total_score'            => $totalScore,
                    'risk_level'             => $riskLevel,
                    'interpretation'         => $interpretation,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
