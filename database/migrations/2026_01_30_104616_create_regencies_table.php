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
        Schema::create('regencies', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik kabupaten/kota

            // =========================
            // IDENTITAS KABUPATEN / KOTA
            // =========================
            $table->string('code', 10)->nullable();
            // Kode Kemendagri/BPS kabupaten/kota (unik secara nasional)

            $table->string('name');
            // Nama kabupaten/kota (contoh: Kota Tasikmalaya)

            // =========================
            // RELASI WILAYAH
            // =========================
            $table->foreignId('province_id')
                ->constrained('provinces')
                ->cascadeOnDelete();
            // Relasi ke tabel provinces
            // Menandakan kabupaten/kota berada di provinsi tertentu

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu data dibuat
            // updated_at = waktu data terakhir diubah
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regencies');
    }
};
