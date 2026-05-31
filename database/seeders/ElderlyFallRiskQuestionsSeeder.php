<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElderlyFallRiskQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [

            // ================= PERTANYAAN RISIKO JATUH LANSIA =================
            [
                'question'    => 'Apakah lansia pernah mengalami jatuh dalam 6 bulan terakhir?',
                'answer_type' => 'yes_no',
                'score_yes'   => 2,
                'score_no'    => 0,
                'order'       => 1,
            ],
            [
                'question'    => 'Apakah lansia menggunakan atau disarankan menggunakan tongkat atau alat bantu berjalan?',
                'answer_type' => 'yes_no',
                'score_yes'   => 2,
                'score_no'    => 0,
                'order'       => 2,
            ],
            [
                'question'    => 'Apakah lansia sering merasa tidak stabil saat berjalan?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 3,
            ],
            [
                'question'    => 'Apakah lansia sering berpegangan pada furnitur atau benda di sekitarnya saat berjalan di rumah?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 4,
            ],
            [
                'question'    => 'Apakah lansia merasa khawatir akan mengalami jatuh?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 5,
            ],
            [
                'question'    => 'Apakah lansia memerlukan bantuan tangan untuk berdiri dari posisi duduk?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 6,
            ],
            [
                'question'    => 'Apakah lansia mengalami kesulitan saat naik trotoar atau anak tangga?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 7,
            ],
            [
                'question'    => 'Apakah lansia sering terburu-buru menuju kamar mandi?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 8,
            ],
            [
                'question'    => 'Apakah lansia mengalami penurunan sensasi atau rasa pada kaki?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 9,
            ],
            [
                'question'    => 'Apakah obat yang dikonsumsi lansia menyebabkan pusing, mengantuk, atau mudah lelah?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 10,
            ],
            [
                'question'    => 'Apakah lansia mengonsumsi obat tidur atau obat yang memengaruhi suasana hati?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 11,
            ],
            [
                'question'    => 'Apakah lansia sering merasa sedih, murung, atau mengalami depresi?',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 12,
            ],

        ];

        foreach ($questions as $q) {
            DB::table('elderly_fall_risk_questions')->insert([
                'question'    => $q['question'],
                'answer_type' => $q['answer_type'],
                'score_yes'   => $q['score_yes'],
                'score_no'    => $q['score_no'],
                'is_active'   => true,
                'order'       => $q['order'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
