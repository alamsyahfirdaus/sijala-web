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
        Schema::create('provinces', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik provinsi (primary key)

            // =========================
            // IDENTITAS PROVINSI
            // =========================
            $table->string('code', 10)->nullable();
            // Kode provinsi (misalnya kode Kemendagri/BPS), opsional

            $table->string('name');
            // Nama provinsi (contoh: Jawa Barat)

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
        Schema::dropIfExists('provinces');
    }
};
