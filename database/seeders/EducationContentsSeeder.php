<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            // ======================================================
            // MATERI EDUKASI PENCEGAHAN RISIKO JATUH PADA LANSIA
            // ======================================================

            // ======================================================
            // 1. POSTER EDUKASI (MATERI DASAR)
            // ======================================================

            [
                'title' => 'Pencegahan Jatuh pada Lansia',
                'category' => 'poster',
                'file_path' => 'pencegahan_jatuh.jpg',
                'description' => 'Materi edukasi mengenai faktor risiko jatuh dan langkah-langkah pencegahan yang dapat dilakukan di rumah maupun lingkungan sekitar.',
            ],

            [
                'title' => 'Penggunaan Alat Bantu Jalan yang Benar',
                'category' => 'poster',
                'file_path' => 'alat_bantu_jalan.jpg',
                'description' => 'Panduan penggunaan alat bantu jalan seperti tongkat, walker, dan kursi roda agar lansia dapat bergerak dengan aman dan mandiri.',
            ],

            // ======================================================
            // 2. VIDEO EDUKASI PENGGUNAAN ALAT BANTU
            // ======================================================

            [
                'title' => 'Cara Aman Menggunakan Tongkat untuk Lansia',
                'category' => 'video',
                'file_path' => 'https://youtu.be/Kb-YJe_OpS4?si=_Sq8b6zVnyqXL4oQ',
                'description' => 'Video panduan penggunaan tongkat yang benar untuk membantu menjaga keseimbangan dan mengurangi risiko jatuh saat berjalan.',
            ],

            [
                'title' => 'Cara Aman Menggunakan Walker untuk Lansia',
                'category' => 'video',
                'file_path' => 'https://youtu.be/ZbtdHBsXnC8?si=1iV997UFNs-Jjdbh',
                'description' => 'Video edukasi tentang cara menggunakan walker secara tepat agar lansia dapat berjalan lebih stabil dan aman.',
            ],

            [
                'title' => 'Cara Aman Menggunakan Kursi Roda untuk Lansia',
                'category' => 'video',
                'file_path' => 'https://youtu.be/1CpcOTZlka8?si=41KB6uQH9C6xBsMC',
                'description' => 'Panduan penggunaan kursi roda yang aman, termasuk teknik berpindah posisi dan langkah keselamatan saat digunakan.',
            ],

            // ======================================================
            // 3. VIDEO EDUKASI LATIHAN KESEIMBANGAN
            // ======================================================

            [
                'title' => 'Latihan Keseimbangan untuk Mencegah Risiko Jatuh pada Lansia',
                'category' => 'video',
                'file_path' => 'https://youtu.be/5UlD1n-6QqU?si=Ob8-FhRPJo3zj0v2',
                'description' => 'Video latihan sederhana untuk meningkatkan kekuatan otot, koordinasi, dan keseimbangan tubuh lansia.',
            ],

            [
                'title' => 'Latihan Keseimbangan bagi Lansia Pengguna Kursi Roda',
                'category' => 'video',
                'file_path' => 'https://youtu.be/oPG9EYbCp9w?si=SttP98VbxMXOkLAy',
                'description' => 'Latihan gerak yang dirancang khusus bagi lansia pengguna kursi roda untuk menjaga fleksibilitas dan keseimbangan tubuh.',
            ],

            // ======================================================
            // 4. EDUKASI KOMUNIKASI DAN PSIKOSOSIAL
            // ======================================================

            [
                'title' => 'Cara Berkomunikasi dengan Lansia',
                'category' => 'poster',
                'file_path' => 'komunikasi_lansia.jpg',
                'description' => 'Materi edukasi mengenai teknik komunikasi yang sabar, empatik, dan efektif untuk membangun hubungan yang baik dengan lansia.',
            ],

            [
                'title' => 'Masalah Psikologis Keluarga dalam Merawat Lansia',
                'category' => 'poster',
                'file_path' => 'psikologis_keluarga.jpg',
                'description' => 'Materi edukasi tentang tantangan emosional yang dapat dialami keluarga serta strategi menghadapi stres dalam merawat lansia.',
            ],
        ];

        foreach ($data as $item) {
            DB::table('education_contents')->updateOrInsert(
                [
                    'title' => $item['title'],
                ],
                [
                    'category'    => $item['category'],
                    'file_path'   => $item['file_path'],
                    'description' => $item['description'],
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}