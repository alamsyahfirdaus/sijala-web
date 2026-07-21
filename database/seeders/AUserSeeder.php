<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111111',
                'photo' => null,
                'role' => 'admin',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111112',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Siti Aisyah',
                'email' => 'siti@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111113',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111114',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Rina Marlina',
                'email' => 'rina@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111115',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Dedi Kurniawan',
                'email' => 'dedi@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111116',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Lina Fitriani',
                'email' => 'lina@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111117',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Andi Saputra',
                'email' => 'andi@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111118',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Nanda Putri',
                'email' => 'nanda@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111119',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Rizky Maulana',
                'email' => 'rizky@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111120',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Fajar Nugraha',
                'email' => 'fajar@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111121',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111122',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Yoga Pratama',
                'email' => 'yoga@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111123',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Nur Hasanah',
                'email' => 'nur@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111124',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111125',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Putri Amelia',
                'email' => 'putri@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111126',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Ilham Ramadhan',
                'email' => 'ilham@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111127',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Rani Oktavia',
                'email' => 'rani@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111128',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Galih Prakoso',
                'email' => 'galih@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111129',
                'photo' => null,
                'role' => 'user',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya@example.com',
                'password' => Hash::make('123456'),
                'phone' => '081111111130',
                'photo' => null,
                'role' => 'user',
            ],
        ];

        // Tambahkan field otomatis
        foreach ($users as &$user) {
            $user['remember_token'] = null;
            $user['created_at'] = $now;
            $user['updated_at'] = $now;
        }

        // Tidak perlu truncate jika menggunakan migrate:fresh --seed
        DB::table('a_users')->insert($users);
    }
}