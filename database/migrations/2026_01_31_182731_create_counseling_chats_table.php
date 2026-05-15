<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_chats', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik ruang chat konseling

            // =========================
            // RELASI SESI KONSELING
            // =========================
            $table->unsignedBigInteger('counseling_session_id');
            // Satu chat untuk satu sesi konseling

            // =========================
            // STATUS CHAT
            // =========================
            $table->enum('status', ['active', 'closed'])
                ->default('active');
            // Status ruang chat:
            // active = chat masih berlangsung
            // closed = chat ditutup

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY (AMAN MYSQL)
            // =========================
            $table->foreign(
                'counseling_session_id',
                'fk_chat_session'
            )->references('id')
             ->on('counseling_sessions')
             ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_chats');
    }
};
