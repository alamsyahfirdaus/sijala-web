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
        Schema::create('evaluation_questions', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik pertanyaan evaluasi

            // =========================
            // RELASI TOPIK
            // =========================
            $table->unsignedBigInteger('evaluation_topic_id');
            // Topik tempat pertanyaan ini berada

            // =========================
            // ISI PERTANYAAN
            // =========================
            $table->text('question');
            // Teks pertanyaan

            // =========================
            // PILIHAN JAWABAN
            // =========================
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            // Opsi jawaban pilihan ganda

            // =========================
            // KUNCI JAWABAN
            // =========================
            $table->enum('correct_answer', ['a', 'b', 'c', 'd']);
            // Jawaban yang benar

            // =========================
            // BOBOT NILAI
            // =========================
            $table->integer('score')->default(1);
            // Skor jika jawaban benar

            // =========================
            // STATUS
            // =========================
            $table->boolean('is_active')->default(true);
            // Status pertanyaan

            $table->integer('order')->nullable();
            // Urutan pertanyaan dalam topik

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY
            // =========================
            $table->foreign(
                'evaluation_topic_id',
                'fk_eval_question_topic'
            )->references('id')
             ->on('evaluation_topics')
             ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};