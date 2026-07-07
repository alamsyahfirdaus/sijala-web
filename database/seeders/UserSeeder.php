<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // =====================
            // ADMIN
            // =====================
            [
                'name' => 'Alamsyah Firdaus',
                'username' => 'alamsyahfirdaus',
                'email' => 'alamsyah.firdaus.af31@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'phone' => NULL,
                'gender' => NULL,
                'birth_place' => NULL,
                'birth_date' => NULL,
                'puskesmas_id' => NULL,
                'is_active' => true,
                'created_at' => NULL,
                'updated_at' => NULL,
            ],

            [
                'name' => 'Admin Jaga Lansia',
                'username' => 'admin',
                'email' => 'admin@jagalansia.id',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'phone' => NULL,
                'gender' => NULL,
                'birth_place' => NULL,
                'birth_date' => NULL,
                'puskesmas_id' => NULL,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // KONSELOR
            // =====================
            [
                'name' => 'Konselor Puskesmas',
                'username' => 'konselor',
                'email' => 'konselor@jagalansia.id',
                'password' => Hash::make('123456'),
                'role' => 'konselor',
                'phone' => NULL,
                'gender' => NULL,
                'birth_place' => NULL,
                'birth_date' => NULL,
                'puskesmas_id' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // KONSELI
            // =====================
            [
                'name' => 'Keluarga Lansia',
                'username' => 'konseli',
                'email' => 'konseli@jagalansia.id',
                'password' => Hash::make('123456'),
                'role' => 'konseli',
                'phone' => NULL,
                'gender' => NULL,
                'birth_place' => NULL,
                'birth_date' => NULL,
                'puskesmas_id' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
