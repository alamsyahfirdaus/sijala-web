<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================
        // Kota Cimahi
        // =========================
        $cimahi = DB::table('regencies')->where('code', '3277')->first();

        if ($cimahi) {
            DB::table('districts')->insert([
                [
                    'regency_id' => $cimahi->id,
                    'code' => '3277010',
                    'name' => 'Cimahi Utara',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $cimahi->id,
                    'code' => '3277020',
                    'name' => 'Cimahi Tengah',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $cimahi->id,
                    'code' => '3277030',
                    'name' => 'Cimahi Selatan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // =========================
        // Kota Tasikmalaya
        // =========================
        $tasikmalaya = DB::table('regencies')->where('code', '3278')->first();

        if ($tasikmalaya) {
            DB::table('districts')->insert([
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278010',
                    'name' => 'Bungursari',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278020',
                    'name' => 'Cibeureum',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278030',
                    'name' => 'Cipedes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278040',
                    'name' => 'Indihiang',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278050',
                    'name' => 'Kawalu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278060',
                    'name' => 'Mangkubumi',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278070',
                    'name' => 'Purbaratu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278080',
                    'name' => 'Tamansari',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'regency_id' => $tasikmalaya->id,
                    'code' => '3278090',
                    'name' => 'Tawang',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
