<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationTopic;
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
        if (! $topic) {
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

        // Jika validasi gagal
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
            | 2. AMBIL DATA SESI KONSELING
            |--------------------------------------------------------------------------
            | Memastikan sesi konseling tersedia.
            */
            $counselingSession = CounselingSession::find(
                $request->counseling_session_id
            );

            if (! $counselingSession) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sesi konseling tidak ditemukan.',
                    'data' => null,
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | 3. AMBIL TOPIK EVALUASI
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
            | 4. VALIDASI PERTANYAAN
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

            // Jika ada question_id tidak valid
            if ($validQuestionCount !== count($questionIds)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terdapat pertanyaan yang tidak sesuai dengan topik evaluasi.',
                    'data' => null,
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | 5. HITUNG HASIL EVALUASI
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
            | 6. TENTUKAN KATEGORI NILAI
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
            | INTERPRETASI HASIL EVALUASI
            |--------------------------------------------------------------------------
            | Membuat kesimpulan otomatis berdasarkan hasil evaluasi
            | yang diperoleh peserta.
            */
            if ($category === 'Baik') {

                $interpretation =
                    'Peserta memiliki pemahaman yang baik terhadap materi "'.
                    $topic->topic.
                    '". Sebagian besar pertanyaan dapat dijawab dengan benar. '
                    .'Disarankan untuk mempertahankan pemahaman yang sudah dimiliki '
                    .'dan terus menerapkan materi yang telah dipelajari.';

            } elseif ($category === 'Cukup') {

                $interpretation =
                    'Peserta memiliki pemahaman yang cukup terhadap materi "'.
                    $topic->topic.
                    '". Masih terdapat beberapa konsep yang perlu diperkuat. '
                    .'Disarankan untuk mengulang kembali materi dan melakukan '
                    .'pendampingan lanjutan pada bagian yang belum dipahami.';

            } else {

                $interpretation =
                    'Peserta masih mengalami kesulitan dalam memahami materi "'.
                    $topic->topic.
                    '". Diperlukan edukasi ulang, pendampingan, serta penguatan '
                    .'materi agar tingkat pemahaman dapat meningkat.';
            }

            /*
            |--------------------------------------------------------------------------
            | 7. CEK DATA EVALUASI
            |--------------------------------------------------------------------------
            | Satu sesi konseling dapat memiliki banyak topik evaluasi.
            |
            | Namun kombinasi:
            | - counseling_session_id
            | - evaluation_topic_id
            |
            | hanya boleh memiliki satu data evaluasi.
            |
            | Jika evaluasi untuk topik yang sama sudah ada,
            | maka data akan diperbarui.
            |
            | Jika belum ada,
            | maka dibuat data evaluasi baru.
            */
            $evaluation = Evaluation::where(
                'counseling_session_id',
                $request->counseling_session_id
            )
                ->where(
                    'evaluation_topic_id',
                    $request->evaluation_topic_id
                )
                ->first();

            if ($evaluation) {

                /*
                |--------------------------------------------------------------------------
                | UPDATE EVALUASI
                |--------------------------------------------------------------------------
                | Update data evaluasi yang sudah ada untuk topik yang sama.
                */
                $evaluation->update([
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'total_score' => $totalScore,
                    'percentage' => $percentage,
                    'category' => $category,
                    'interpretation' => $interpretation,
                ]);

                /*
                |--------------------------------------------------------------------------
                | HAPUS JAWABAN LAMA
                |--------------------------------------------------------------------------
                | Karena evaluasi diperbarui, maka seluruh jawaban lama
                | harus dihapus terlebih dahulu.
                */
                EvaluationAnswer::where(
                    'evaluation_id',
                    $evaluation->id
                )->delete();

                $message = 'Hasil evaluasi berhasil diperbarui.';
                $statusCode = 200;

            } else {

                /*
                |--------------------------------------------------------------------------
                | SIMPAN EVALUASI BARU
                |--------------------------------------------------------------------------
                | Membuat data evaluasi baru untuk topik yang belum pernah
                | dikerjakan pada sesi konseling ini.
                */
                $evaluation = Evaluation::create([
                    'counseling_session_id' => $request->counseling_session_id,

                    'evaluation_topic_id' => $request->evaluation_topic_id,

                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'total_score' => $totalScore,
                    'percentage' => $percentage,
                    'category' => $category,
                    'interpretation' => $interpretation,
                ]);

                $message = 'Hasil evaluasi berhasil disimpan.';
                $statusCode = 201;
            }

            /*
            |--------------------------------------------------------------------------
            | 8. SIMPAN DETAIL JAWABAN
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

            /*
            |--------------------------------------------------------------------------
            | 9. UPDATE STATUS SESI KONSELING
            |--------------------------------------------------------------------------

            */
            $counselingSession->update([
                'status' => 'ongoing',
            ]);

            // Simpan seluruh perubahan ke database
            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | 10. RESPONSE SUKSES
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'evaluation_id' => $evaluation->id,
                    'counseling_session_id' => $counselingSession->id,

                    'topic' => $topic->topic,

                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'wrong_answers' => $totalQuestions - $correctAnswers,

                    'total_score' => $totalScore,
                    'percentage' => $percentage,
                    'category' => $category,
                    'interpretation' => $evaluation->interpretation,

                    'counseling_status' => $counselingSession->status,
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
