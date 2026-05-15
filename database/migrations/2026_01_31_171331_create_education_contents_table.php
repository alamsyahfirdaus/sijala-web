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
        Schema::create('education_contents', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id();
            // ID unik konten edukasi

            // =========================
            // IDENTITAS KONTEN
            // =========================
            $table->string('title');
            // Judul konten edukasi

            $table->enum('category', ['video', 'poster']);
            // Kategori konten:
            // Video  = video edukasi
            // Poster = poster / gambar edukasi

            // =========================
            // MEDIA & DESKRIPSI
            // =========================
            $table->string('file_path');
            // Filename poster atau URL video

            $table->text('description')->nullable();
            // Deskripsi singkat isi konten edukasi

            // =========================
            // STATUS KONTEN
            // =========================
            $table->boolean('is_active')->default(true);
            // Status konten:
            // true  = aktif & ditampilkan
            // false = tidak ditampilkan

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu konten dibuat
            // updated_at = waktu konten terakhir diperbarui
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_contents');
    }
};
