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
        Schema::create('evaluations', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik hasil evaluasi

            // =========================
            // RELASI USER
            // =========================
            // $table->unsignedBigInteger('user_id');
            // User yang mengerjakan evaluasi

            // =========================
            // RELASI SESI KONSELING
            // =========================
            $table->unsignedBigInteger('counseling_session_id');
            // Sesi konseling terkait

            // =========================
            // RELASI TOPIK
            // =========================
            $table->unsignedBigInteger('evaluation_topic_id');
            // Topik yang diuji

            // =========================
            // JENIS EVALUASI
            // =========================
            $table->enum('evaluation_type', ['pre_test', 'post_test'])
                ->default('post_test');
            // Jenis evaluasi

            // =========================
            // HASIL EVALUASI
            // =========================
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('total_score')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            // Persentase nilai (0–100)
            
            // KATEGORI HASIL EVALUASI
            $table->string('category')->nullable();

            // =========================
            // STATUS KELULUSAN
            // =========================
            // $table->boolean('is_passed')->default(false);
            // true = lulus
            // false = belum lulus

            // =========================
            // WAKTU PENGERJAAN
            // =========================
            // $table->timestamp('started_at')->nullable();
            // $table->timestamp('submitted_at')->nullable();

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();

            // =========================
            // FOREIGN KEY
            // =========================
            // $table->foreign(
            //     'user_id',
            //     'fk_evaluation_user'
            // )->references('id')
            //  ->on('users')
            //  ->cascadeOnDelete();

            $table->foreign(
                'counseling_session_id',
                'fk_evaluation_session'
            )->references('id')
             ->on('counseling_sessions')
             ->cascadeOnDelete();

            $table->foreign(
                'evaluation_topic_id',
                'fk_evaluation_topic'
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
        Schema::dropIfExists('evaluations');
    }
};