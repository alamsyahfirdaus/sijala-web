<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounselingChatMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data chat pertama
        $chat = DB::table('counseling_chats')->first();

        // Jika data chat belum tersedia, hentikan proses
        if (!$chat) {
            return;
        }

        // Ambil user konselor
        $konselor = DB::table('users')
            ->where('role', 'konselor')
            ->first();

        // Ambil user konseli/pasien
        $konseli = DB::table('users')
            ->where('role', 'konseli')
            ->first();

        // Jika salah satu user tidak ditemukan, hentikan proses
        if (!$konselor || !$konseli) {
            return;
        }

        // Cek apakah pesan untuk chat ini sudah ada
        $exists = DB::table('counseling_chat_messages')
            ->where('counseling_chat_id', $chat->id)
            ->exists();

        // Jika sudah ada, hentikan proses
        if ($exists) {
            return;
        }

        // Data percakapan contoh
        $messages = [
            [
                'sender_id'   => $konseli->id,
                'sender_role' => 'konseli',
                'message'     => 'Assalamu\'alaikum, saya ingin berkonsultasi mengenai kondisi orang tua saya yang akhir-akhir ini sering hampir terjatuh.',
                'is_read'     => true,
            ],
            [
                'sender_id'   => $konselor->id,
                'sender_role' => 'konselor',
                'message'     => 'Wa\'alaikumsalam. Tentu, silakan ceritakan kondisi lansia yang sedang Anda dampingi.',
                'is_read'     => true,
            ],
            [
                'sender_id'   => $konseli->id,
                'sender_role' => 'konseli',
                'message'     => 'Usia beliau 70 tahun dan sering kehilangan keseimbangan saat berjalan, terutama setelah bangun tidur.',
                'is_read'     => true,
            ],
            [
                'sender_id'   => $konselor->id,
                'sender_role' => 'konselor',
                'message'     => 'Apakah beliau memiliki riwayat jatuh sebelumnya atau menggunakan alat bantu berjalan?',
                'is_read'     => true,
            ],
            [
                'sender_id'   => $konseli->id,
                'sender_role' => 'konseli',
                'message'     => 'Ya, sekitar dua bulan lalu beliau sempat terpeleset di kamar mandi.',
                'is_read'     => true,
            ],
            [
                'sender_id'   => $konselor->id,
                'sender_role' => 'konselor',
                'message'     => 'Baik. Saya sarankan memasang pegangan di kamar mandi, menggunakan alas anti-slip, dan memastikan pencahayaan rumah cukup terang.',
                'is_read'     => true,
            ],
            [
                'sender_id'   => $konseli->id,
                'sender_role' => 'konseli',
                'message'     => 'Baik, terima kasih. Apakah ada latihan yang bisa dilakukan untuk meningkatkan keseimbangan?',
                'is_read'     => true,
            ],
            [
                'sender_id'   => $konselor->id,
                'sender_role' => 'konselor',
                'message'     => 'Ada. Lansia dapat melakukan latihan berdiri satu kaki dengan bantuan pegangan, jalan tumit-ke-jari, dan senam ringan secara rutin.',
                'is_read'     => false,
            ],
        ];

        // Simpan semua pesan ke database
        foreach ($messages as $message) {
            DB::table('counseling_chat_messages')->insert([
                'counseling_chat_id' => $chat->id,
                'sender_id'          => $message['sender_id'],
                'sender_role'        => $message['sender_role'],
                'message'            => $message['message'],
                'is_read'            => $message['is_read'],
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }
}