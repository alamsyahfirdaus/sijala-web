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
            | VALIDASI JUMLAH PERTANYAAN
            |--------------------------------------------------------------------------
            */

            $totalQuestion = EmpowermentQuestion::whereNotNull('dimension_id')->count();

            if (count($request->answers) !== $totalQuestion) {
                throw new \Exception(
                    'Seluruh pertanyaan harus dijawab sebelum dikirim.'
                );
            }

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
            |
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

                $question = EmpowermentQuestion::find($item['question_id']);

                if (!$question) {
                    throw new \Exception('Pertanyaan tidak ditemukan.');
                }

                $answer = (int) $item['answer'];

                // Reverse Scoring
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
            */

            if ($totalScore <= 70) {

                $empowermentLevel = 'Rendah';

                $interpretation =
                    'Tingkat pemberdayaan keluarga tergolong rendah. '
                    . 'Keluarga masih memerlukan pendampingan, edukasi, serta peningkatan '
                    . 'kemampuan dalam mengenali masalah kesehatan, mengambil keputusan, '
                    . 'merawat anggota keluarga, memodifikasi lingkungan, dan memanfaatkan '
                    . 'fasilitas pelayanan kesehatan.';

            } elseif ($totalScore <= 105) {

                $empowermentLevel = 'Sedang';

                $interpretation =
                    'Tingkat pemberdayaan keluarga tergolong sedang. '
                    . 'Keluarga telah memiliki kemampuan dalam mendukung perawatan '
                    . 'kesehatan anggota keluarga, namun masih diperlukan penguatan '
                    . 'melalui edukasi, motivasi, dan pendampingan secara berkelanjutan.';

            } else {

                $empowermentLevel = 'Tinggi';

                $interpretation =
                    'Tingkat pemberdayaan keluarga tergolong tinggi. '
                    . 'Keluarga memiliki kemampuan yang baik dalam mengenali masalah '
                    . 'kesehatan, mengambil keputusan, memberikan perawatan, '
                    . 'menciptakan lingkungan yang aman, serta memanfaatkan fasilitas '
                    . 'pelayanan kesehatan secara optimal.';
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
            | HAPUS JAWABAN LAMA
            |--------------------------------------------------------------------------
            */

            EmpowermentAnswer::where(
                'empowerment_id',
                $empowerment->id
            )->delete();

            /*
            |--------------------------------------------------------------------------
            | PERSIAPKAN BULK INSERT
            |--------------------------------------------------------------------------
            */

            $now = now();

            foreach ($answersData as &$answer) {

                $answer['empowerment_id'] = $empowerment->id;
                $answer['created_at'] = $now;
                $answer['updated_at'] = $now;

            }

            EmpowermentAnswer::insert($answersData);

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

            \Log::error('Empowerment Assessment Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menyimpan data pemberdayaan keluarga.',
            ], 500);
        }
    }
}
