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
        Schema::create('puskesmas', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik puskesmas (primary key)

            // =========================
            // IDENTITAS PUSKESMAS
            // =========================
            $table->string('code')->nullable();
            // Kode puskesmas (kode internal/Dinas Kesehatan), opsional

            $table->string('name');
            // Nama resmi puskesmas
            // Contoh: Puskesmas Cimahi Selatan

            // =========================
            // RELASI WILAYAH UTAMA
            // =========================
            $table->foreignId('village_id')
                ->constrained('villages')
                ->cascadeOnDelete();
            // Relasi ke tabel villages
            // Menunjukkan lokasi utama puskesmas berada pada kelurahan tertentu

            // =========================
            // KONTAK & LOKASI
            // =========================
            $table->text('address')->nullable();
            // Alamat lengkap puskesmas

            $table->string('phone')->nullable();
            // Nomor telepon puskesmas

            $table->string('email')->nullable();
            // Email resmi puskesmas (jika ada)

            // =========================
            // INFORMASI TAMBAHAN
            // =========================
            $table->string('head_name')->nullable();
            // Nama kepala puskesmas

            $table->string('service_type')
                ->nullable()
                ->comment('1 = Rawat Inap, 2 = Non Rawat Inap');
            // Jenis layanan puskesmas:
            // 1 = Rawat Inap
            // 2 = Non Rawat Inap

            $table->text('description')->nullable();
            // Keterangan tambahan puskesmas
            // (misalnya: layanan unggulan, jam operasional, dll)

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu data puskesmas dibuat
            // updated_at = waktu data puskesmas terakhir diperbarui
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puskesmas');
    }
};
