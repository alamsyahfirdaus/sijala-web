<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounselingResumeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==================================================
        // TIMESTAMP
        // ==================================================
        $now = now();

        // ==================================================
        // KATEGORI
        // ==================================================
        $interaksiAwal = DB::table('counseling_resume_options')->insertGetId([
            'category_id' => null,
            'title' => 'Interaksi Awal',
            'description' => null,
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $skriningAwal = DB::table('counseling_resume_options')->insertGetId([
            'category_id' => null,
            'title' => 'Skrining Awal',
            'description' => null,
            'sort_order' => 2,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $persiapan = DB::table('counseling_resume_options')->insertGetId([
            'category_id' => null,
            'title' => 'Persiapan Konseling',
            'description' => null,
            'sort_order' => 3,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $pelaksanaan = DB::table('counseling_resume_options')->insertGetId([
            'category_id' => null,
            'title' => 'Pelaksanaan Konseling',
            'description' => null,
            'sort_order' => 4,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $edukasi = DB::table('counseling_resume_options')->insertGetId([
            'category_id' => null,
            'title' => 'Edukasi',
            'description' => null,
            'sort_order' => 5,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $evaluasi = DB::table('counseling_resume_options')->insertGetId([
            'category_id' => null,
            'title' => 'Evaluasi',
            'description' => null,
            'sort_order' => 6,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $skriningAkhir = DB::table('counseling_resume_options')->insertGetId([
            'category_id' => null,
            'title' => 'Skrining Akhir',
            'description' => null,
            'sort_order' => 7,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ==================================================
        // ITEM
        // ==================================================
        DB::table('counseling_resume_options')->insert([

            // ================= INTERAKSI AWAL =================
            [
                'category_id' => $interaksiAwal,
                'title' => 'Interaksi awal (bina trust)',
                'description' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $interaksiAwal,
                'title' => 'Menjelaskan tujuan konseling',
                'description' => null,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ================= SKRINING AWAL =================
            [
                'category_id' => $skriningAwal,
                'title' => 'Melakukan skrining risiko jatuh',
                'description' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $skriningAwal,
                'title' => 'Melakukan asesmen kemandirian kesehatan keluarga',
                'description' => null,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $skriningAwal,
                'title' => 'Mengidentifikasi masalah lain dalam merawat lansia',
                'description' => null,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ================= PERSIAPAN =================
            [
                'category_id' => $persiapan,
                'title' => 'Membuat kontrak waktu konseling',
                'description' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ================= PELAKSANAAN =================
            [
                'category_id' => $pelaksanaan,
                'title' => 'Melakukan konseling melalui chat',
                'description' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $pelaksanaan,
                'title' => 'Melakukan konseling melalui video',
                'description' => null,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $pelaksanaan,
                'title' => 'Melakukan konseling melalui telepon / WhatsApp',
                'description' => null,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $pelaksanaan,
                'title' => 'Menentukan masalah yang dihadapi keluarga',
                'description' => null,
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $pelaksanaan,
                'title' => 'Mencari alternatif solusi',
                'description' => null,
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $pelaksanaan,
                'title' => 'Memberikan edukasi dan dukungan (support)',
                'description' => null,
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ================= EDUKASI =================
            [
                'category_id' => $edukasi,
                'title' => 'Edukasi pencegahan jatuh pada lansia',
                'sort_order' => 1,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $edukasi,
                'title' => 'Edukasi masalah psikologis dalam merawat lansia',
                'sort_order' => 2,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $edukasi,
                'title' => 'Edukasi komunikasi dengan lansia',
                'sort_order' => 3,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $edukasi,
                'title' => 'Edukasi penggunaan alat bantu jalan yang benar',
                'sort_order' => 4,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $edukasi,
                'title' => 'Edukasi latihan keseimbangan (Otago)',
                'sort_order' => 5,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $edukasi,
                'title' => 'Edukasi WSP',
                'sort_order' => 6,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ================= EVALUASI =================
            [
                'category_id' => $evaluasi,
                'title' => 'Eksplorasi perasaan keluarga',
                'sort_order' => 1,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $evaluasi,
                'title' => 'Evaluasi pengetahuan pencegahan jatuh',
                'sort_order' => 2,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $evaluasi,
                'title' => 'Evaluasi pengetahuan masalah psikologis dalam merawat lansia',
                'sort_order' => 3,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $evaluasi,
                'title' => 'Evaluasi pengetahuan komunikasi dengan lansia',
                'sort_order' => 4,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $evaluasi,
                'title' => 'Evaluasi pengetahuan latihan keseimbangan (Otago)',
                'sort_order' => 5,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $evaluasi,
                'title' => 'Evaluasi pengetahuan penggunaan alat bantu jalan',
                'sort_order' => 6,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ================= SKRINING AKHIR =================
            [
                'category_id' => $skriningAkhir,
                'title' => 'Melakukan skrining risiko jatuh akhir',
                'sort_order' => 1,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $skriningAkhir,
                'title' => 'Melakukan asesmen kemandirian kesehatan keluarga akhir',
                'sort_order' => 2,
                'description' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}