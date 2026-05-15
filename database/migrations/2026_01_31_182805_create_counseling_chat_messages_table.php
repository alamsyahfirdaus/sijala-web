<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_chat_messages', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik pesan chat

            // =========================
            // RELASI CHAT
            // =========================
            $table->unsignedBigInteger('counseling_chat_id');
            // Ruang chat tempat pesan dikirim

            // =========================
            // PENGIRIM PESAN
            // =========================
            $table->unsignedBigInteger('sender_id');
            // User pengirim pesan (konselor atau konseli)

            $table->enum('sender_role', ['konselor', 'konseli']);
            // Peran pengirim pesan

            // =========================
            // ISI PESAN
            // =========================
            $table->text('message');
            // Isi pesan teks

            // =========================
            // STATUS PESAN
            // =========================
            $table->boolean('is_read')->default(false);
            // Status pesan:
            // false = belum dibaca
            // true  = sudah dibaca

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY (AMAN MYSQL)
            // =========================
            $table->foreign(
                'counseling_chat_id',
                'fk_chat_message_chat'
            )->references('id')
             ->on('counseling_chats')
             ->cascadeOnDelete();

            $table->foreign(
                'sender_id',
                'fk_chat_message_sender'
            )->references('id')
             ->on('users')
             ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_chat_messages');
    }
};
