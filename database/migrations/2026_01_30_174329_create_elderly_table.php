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
        Schema::create('elderly_counselee', function (Blueprint $table) {

            // ==================================================
            // PRIMARY KEY
            // ==================================================
            $table->id();
            // ID unik untuk setiap data lansia.

            // ==================================================
            // RELASI KONSELI (PENDAMPING)
            // ==================================================
            $table->foreignId('counselee_id')
                ->comment('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            // Menunjukkan pengguna (anggota keluarga/konseli)
            // yang mendampingi lansia.
            // Jika data pengguna dihapus, maka data lansia
            // yang terkait akan ikut terhapus.


            // ==================================================
            // INFORMASI PENDAMPINGAN
            // ==================================================
            $table->unsignedInteger('care_duration_months')->nullable();
            // Lama konseli merawat lansia dalam satuan bulan.
            // Contoh:
            // 6  = 6 bulan
            // 12 = 1 tahun
            // 24 = 2 tahun

            // ==================================================
            // IDENTITAS LANSIA
            // ==================================================
            $table->string('elderly_name')->nullable();
            // Nama lengkap lansia.

            $table->enum('elderly_gender', ['L', 'P'])->nullable();
            // Jenis kelamin:
            // L = Laki-laki
            // P = Perempuan

            $table->unsignedInteger('elderly_age')->nullable();
            // Usia lansia dalam satuan tahun.
            // Contoh:
            // 60 = 60 tahun
            // 75 = 75 tahun
            // 82 = 82 tahun

            // ==================================================
            // MASALAH KESEHATAN
            // ==================================================
            $table->text('health_problems')->nullable();
            // Daftar masalah kesehatan yang dimiliki lansia.
            // Contoh:
            // - Hipertensi
            // - Diabetes Melitus
            // - Stroke
            // - Osteoarthritis
            // - Gangguan penglihatan
            // - Keterbatasan mobilitas

            // ==================================================
            // RIWAYAT JATUH
            // ==================================================
            $table->boolean('has_fallen')->nullable();
            // Status riwayat jatuh:
            // true  = Pernah jatuh
            // false = Tidak pernah jatuh
            // null  = Belum diisi

            // ==================================================
            // TIMESTAMP
            // ==================================================
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
        Schema::dropIfExists('elderly_counselee');
    }
};