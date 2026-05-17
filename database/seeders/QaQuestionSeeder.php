<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QaQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // =========================
        // INSERT QUESTIONS
        // =========================
        DB::table('qa_questions')->insert([
            [
                'id' => 1,
                'title' => 'Pengertian Jatuh pada Lansia',
                'question' => 'Apa yang dimaksud dengan jatuh pada lansia?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'title' => 'Bahaya Jatuh pada Lansia',
                'question' => 'Mengapa jatuh pada lansia menjadi masalah serius?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'title' => 'Risiko Jatuh pada Semua Lansia',
                'question' => 'Apakah semua lansia berisiko jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'title' => 'Tanda Risiko Tinggi Jatuh',
                'question' => 'Apa tanda lansia berisiko tinggi jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'title' => 'Penyebab Utama Jatuh pada Lansia',
                'question' => 'Apa penyebab utama jatuh pada lansia?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'title' => 'Pengaruh Faktor Fisik',
                'question' => 'Bagaimana faktor fisik memengaruhi risiko jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'title' => 'Obat-obatan dan Risiko Jatuh',
                'question' => 'Apakah obat-obatan bisa menyebabkan jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'title' => 'Pengaruh Kondisi Lingkungan',
                'question' => 'Bagaimana kondisi lingkungan berpengaruh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'title' => 'Penyakit yang Meningkatkan Risiko Jatuh',
                'question' => 'Apakah penyakit tertentu meningkatkan risiko jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'title' => 'Faktor Psikologis dan Risiko Jatuh',
                'question' => 'Apakah faktor psikologis berpengaruh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'title' => 'Pencegahan Jatuh di Rumah',
                'question' => 'Bagaimana cara mencegah jatuh pada lansia di rumah?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'title' => 'Pentingnya Olahraga',
                'question' => 'Apakah olahraga penting untuk mencegah jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'title' => 'Penggunaan Alat Bantu Jalan',
                'question' => 'Seberapa penting penggunaan alat bantu jalan?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 14,
                'title' => 'Peran Keluarga',
                'question' => 'Bagaimana peran keluarga dalam pencegahan jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 15,
                'title' => 'Pentingnya Pemeriksaan Rutin',
                'question' => 'Apakah pemeriksaan kesehatan rutin diperlukan?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 16,
                'title' => 'Pencahayaan Rumah yang Aman',
                'question' => 'Bagaimana mengatur pencahayaan rumah yang aman?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 17,
                'title' => 'Alas Kaki yang Aman',
                'question' => 'Apa alas kaki yang aman untuk lansia?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 18,
                'title' => 'Pencegahan Jatuh di Kamar Mandi',
                'question' => 'Bagaimana mencegah jatuh saat di kamar mandi?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 19,
                'title' => 'Tindakan Setelah Pernah Jatuh',
                'question' => 'Apa yang harus dilakukan jika lansia sudah pernah jatuh?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 20,
                'title' => 'Kapan Harus ke Tenaga Kesehatan',
                'question' => 'Kapan harus mencari bantuan tenaga kesehatan?',
                'status' => 'answered',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // =========================
        // INSERT ANSWERS
        // =========================
        DB::table('qa_answers')->insert([
            [
                'qa_question_id' => 1,
                'answer' => 'Jatuh adalah kejadian ketika seseorang tidak sengaja kehilangan keseimbangan dan berpindah ke posisi lebih rendah, seperti lantai, baik dengan atau tanpa cedera.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 2,
                'answer' => 'Jatuh dapat menyebabkan cedera seperti patah tulang, trauma kepala, penurunan kemandirian, hingga meningkatkan risiko kematian.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 3,
                'answer' => 'Ya, semua lansia memiliki risiko jatuh, namun tingkat risikonya berbeda tergantung kondisi kesehatan, lingkungan, dan aktivitas.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 4,
                'answer' => 'Tanda risiko tinggi jatuh antara lain sering pusing, berjalan tidak stabil, pernah jatuh sebelumnya, atau membutuhkan alat bantu berjalan.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 5,
                'answer' => 'Penyebabnya multifaktor, seperti gangguan keseimbangan, kelemahan otot, penyakit kronis, dan faktor lingkungan.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 6,
                'answer' => 'Penurunan kekuatan otot, penglihatan kabur, dan gangguan saraf dapat membuat lansia mudah kehilangan keseimbangan.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 7,
                'answer' => 'Ya, beberapa obat seperti penenang, antihipertensi, atau obat tidur dapat menyebabkan pusing atau mengantuk.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 8,
                'answer' => 'Lantai licin, pencahayaan buruk, dan barang berserakan dapat meningkatkan risiko tersandung atau terpeleset.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 9,
                'answer' => 'Ya, penyakit seperti stroke, diabetes, arthritis, dan gangguan jantung dapat meningkatkan risiko jatuh.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 10,
                'answer' => 'Takut jatuh justru dapat membuat lansia lebih kaku saat berjalan dan meningkatkan risiko jatuh.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 11,
                'answer' => 'Menjaga rumah tetap rapi, memasang pegangan di kamar mandi, dan memastikan pencahayaan cukup dapat membantu mencegah jatuh.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 12,
                'answer' => 'Sangat penting. Latihan keseimbangan dan kekuatan otot seperti senam lansia dapat mengurangi risiko jatuh.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 13,
                'answer' => 'Alat bantu seperti tongkat atau walker sangat membantu meningkatkan stabilitas saat berjalan.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 14,
                'answer' => 'Keluarga dapat mengawasi aktivitas lansia, membantu kebutuhan sehari-hari, dan memastikan lingkungan tetap aman.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 15,
                'answer' => 'Ya, pemeriksaan rutin diperlukan untuk memantau tekanan darah, penglihatan, dan efek samping obat.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 16,
                'answer' => 'Gunakan lampu yang cukup terang, terutama di malam hari dan pada area seperti tangga dan kamar mandi.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 17,
                'answer' => 'Gunakan sepatu atau sandal dengan sol tidak licin dan pas di kaki.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 18,
                'answer' => 'Gunakan alas anti-slip, pegangan dinding, dan hindari lantai basah di kamar mandi.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 19,
                'answer' => 'Segera evaluasi penyebab jatuh, konsultasikan ke tenaga kesehatan, dan lakukan langkah pencegahan ulang.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'qa_question_id' => 20,
                'answer' => 'Cari bantuan tenaga kesehatan jika lansia sering jatuh, mengalami cedera, atau menunjukkan perubahan keseimbangan dan kesadaran.',
                'user_id' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // =========================
        // SYNC STATUS
        // =========================
        DB::table('qa_questions')->update([
            'status' => 'answered',
            'updated_at' => $now,
        ]);
    }
}