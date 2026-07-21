<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ASurveyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Jalan'],
            ['name' => 'Jembatan'],
            ['name' => 'Drainase'],
            ['name' => 'Lampu Jalan'],
            ['name' => 'Trotoar'],
            ['name' => 'Rambu Lalu Lintas'],
            ['name' => 'Marka Jalan'],
            ['name' => 'Taman Kota'],
            ['name' => 'Gedung Pemerintah'],
            ['name' => 'Sekolah'],
            ['name' => 'Puskesmas'],
            ['name' => 'Tempat Ibadah'],
            ['name' => 'Pasar'],
            ['name' => 'Terminal'],
            ['name' => 'Halte'],
            ['name' => 'Saluran Air'],
            ['name' => 'Tempat Sampah'],
            ['name' => 'Fasilitas Olahraga'],
            ['name' => 'Area Parkir'],
            ['name' => 'Lainnya'],
        ];

        DB::table('a_survey_categories')->upsert(
            $categories,
            ['name'],   // Kolom unik
            ['name']    // Kolom yang diupdate jika sudah ada
        );
    }
}