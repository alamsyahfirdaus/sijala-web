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
        Schema::create('elderly_fall_risk_answers', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik jawaban skrining

            // =========================
            // RELASI HEADER ASESMEN
            // =========================
            $table->foreignId('screening_id')
                ->constrained('elderly_fall_risk_screenings')
                ->cascadeOnDelete();
            // Relasi ke header skrining
            // Menandakan jawaban ini milik asesmen tertentu

            // =========================
            // RELASI MASTER PERTANYAAN
            // =========================
            $table->foreignId('question_id')
                ->constrained('elderly_fall_risk_questions')
                ->cascadeOnDelete();
            // Relasi ke master pertanyaan skrining

            // =========================
            // ISI JAWABAN
            // =========================
            $table->string('answer')->nullable();
            // Jawaban yang diberikan (Ya/Tidak/Angka)

            $table->integer('score')->nullable();
            // Skor dari jawaban tersebut
            // Diambil dari master pertanyaan

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu jawaban disimpan
            // updated_at = waktu jawaban diperbarui
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elderly_fall_risk_answers');
    }
};
