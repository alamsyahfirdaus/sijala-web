<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounselingChatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil satu data sesi konseling
        $session = DB::table('counseling_sessions')->first();

        // Jika belum ada data sesi konseling, hentikan proses
        if (!$session) {
            return;
        }

        // Cek apakah chat untuk sesi tersebut sudah ada
        $exists = DB::table('counseling_chats')
            ->where('counseling_session_id', $session->id)
            ->exists();

        // Jika belum ada, tambahkan data chat
        if (!$exists) {
            DB::table('counseling_chats')->insert([
                'counseling_session_id' => $session->id,
                'status'                => 'active',
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }
    }
}