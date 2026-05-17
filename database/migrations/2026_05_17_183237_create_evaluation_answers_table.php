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
        Schema::create('evaluation_answers', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik jawaban evaluasi

            // =========================
            // RELASI HEADER EVALUASI
            // =========================
            $table->unsignedBigInteger('evaluation_id');
            // Header evaluasi

            // =========================
            // RELASI PERTANYAAN
            // =========================
            $table->unsignedBigInteger('evaluation_question_id');
            // Pertanyaan yang dijawab

            // =========================
            // JAWABAN PESERTA
            // =========================
            $table->enum('selected_answer', ['a', 'b', 'c', 'd']);
            // Jawaban yang dipilih peserta

            // =========================
            // HASIL PENILAIAN
            // =========================
            $table->boolean('is_correct')->default(false);
            // true  = jawaban benar
            // false = jawaban salah

            $table->integer('score')->default(0);
            // Skor yang diperoleh untuk pertanyaan ini

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY
            // =========================
            $table->foreign(
                'evaluation_id',
                'fk_eval_answer_eval'
            )->references('id')
             ->on('evaluations')
             ->cascadeOnDelete();

            $table->foreign(
                'evaluation_question_id',
                'fk_eval_answer_question'
            )->references('id')
             ->on('evaluation_questions')
             ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_answers');
    }
};