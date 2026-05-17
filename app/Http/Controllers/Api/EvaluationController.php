<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationTopic;
use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL DAFTAR TOPIK EVALUASI
        |--------------------------------------------------------------------------
        | Menampilkan seluruh topik evaluasi yang masih aktif.
        | Data diurutkan berdasarkan kolom `order` agar tampil sesuai
        | urutan yang telah ditentukan oleh admin.
        */
        $topics = EvaluationTopic::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get([
                'id',
                'topic',
                'description',
            ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE SUKSES
        |--------------------------------------------------------------------------
        | Mengembalikan daftar topik evaluasi dalam format JSON.
        */
        return response()->json([
            'status' => true,
            'message' => 'Daftar topik evaluasi berhasil diambil.',
            'data' => $topics,
        ], 200);
    }

    public function getEvaluationQuestions($evaluationTopicId)
    {
        /*
        |--------------------------------------------------------------------------
        | CARI TOPIK EVALUASI
        |--------------------------------------------------------------------------
        | Memastikan topik evaluasi dengan ID yang diberikan
        | tersedia dan berstatus aktif.
        */
        $topic = EvaluationTopic::where('id', $evaluationTopicId)
            ->where('is_active', true)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI TOPIK
        |--------------------------------------------------------------------------
        | Jika topik tidak ditemukan, kembalikan response 404.
        */
        if (!$topic) {
            return response()->json([
                'status' => false,
                'message' => 'Topik evaluasi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DAFTAR PERTANYAAN
        |--------------------------------------------------------------------------
        | Mengambil seluruh pertanyaan aktif yang terkait dengan
        | topik evaluasi yang dipilih.
        */
        $questions = EvaluationQuestion::where(
                'evaluation_topic_id',
                $evaluationTopicId
            )
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->get([
                'id',
                'question',
                'option_a',
                'option_b',
                'option_c',
                'option_d',
            ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE SUKSES
        |--------------------------------------------------------------------------
        | Mengembalikan informasi topik dan seluruh pertanyaan
        | evaluasi dalam format JSON.
        */
        return response()->json([
            'status' => true,
            'message' => 'Daftar pertanyaan evaluasi berhasil diambil.',
            'data' => [
                'topic' => [
                    'id' => $topic->id,
                    'topic' => $topic->topic,
                    'description' => $topic->description,
                ],
                'questions' => $questions,
            ],
        ], 200);
    }

    public function saveEvaluationQuestions(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. VALIDASI INPUT
        |--------------------------------------------------------------------------
        | Memastikan seluruh data yang dikirim dari client sudah lengkap
        | dan sesuai dengan aturan yang ditentukan.
        */
        $validator = Validator::make(
            $request->all(),
            [
                'counseling_session_id' => 'required|exists:counseling_sessions,id',
                'evaluation_topic_id' => 'required|exists:evaluation_topics,id',
                'answers' => 'required|array|min:1',
                'answers.*.evaluation_question_id' => 'required|exists:evaluation_questions,id',
                'answers.*.selected_answer' => 'required|in:a,b,c,d',
            ],
            [
                'counseling_session_id.required' => 'Sesi konseling wajib dipilih.',
                'counseling_session_id.exists' => 'Sesi konseling tidak ditemukan.',
                'evaluation_topic_id.required' => 'Topik evaluasi wajib dipilih.',
                'evaluation_topic_id.exists' => 'Topik evaluasi tidak ditemukan.',
                'answers.required' => 'Jawaban evaluasi wajib diisi.',
                'answers.array' => 'Format jawaban evaluasi tidak valid.',
                'answers.min' => 'Minimal harus ada satu jawaban yang dikirim.',
                'answers.*.evaluation_question_id.required' => 'ID pertanyaan evaluasi wajib diisi.',
                'answers.*.evaluation_question_id.exists' => 'Pertanyaan evaluasi tidak ditemukan.',
                'answers.*.selected_answer.required' => 'Jawaban yang dipilih wajib diisi.',
                'answers.*.selected_answer.in' => 'Jawaban yang dipilih harus berupa a, b, c, atau d.',
            ]
        );

        // Jika validasi gagal, kembalikan pesan error
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        // Mulai database transaction
        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | 2. AMBIL TOPIK EVALUASI
            |--------------------------------------------------------------------------
            | Pastikan topik evaluasi yang dipilih masih aktif.
            */
            $topic = EvaluationTopic::where(
                'id',
                $request->evaluation_topic_id
            )
                ->where('is_active', true)
                ->first();

            // Jika topik tidak ditemukan
            if (! $topic) {
                return response()->json([
                    'status' => false,
                    'message' => 'Topik evaluasi tidak ditemukan.',
                    'data' => null,
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | 3. VALIDASI PERTANYAAN
            |--------------------------------------------------------------------------
            | Pastikan seluruh question_id yang dikirim benar-benar
            | berasal dari topik evaluasi yang dipilih.
            */
            $questionIds = collect($request->answers)
                ->pluck('evaluation_question_id')
                ->toArray();

            $validQuestionCount = EvaluationQuestion::where(
                'evaluation_topic_id',
                $request->evaluation_topic_id
            )
                ->where('is_active', true)
                ->whereIn('id', $questionIds)
                ->count();

            // Jika ada question_id yang tidak valid
            if ($validQuestionCount !== count($questionIds)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terdapat pertanyaan yang tidak sesuai dengan topik evaluasi.',
                    'data' => null,
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | 4. HITUNG HASIL EVALUASI
            |--------------------------------------------------------------------------
            | Rumus:
            | P = F / N × 100%
            |
            | F = jumlah jawaban benar
            | N = jumlah soal
            | P = persentase nilai
            */
            $correctAnswers = 0;
            $totalScore = 0;
            $totalQuestions = count($request->answers);

            foreach ($request->answers as $answer) {
                // Ambil data pertanyaan
                $question = EvaluationQuestion::find(
                    $answer['evaluation_question_id']
                );

                // Jika jawaban benar
                if (
                    $question->correct_answer ===
                    $answer['selected_answer']
                ) {
                    $correctAnswers++;
                    $totalScore += $question->score;
                }
            }

            // Hitung persentase nilai
            $percentage = $totalQuestions > 0
                ? round(
                    ($correctAnswers / $totalQuestions) * 100,
                    2
                )
                : 0;

            /*
            |--------------------------------------------------------------------------
            | 5. TENTUKAN KATEGORI NILAI
            |--------------------------------------------------------------------------
            | Baik   : 76 - 100%
            | Cukup  : 56 - 75%
            | Kurang : < 56%
            */
            if ($percentage >= 76) {
                $category = 'Baik';
            } elseif ($percentage >= 56) {
                $category = 'Cukup';
            } else {
                $category = 'Kurang';
            }

            /*
            |--------------------------------------------------------------------------
            | 6. CEK DATA EVALUASI BERDASARKAN counseling_session_id
            |--------------------------------------------------------------------------
            | Setiap sesi konseling hanya boleh memiliki satu data evaluasi.
            | Jika data sudah ada, maka diperbarui.
            | Jika belum ada, maka dibuat baru.
            */
            $evaluation = Evaluation::where(
                'counseling_session_id',
                $request->counseling_session_id
            )->first();

            if ($evaluation) {
                // Update data evaluasi
                $evaluation->update([
                    'evaluation_topic_id' => $request->evaluation_topic_id,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'total_score' => $totalScore,
                    'percentage' => $percentage,
                    'category' => $category,
                ]);

                // Hapus seluruh jawaban lama
                EvaluationAnswer::where(
                    'evaluation_id',
                    $evaluation->id
                )->delete();

                $message = 'Hasil evaluasi berhasil diperbarui.';
                $statusCode = 200;
            } else {
                // Simpan evaluasi baru
                $evaluation = Evaluation::create([
                    'counseling_session_id' => $request->counseling_session_id,
                    'evaluation_topic_id' => $request->evaluation_topic_id,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'total_score' => $totalScore,
                    'percentage' => $percentage,
                    'category' => $category,
                ]);

                $message = 'Hasil evaluasi berhasil disimpan.';
                $statusCode = 201;
            }

            /*
            |--------------------------------------------------------------------------
            | 7. SIMPAN DETAIL JAWABAN
            |--------------------------------------------------------------------------
            | Menyimpan seluruh jawaban pengguna ke tabel
            | evaluation_answers.
            */
            foreach ($request->answers as $answer) {
                $question = EvaluationQuestion::find(
                    $answer['evaluation_question_id']
                );

                $isCorrect =
                    $question->correct_answer ===
                    $answer['selected_answer'];

                EvaluationAnswer::create([
                    'evaluation_id' => $evaluation->id,
                    'evaluation_question_id' => $answer['evaluation_question_id'],
                    'selected_answer' => $answer['selected_answer'],
                    'is_correct' => $isCorrect,
                    'score' => $isCorrect
                        ? $question->score
                        : 0,
                ]);
            }

            // Simpan seluruh perubahan ke database
            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | 8. RESPONSE SUKSES
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'evaluation_id' => $evaluation->id,
                    'topic' => $topic->topic,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'wrong_answers' => $totalQuestions - $correctAnswers,
                    'total_score' => $totalScore,
                    'percentage' => $percentage,
                    'category' => $category,
                ],
            ], $statusCode);
        } catch (\Exception $e) {
            // Batalkan seluruh proses jika terjadi error
            DB::rollBack();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE ERROR
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan evaluasi.',
                'error' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
