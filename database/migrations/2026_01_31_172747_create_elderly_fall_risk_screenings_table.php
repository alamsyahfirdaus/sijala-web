<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     */
    public function up(): void
    {
        Schema::create('elderly_fall_risk_screenings', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik untuk setiap hasil skrining risiko jatuh.

            // =========================
            // RELASI SESI KONSELING
            // =========================
            $table->foreignId('counseling_session_id')
                ->constrained('counseling_sessions')
                ->cascadeOnDelete();
            // Menunjukkan sesi konseling tempat skrining dilakukan.
            // Jika sesi konseling dihapus, maka data skrining ikut terhapus.

            // =========================
            // HASIL SKRINING
            // =========================
            $table->unsignedInteger('total_score')->nullable();
            // Total skor yang diperoleh dari seluruh jawaban.

            $table->string('risk_level')->nullable();
            // Kategori risiko jatuh.
            // Contoh:
            // - Rendah
            // - Sedang
            // - Tinggi

            // =========================
            // INTERPRETASI HASIL
            // =========================
            $table->text('interpretation')->nullable();
            // Penjelasan atau kesimpulan hasil skrining.
            // Contoh:
            // "Lansia memiliki risiko jatuh tinggi dan memerlukan
            // modifikasi lingkungan serta pengawasan keluarga."

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu data dibuat
            // updated_at = waktu data terakhir diperbarui
        });
    }

    /**
     * Membatalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('elderly_fall_risk_screenings');
    }
};