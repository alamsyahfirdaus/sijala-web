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
                'question'    => 'Saya pernah jatuh dalam 6 bulan terakhir.',
                'answer_type' => 'yes_no',
                'score_yes'   => 2,
                'score_no'    => 0,
                'order'       => 1,
            ],
            [
                'question'    => 'Saya menggunakan atau disarankan menggunakan tongkat atau alat bantu jalan.',
                'answer_type' => 'yes_no',
                'score_yes'   => 2,
                'score_no'    => 0,
                'order'       => 2,
            ],
            [
                'question'    => 'Saya kadang merasa tidak stabil saat berjalan.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 3,
            ],
            [
                'question'    => 'Saya berpegangan pada furnitur saat berjalan di rumah.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 4,
            ],
            [
                'question'    => 'Saya khawatir akan jatuh.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 5,
            ],
            [
                'question'    => 'Saya perlu mendorong dengan tangan untuk berdiri dari kursi.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 6,
            ],
            [
                'question'    => 'Saya kesulitan saat naik ke trotoar atau anak tangga.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 7,
            ],
            [
                'question'    => 'Saya sering terburu-buru ke kamar mandi.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 8,
            ],
            [
                'question'    => 'Saya mengalami penurunan rasa pada kaki.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 9,
            ],
            [
                'question'    => 'Obat yang saya konsumsi membuat saya pusing atau lebih lelah.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 10,
            ],
            [
                'question'    => 'Saya mengonsumsi obat untuk tidur atau suasana hati.',
                'answer_type' => 'yes_no',
                'score_yes'   => 1,
                'score_no'    => 0,
                'order'       => 11,
            ],
            [
                'question'    => 'Saya sering merasa sedih atau depresi.',
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