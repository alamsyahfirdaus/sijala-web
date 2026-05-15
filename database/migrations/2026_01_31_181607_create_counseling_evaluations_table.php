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
        Schema::create('counseling_evaluations', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik evaluasi konseling

            // =========================
            // RELASI SESI KONSELING
            // =========================
            $table->unsignedBigInteger('counseling_session_id');
            // Relasi ke sesi konseling yang dievaluasi

            // =========================
            // HASIL EVALUASI
            // =========================
            $table->integer('score')->nullable();
            // Skor evaluasi hasil konseling (diisi konselor atau hasil agregasi indikator)

            $table->enum('result', ['improved', 'unchanged', 'worsened'])
                ->nullable();
            // Hasil evaluasi:
            // improved  = ada perbaikan
            // unchanged = tidak ada perubahan
            // worsened  = kondisi memburuk

            // =========================
            // REKOMENDASI & CATATAN
            // =========================
            $table->text('recommendation')->nullable();
            // Rekomendasi tindak lanjut dari konselor

            $table->text('note')->nullable();
            // Catatan tambahan evaluasi

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY (AMAN MYSQL)
            // =========================
            $table->foreign(
                'counseling_session_id',
                'fk_counseling_evaluation_session'
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
        Schema::dropIfExists('counseling_evaluations');
    }
};
