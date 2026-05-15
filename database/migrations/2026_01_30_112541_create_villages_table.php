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
        Schema::create('villages', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik kelurahan/desa

            // =========================
            // IDENTITAS KELURAHAN / DESA
            // =========================
            $table->string('code', 13)->nullable();
            // Kode Kemendagri kelurahan/desa

            $table->string('name');
            // Nama kelurahan/desa (contoh: Cibabat)

            // =========================
            // RELASI WILAYAH
            // =========================
            $table->foreignId('district_id')
                ->constrained('districts')
                ->cascadeOnDelete();
            // Relasi ke tabel districts
            // Menandakan kelurahan/desa berada di kecamatan tertentu

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
        Schema::dropIfExists('villages');
    }
};
