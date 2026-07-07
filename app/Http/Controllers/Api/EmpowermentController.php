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
        $empowermentQuestions = EmpowermentQuestion::with([
                'questions' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            ])
            ->whereNull('dimension_id')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($dimension) {

                return [
                    'id'        => $dimension->id,
                    'dimension' => $dimension->question,

                    'questions' => $dimension->questions->map(function ($question) {

                        return [
                            'id'          => $question->id,
                            'item_number' => $question->item_number,
                            'question'    => $question->question,
                        ];

                    })->values(),
                ];

            })->values();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar pertanyaan pemberdayaan keluarga berhasil diambil.',
            'data'    => $empowermentQuestions,
        ]);
    }

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */
        $validator = Validator::make($request->all(), [
            'counseling_session_id' => 'required|exists:counseling_sessions,id',
            'answers'               => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:family_empowerment_questions,id',
            'answers.*.answer'      => 'required|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'data'    => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | INISIALISASI
            |--------------------------------------------------------------------------
            */
            $totalScore = 0;
            $answersData = [];

            /*
            |--------------------------------------------------------------------------
            | PERHITUNGAN SKOR
            |--------------------------------------------------------------------------
            | Favorable
            | STS = 1
            | TS  = 2
            | S   = 3
            | SS  = 4
            |
            | Unfavorable (Reverse Scoring)
            | STS = 4
            | TS  = 3
            | S   = 2
            | SS  = 1
            |--------------------------------------------------------------------------
            */

            foreach ($request->answers as $item) {

                $question = EmpowermentQuestion::findOrFail($item['question_id']);

                $answer = (int) $item['answer'];

                // Reverse scoring
                $score = $question->is_favorable
                    ? $answer
                    : (5 - $answer);

                $totalScore += $score;

                $answersData[] = [
                    'question_id' => $question->id,
                    'answer'      => $answer,
                    'score'       => $score,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | INTERPRETASI HASIL
            |--------------------------------------------------------------------------
            | Rentang Skor:
            | Minimum : 35
            | Maksimum: 140
            |--------------------------------------------------------------------------
            */

            if ($totalScore <= 70) {

                $empowermentLevel = 'Rendah';

                $interpretation =
                    'Tingkat pemberdayaan keluarga tergolong rendah. '
                    . 'Keluarga masih memerlukan pendampingan, edukasi, dan peningkatan '
                    . 'kemampuan dalam mengenali masalah kesehatan, mengambil keputusan, '
                    . 'merawat anggota keluarga, memodifikasi lingkungan, serta '
                    . 'memanfaatkan fasilitas pelayanan kesehatan.';

            } elseif ($totalScore <= 105) {

                $empowermentLevel = 'Sedang';

                $interpretation =
                    'Tingkat pemberdayaan keluarga tergolong sedang. '
                    . 'Keluarga telah menunjukkan kemampuan dalam mendukung perawatan '
                    . 'kesehatan anggota keluarga, namun masih diperlukan penguatan '
                    . 'pada beberapa aspek melalui edukasi, motivasi, dan pendampingan '
                    . 'secara berkelanjutan.';

            } else {

                $empowermentLevel = 'Tinggi';

                $interpretation =
                    'Tingkat pemberdayaan keluarga tergolong tinggi. '
                    . 'Keluarga memiliki kemampuan yang baik dalam mengenali masalah '
                    . 'kesehatan, mengambil keputusan, memberikan perawatan, menciptakan '
                    . 'lingkungan yang aman, serta memanfaatkan fasilitas pelayanan '
                    . 'kesehatan secara optimal.';
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN / UPDATE ASESMEN
            |--------------------------------------------------------------------------
            */

            $empowerment = EmpowermentAssessment::updateOrCreate(
                [
                    'counseling_session_id' => $request->counseling_session_id,
                ],
                [
                    'total_score'       => $totalScore,
                    'empowerment_level' => $empowermentLevel,
                    'interpretation'    => $interpretation,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | HAPUS DETAIL JAWABAN LAMA
            |--------------------------------------------------------------------------
            */

            EmpowermentAnswer::where(
                'empowerment_id',
                $empowerment->id
            )->delete();

            /*
            |--------------------------------------------------------------------------
            | SIMPAN DETAIL JAWABAN
            |--------------------------------------------------------------------------
            */

            collect($answersData)->each(function ($answer) use ($empowerment) {

                EmpowermentAnswer::create([
                    'empowerment_id' => $empowerment->id,
                    'question_id'    => $answer['question_id'],
                    'answer'         => $answer['answer'],
                    'score'          => $answer['score'],
                ]);

            });

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            $message = $empowerment->wasRecentlyCreated
                ? 'Jawaban pemberdayaan keluarga berhasil disimpan.'
                : 'Jawaban pemberdayaan keluarga berhasil diperbarui.';

            return response()->json([
                'status'  => true,
                'message' => $message,
                'data'    => [
                    'id'                    => $empowerment->id,
                    'counseling_session_id' => $request->counseling_session_id,
                    'total_score'           => $totalScore,
                    'empowerment_level'     => $empowermentLevel,
                    'interpretation'        => $interpretation,
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
