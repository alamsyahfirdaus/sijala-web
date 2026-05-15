<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElderlySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('elderly_counselee')->insert([
            [
                'counselee_id' => 3,
                'care_duration_months' => 24,
                'elderly_name' => 'Ahmad Sudrajat',
                'elderly_gender' => 'L',
                'elderly_age' => 70,
                // 'health_problems' => 'Diabetes ringan',
                // 'has_fallen' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}