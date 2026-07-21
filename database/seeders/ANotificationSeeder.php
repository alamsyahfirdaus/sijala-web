<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ANotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $notifications = [
            [
                'user_id' => 2,
                'title' => 'Selamat Datang',
                'message' => 'Selamat datang di Aplikasi Field Survey.',
                'is_read' => true,
            ],
            [
                'user_id' => 3,
                'title' => 'Survey Berhasil Disimpan',
                'message' => 'Data survey berhasil disimpan.',
                'is_read' => true,
            ],
            [
                'user_id' => 4,
                'title' => 'Survey Menunggu Review',
                'message' => 'Survey Anda sedang menunggu proses review.',
                'is_read' => false,
            ],
            [
                'user_id' => 5,
                'title' => 'Survey Selesai',
                'message' => 'Survey telah berhasil diselesaikan.',
                'is_read' => true,
            ],
            [
                'user_id' => 6,
                'title' => 'Lokasi Berhasil Tersimpan',
                'message' => 'Koordinat GPS berhasil disimpan.',
                'is_read' => false,
            ],
        ];

        // Tambahkan hingga menjadi 20 data
        for ($i = 6; $i <= 20; $i++) {

            $notifications[] = [
                'user_id' => rand(2, 20),
                'title' => "Notifikasi #{$i}",
                'message' => "Ini adalah notifikasi ke-{$i} dari sistem Field Survey.",
                'is_read' => rand(0, 1),
            ];
        }

        foreach ($notifications as &$notification) {
            $notification['created_at'] = $now;
            $notification['updated_at'] = $now;
        }

        DB::table('a_notifications')->insert($notifications);
    }
}