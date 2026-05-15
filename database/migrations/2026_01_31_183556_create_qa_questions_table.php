<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qa_questions', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik pertanyaan Q&A

            // =========================
            // KONTEN PERTANYAAN
            // =========================
            $table->string('title');
            // Judul singkat pertanyaan

            $table->text('question');
            // Isi lengkap pertanyaan

            // =========================
            // STATUS PERTANYAAN
            // =========================
            $table->enum('status', ['open', 'answered'])
                ->default('open');
            // open     = belum dijawab
            // answered = sudah dijawab

            // =========================
            // PENGIRIM PERTANYAAN
            // =========================
            $table->unsignedBigInteger('user_id')->nullable()->comment('created_by');
            // User yang mengajukan pertanyaan (siapa saja)

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY (AMAN MYSQL)
            // =========================
            $table->foreign(
                'user_id',
                'fk_qa_question_user'
            )->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qa_questions');
    }
};
