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
        Schema::create('elderly_fall_risk_questions', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik pertanyaan skrining risiko jatuh

            // =========================
            // ISI PERTANYAAN
            // =========================
            $table->text('question');
            // Teks pertanyaan skrining risiko jatuh lansia

            // =========================
            // TIPE JAWABAN
            // =========================
            $table->enum('answer_type', ['yes_no', 'scale', 'number'])
                ->default('yes_no');
            // Jenis jawaban:
            // yes_no = Ya / Tidak
            // scale  = Skala (mis. 1–4)
            // number = Angka (mis. jumlah kejadian jatuh)

            // =========================
            // NILAI SKOR
            // =========================
            $table->integer('score_yes')->nullable();
            // Skor jika jawaban "Ya" (untuk answer_type yes_no)

            $table->integer('score_no')->nullable();
            // Skor jika jawaban "Tidak" (untuk answer_type yes_no)

            // =========================
            // STATUS PERTANYAAN
            // =========================
            $table->boolean('is_active')->default(true);
            // Status pertanyaan:
            // true  = aktif digunakan
            // false = tidak digunakan lagi

            // =========================
            // URUTAN TAMPILAN
            // =========================
            $table->integer('order')->nullable();
            // Urutan pertanyaan saat ditampilkan di aplikasi

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu pertanyaan dibuat
            // updated_at = waktu pertanyaan diperbarui
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elderly_fall_risk_questions');
    }
};
