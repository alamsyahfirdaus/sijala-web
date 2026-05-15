<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regencies')->insert([
            [
                'code' => '3277',
                'name' => 'Kota Cimahi',
                'province_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3206',
                'name' => 'Kabupaten Tasikmalaya',
                'province_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3278',
                'name' => 'Kota Tasikmalaya',
                'province_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}