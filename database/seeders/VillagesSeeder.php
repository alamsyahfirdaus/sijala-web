<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | KOTA CIMAHI
        |--------------------------------------------------------------------------
        */

        // =====================
        // CIMAHI UTARA
        // =====================
        $utara = DB::table('districts')->where('name', 'Cimahi Utara')->first();
        if ($utara) {
            DB::table('villages')->insert([
                ['district_id' => $utara->id, 'code' => null, 'name' => 'Cipageran', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $utara->id, 'code' => null, 'name' => 'Citeureup', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $utara->id, 'code' => null, 'name' => 'Cibabat', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $utara->id, 'code' => null, 'name' => 'Pasirkaliki', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // =====================
        // CIMAHI TENGAH
        // =====================
        $tengah = DB::table('districts')->where('name', 'Cimahi Tengah')->first();
        if ($tengah) {
            DB::table('villages')->insert([
                ['district_id' => $tengah->id, 'code' => null, 'name' => 'Baros', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $tengah->id, 'code' => null, 'name' => 'Cigugur Tengah', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $tengah->id, 'code' => null, 'name' => 'Karangmekar', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $tengah->id, 'code' => null, 'name' => 'Setiamanah', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $tengah->id, 'code' => null, 'name' => 'Cimahi', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $tengah->id, 'code' => null, 'name' => 'Padasuka', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // =====================
        // CIMAHI SELATAN
        // =====================
        $selatan = DB::table('districts')->where('name', 'Cimahi Selatan')->first();
        if ($selatan) {
            DB::table('villages')->insert([
                ['district_id' => $selatan->id, 'code' => null, 'name' => 'Cibeber', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $selatan->id, 'code' => null, 'name' => 'Leuwigajah', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $selatan->id, 'code' => null, 'name' => 'Utama', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $selatan->id, 'code' => null, 'name' => 'Melong', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $selatan->id, 'code' => null, 'name' => 'Cibeureum', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | KOTA TASIKMALAYA
        |--------------------------------------------------------------------------
        */

        // =====================
        // CIBEUREUM
        // =====================
        $cibeureum = DB::table('districts')->where('name', 'Cibeureum')->first();
        if ($cibeureum) {
            DB::table('villages')->insert([
                ['district_id' => $cibeureum->id, 'code' => null, 'name' => 'Ciherang', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $cibeureum->id, 'code' => null, 'name' => 'Setiawargi', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $cibeureum->id, 'code' => null, 'name' => 'Kersamenak', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // =====================
        // TAWANG
        // =====================
        $tawang = DB::table('districts')->where('name', 'Tawang')->first();
        if ($tawang) {
            DB::table('villages')->insert([
                ['district_id' => $tawang->id, 'code' => null, 'name' => 'Tawangsari', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $tawang->id, 'code' => null, 'name' => 'Lengkongsari', 'created_at' => now(), 'updated_at' => now()],
                ['district_id' => $tawang->id, 'code' => null, 'name' => 'Cikalang', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
