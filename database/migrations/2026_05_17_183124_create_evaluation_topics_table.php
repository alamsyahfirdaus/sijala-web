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
        Schema::create('evaluation_topics', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik topik evaluasi

            // =========================
            // IDENTITAS TOPIK
            // =========================
            $table->string('topic');
            // Judul topik evaluasi
            // Contoh:
            // - Pencegahan Jatuh pada Lansia
            // - Penggunaan Alat Bantu Jalan
            // - Komunikasi Efektif dengan Lansia

            $table->text('description')->nullable();
            // Deskripsi singkat topik evaluasi

            // =========================
            // STATUS TOPIK
            // =========================
            $table->boolean('is_active')->default(true);
            // true  = topik aktif
            // false = topik tidak digunakan

            $table->integer('order')->nullable();
            // Urutan tampil topik

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu topik dibuat
            // updated_at = waktu topik diperbarui
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_topics');
    }
};