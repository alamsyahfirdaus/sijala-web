<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmpowermentAnswer;
use App\Models\EmpowermentAssessment;
use App\Models\EmpowermentQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmpowermentController extends Controller
{
    public function index()
    {
        // Ambil seluruh data dimensi (parent) beserta pertanyaannya (child)
        $empowermentQuestions = EmpowermentQuestion::with([
                'questions' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            ])
            ->whereNull('dimension_id') // Hanya data dimensi
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($dimension) {
                return [
                    'id' => $dimension->id,
                    'dimension' => $dimension->question,

                    // Daftar pertanyaan dalam dimensi tersebut
                    'questions' => $dimension->questions->map(function ($question) use ($dimension) {
                        return [
                            'id' => $question->id,
                            'question' => $question->question,

                            // Jika child min/max null, gunakan nilai dari parent
                            'min_score' => $question->min_score ?? $dimension->min_score,
                            'max_score' => $question->max_score ?? $dimension->max_score,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'message' => 'Daftar pertanyaan pemberdayaan keluarga berhasil diambil.',
            'data' => $empowermentQuestions,
        ]);
    }

    public function store(Request $request)
    {
        // ================= VALIDASI =================
        $validator = Validator::make($request->all(), [
            'counseling_session_id' => 'required|exists:counseling_sessions,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:family_empowerment_questions,id',
            'answers.*.answer' => 'required|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $totalScore = 0;
            $maxScore = 0;
            $answersData = [];

            // ================= HITUNG SKOR =================
            foreach ($request->answers as $item) {
                $question = EmpowermentQuestion::find($item['question_id']);

                if (!$question) {
                    throw new \Exception('Pertanyaan tidak ditemukan');
                }

                $score = (int) $item['answer'];

                // Total skor jawaban user
                $totalScore += $score;

                // Skor maksimum pertanyaan
                $maxScore += $question->max_score ?? 4;

                $answersData[] = [
                    'question_id' => $question->id,
                    'answer'      => $score,
                    'score'       => $score,
                ];
            }

            // ================= KONVERSI KE SKALA 100 =================
            $finalScore = 0;
            if ($maxScore > 0) {
                $finalScore = round(($totalScore / $maxScore) * 100);
            }

            // ================= KATEGORI LEVEL =================
            if ($finalScore <= 50) {
                $level = 'Rendah';
            } elseif ($finalScore <= 75) {
                $level = 'Sedang';
            } else {
                $level = 'Tinggi';
            }

            // ==========================================================
            // CEK APAKAH ASESMEN SUDAH ADA UNTUK SESSION INI
            // Jika ada → update
            // Jika belum → create
            // ==========================================================
            $empowerment = EmpowermentAssessment::updateOrCreate(
                [
                    // Kondisi pencarian
                    'counseling_session_id' => $request->counseling_session_id,
                ],
                [
                    // Data yang diupdate/disimpan
                    'total_score'       => $finalScore,
                    'empowerment_level' => $level,
                ]
            );

            // ==========================================================
            // HAPUS DETAIL JAWABAN LAMA AGAR TIDAK DUPLIKAT
            // ==========================================================
            EmpowermentAnswer::where('empowerment_id', $empowerment->id)
                ->delete();

            // ==========================================================
            // SIMPAN ULANG DETAIL JAWABAN
            // ==========================================================
            foreach ($answersData as $ans) {
                EmpowermentAnswer::create([
                    'empowerment_id' => $empowerment->id,
                    'question_id'    => $ans['question_id'],
                    'answer'         => $ans['answer'],
                    'score'          => $ans['score'],
                ]);
            }

            DB::commit();

            // Tentukan apakah create atau update
            $message = $empowerment->wasRecentlyCreated
                ? 'Jawaban pemberdayaan keluarga berhasil disimpan.'
                : 'Jawaban pemberdayaan keluarga berhasil diperbarui.';

            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'id' => $empowerment->id,
                    'counseling_session_id' => $request->counseling_session_id,
                    'total_score' => $finalScore,
                    'empowerment_level' => $level,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
