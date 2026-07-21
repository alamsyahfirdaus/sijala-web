<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ASurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surveys = [
            [
                'user_id' => 2,
                'category_id' => 1,
                'title' => 'Jalan Berlubang di Jalan Ahmad Yani',
                'description' => 'Ditemukan jalan berlubang yang dapat membahayakan pengguna jalan.',
                'photo' => 'survey/jalan1.jpg',
                'latitude' => -7.3505800,
                'longitude' => 108.2171600,
                'address' => 'Jl. Ahmad Yani, Tasikmalaya',
                'qr_code' => 'QR0001',
                'status' => 'selesai',
            ],
            [
                'user_id' => 3,
                'category_id' => 2,
                'title' => 'Retakan pada Jembatan Ciwulan',
                'description' => 'Retakan kecil ditemukan pada sisi jembatan.',
                'photo' => 'survey/jembatan1.jpg',
                'latitude' => -7.3452100,
                'longitude' => 108.2245600,
                'address' => 'Jembatan Ciwulan',
                'qr_code' => 'QR0002',
                'status' => 'draft',
            ],
            [
                'user_id' => 4,
                'category_id' => 3,
                'title' => 'Drainase Tersumbat',
                'description' => 'Saluran drainase dipenuhi sampah sehingga air tidak mengalir.',
                'photo' => 'survey/drainase1.jpg',
                'latitude' => -7.3412000,
                'longitude' => 108.2105000,
                'address' => 'Jl. KHZ Mustofa',
                'qr_code' => 'QR0003',
                'status' => 'selesai',
            ],
            [
                'user_id' => 5,
                'category_id' => 4,
                'title' => 'Lampu Jalan Mati',
                'description' => 'Lampu penerangan jalan tidak menyala pada malam hari.',
                'photo' => 'survey/lampu1.jpg',
                'latitude' => -7.3388000,
                'longitude' => 108.2235000,
                'address' => 'Jl. HZ Mustofa',
                'qr_code' => 'QR0004',
                'status' => 'draft',
            ],
            [
                'user_id' => 6,
                'category_id' => 5,
                'title' => 'Trotoar Rusak',
                'description' => 'Trotoar mengalami kerusakan pada beberapa titik.',
                'photo' => 'survey/trotoar1.jpg',
                'latitude' => -7.3399000,
                'longitude' => 108.2157000,
                'address' => 'Jl. Sutisna Senjaya',
                'qr_code' => 'QR0005',
                'status' => 'selesai',
            ],
        ];

        // Tambahkan data hingga menjadi 20 record
        for ($i = 6; $i <= 20; $i++) {

            $surveys[] = [
                'user_id' => rand(2, 20),
                'category_id' => rand(1, 20),
                'title' => "Survey Lapangan #{$i}",
                'description' => "Hasil survey lapangan ke-{$i}. Ditemukan beberapa kondisi yang memerlukan tindak lanjut.",
                'photo' => "survey/photo{$i}.jpg",
                'latitude' => -7.3500000 + ($i * 0.001),
                'longitude' => 108.2200000 + ($i * 0.001),
                'address' => "Lokasi Survey {$i}, Kota Tasikmalaya",
                'qr_code' => "QR" . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => rand(0,1) ? 'draft' : 'selesai',
            ];
        }

        foreach ($surveys as $survey) {

            DB::table('a_surveys')->updateOrInsert(

                [
                    'title' => $survey['title']
                ],

                array_merge($survey, [

                    'created_at' => now(),
                    'updated_at' => now(),

                ])

            );

        }
    }
}