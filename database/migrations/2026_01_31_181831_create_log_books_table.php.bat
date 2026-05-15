<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_books', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik log book

            // =========================
            // RELASI KONSELI
            // =========================
            // $table->unsignedBigInteger('counselee_id');
            // Konseli (keluarga) yang mengisi log book
            // $table->unsignedBigInteger('counselee_id')->comment('user_id');
            // Relasi ke tabel users
            // Menunjukkan siapa konseli (keluarga) yang mengisi log book

            // =========================
            // RELASI SESI KONSELING
            // =========================
            $table->unsignedBigInteger('counseling_session_id');
            // Sesi konseling yang memberikan tugas

            $table->unsignedTinyInteger('score')->nullable()->comment('1-5');
            // =========================
            // INFORMASI LOG BOOK
            // =========================
            // $table->date('log_date');
            // Tanggal pelaksanaan tugas

            $table->text('activity')->comment('note')->nullable();
            // Deskripsi aktivitas/tugas yang dilakukan konseli

            $table->boolean('is_completed')->default(true);
            // Status tugas:
            // true  = tugas sudah dilaksanakan
            // false = belum dilaksanakan

            // $table->text('note')->nullable();
            // Catatan tambahan dari konseli atau konselor

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY (AMAN MYSQL)
            // =========================
            // $table->foreign(
            //     'counselee_id',
            //     'fk_logbook_counselee'
            // )->references('id')
            //  ->on('counselees')
            //  ->cascadeOnDelete();

            // $table->foreign(
            //     'counselee_id',
            //     'fk_logbook_counselee'
            // )->references('id')
            //     ->on('users')
            //     ->cascadeOnDelete();

            $table->foreign(
                'counseling_session_id',
                'fk_logbook_session'
            )->references('id')
                ->on('counseling_sessions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_books');
    }
};
