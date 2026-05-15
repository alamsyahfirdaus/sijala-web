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

            // =========================
            // VIDEO EDUKASI
            // =========================
            [
                'title' => 'Pencegahan Risiko Jatuh pada Lansia',
                'category' => 'video',
                'file_path' => 'education/videos/pencegahan_risiko_jatuh.mp4',
                'description' => 'Video edukasi mengenai langkah-langkah pencegahan risiko jatuh pada lansia di lingkungan rumah.',
            ],
            [
                'title' => 'Latihan Keseimbangan untuk Lansia',
                'category' => 'video',
                'file_path' => 'education/videos/latihan_keseimbangan_lansia.mp4',
                'description' => 'Panduan latihan sederhana untuk meningkatkan keseimbangan dan kekuatan lansia.',
            ],

            // =========================
            // POSTER EDUKASI
            // =========================
            [
                'title' => 'Poster Lingkungan Rumah Aman bagi Lansia',
                'category' => 'poster',
                'file_path' => 'lingkungan_aman_lansia.jpg',
                'description' => 'Poster edukasi tentang pengaturan lingkungan rumah agar aman dan ramah lansia.',
            ],
            [
                'title' => 'Poster Tips Mencegah Jatuh',
                'category' => 'poster',
                'file_path' => 'tips_mencegah_jatuh.jpg',
                'description' => 'Poster berisi tips praktis pencegahan jatuh pada lansia dalam aktivitas sehari-hari.',
            ],
        ];

        foreach ($data as $item) {
            DB::table('education_contents')->insert([
                'title' => $item['title'],
                'category' => $item['category'],
                'file_path' => $item['file_path'],
                'description' => $item['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
