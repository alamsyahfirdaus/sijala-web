<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounselorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | DATA KONSELOR
        |--------------------------------------------------------------------------
        | Catatan:
        | - user harus sudah ada di tabel users
        | - puskesmas harus sudah ada di tabel puskesmas
        */

        // Ambil beberapa puskesmas (contoh)
        $puskesmasCimahiSelatan = DB::table('puskesmas')->where('name', 'Cimahi Selatan')->first();
        $puskesmasCimahiTengah  = DB::table('puskesmas')->where('name', 'Cimahi Tengah')->first();

        // Ambil user konselor
        $konselor1 = DB::table('users')->where('username', 'konselor')->first();
        $konselor2 = DB::table('users')->where('username', 'konselor2')->first();

        if ($konselor1 && $puskesmasCimahiSelatan) {
            DB::table('counselors')->insert([
                'user_id' => $konselor1->id,
                'puskesmas_id' => $puskesmasCimahiSelatan->id,
                'registration_number' => null,
                'specialization' => null,
                'education' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // if ($konselor2 && $puskesmasCimahiTengah) {
        //     DB::table('counselors')->insert([
        //         'user_id' => $konselor2->id,
        //         'puskesmas_id' => $puskesmasCimahiTengah->id,
        //         'registration_number' => 'STR-320002',
        //         'specialization' => 'Bidan',
        //         'education' => 'D3 Kebidanan',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
