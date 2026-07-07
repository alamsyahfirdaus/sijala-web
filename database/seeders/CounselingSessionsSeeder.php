<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounselingSessionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('counseling_sessions')->insert([
            [
                // Relasi ke data lansia pada tabel elderly_counselee
                'elderly_counselee_id' => 1,

                // Relasi ke user konselor pada tabel users
                // Pastikan ID 3 adalah user dengan role konselor
                'counselor_id' => 3,

                // Jenis layanan konseling
                // chat = konseling melalui pesan teks
                // video = konseling melalui video call
                'service_mode' => 'chat',

                // Status sesi
                // ongoing   = sesi masih berlangsung
                // completed = sesi telah selesai
                'status' => 'ongoing',

                // Timestamp
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}