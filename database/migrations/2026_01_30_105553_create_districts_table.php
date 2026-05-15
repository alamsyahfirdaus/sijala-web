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
        Schema::create('districts', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik kecamatan

            // =========================
            // IDENTITAS KECAMATAN
            // =========================
            $table->string('code', 13)->nullable();
            // Kode Kemendagri kecamatan (biasanya 13 digit)

            $table->string('name');
            // Nama kecamatan (contoh: Cimahi Utara)

            // =========================
            // RELASI WILAYAH
            // =========================
            $table->foreignId('regency_id')
                ->constrained('regencies')
                ->cascadeOnDelete();
            // Relasi ke tabel regencies
            // Menandakan kecamatan berada di kabupaten/kota tertentu

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
        Schema::dropIfExists('districts');
    }
};
