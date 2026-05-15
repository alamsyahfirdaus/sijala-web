<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qa_answers', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik jawaban Q&A

            // =========================
            // RELASI PERTANYAAN
            // =========================
            $table->unsignedBigInteger('qa_question_id');
            // Pertanyaan yang dijawab


            // =========================
            // ISI JAWABAN
            // =========================
            $table->text('answer');
            // Isi jawaban konselor

            // =========================
            // PENJAWAB
            // =========================
            $table->unsignedBigInteger('user_id')->nullable()->comment('answered_by');
            // Konselor yang menjawab pertanyaan

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY (AMAN MYSQL)
            // =========================
            $table->foreign(
                'qa_question_id',
                'fk_qa_answer_question'
            )->references('id')
                ->on('qa_questions')
                ->cascadeOnDelete();

            $table->foreign(
                'user_id',
                'fk_qa_answer_user'
            )->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qa_answers');
    }
};
