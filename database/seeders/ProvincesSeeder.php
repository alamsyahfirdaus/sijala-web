<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        DB::table('provinces')->insert([
            [
                'code' => '31',
                'name' => 'DKI Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '32',
                'name' => 'Jawa Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '33',
                'name' => 'Jawa Tengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
