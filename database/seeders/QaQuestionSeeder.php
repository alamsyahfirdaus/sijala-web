<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QaQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // INSERT QUESTIONS
        // =========================
        DB::table('qa_questions')->insert([
            [
                'id' => 1,
                'title' => 'Lansia sering pusing',
                'question' => 'Orang tua saya sering merasa pusing saat bangun tidur, apakah ini berbahaya?',
                'status' => 'open',
                'user_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'title' => 'Cara mencegah jatuh pada lansia',
                'question' => 'Bagaimana cara mencegah risiko jatuh pada lansia di rumah?',
                'status' => 'answered',
                'user_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'title' => 'Lansia sulit tidur',
                'question' => 'Apa yang menyebabkan lansia sulit tidur di malam hari?',
                'status' => 'open',
                'user_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'title' => 'Nafsu makan menurun',
                'question' => 'Kenapa lansia sering kehilangan nafsu makan?',
                'status' => 'answered',
                'user_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'title' => 'Nyeri sendi pada lansia',
                'question' => 'Apa penyebab nyeri sendi pada lansia dan bagaimana cara mengatasinya?',
                'status' => 'open',
                'user_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // =========================
        // INSERT ANSWERS
        // =========================
        DB::table('qa_answers')->insert([
            [
                'qa_question_id' => 2,
                'answer' => 'Untuk mencegah jatuh, pastikan lantai tidak licin, gunakan alas kaki anti slip, pasang pegangan di kamar mandi, dan pastikan pencahayaan cukup.',
                'user_id' => 2, // konselor
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'qa_question_id' => 4,
                'answer' => 'Penurunan nafsu makan pada lansia bisa disebabkan oleh faktor usia, gangguan kesehatan, atau efek obat. Disarankan memberikan makanan bergizi dalam porsi kecil namun sering.',
                'user_id' => 2, // konselor
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // =========================
        // SYNC STATUS (opsional safety)
        // =========================
        DB::table('qa_questions')
            ->whereIn('id', [2, 4])
            ->update([
                'status' => 'answered',
                'updated_at' => Carbon::now(),
            ]);
    }
}