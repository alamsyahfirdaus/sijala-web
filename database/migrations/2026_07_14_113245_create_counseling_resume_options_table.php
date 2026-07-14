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
        Schema::create('counseling_resume_options', function (Blueprint $table) {

            // ==================================================
            // PRIMARY KEY
            // ==================================================
            $table->id();

            // ==================================================
            // KATEGORI
            // NULL = DATA INI ADALAH KATEGORI
            // NOT NULL = ITEM YANG BERADA PADA KATEGORI
            // ==================================================
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('counseling_resume_options')
                ->cascadeOnDelete();

            // ==================================================
            // NAMA KATEGORI / ITEM
            // ==================================================
            $table->string('title');

            // ==================================================
            // DESKRIPSI
            // ==================================================
            $table->text('description')
                ->nullable();

            // ==================================================
            // URUTAN TAMPIL
            // ==================================================
            $table->unsignedSmallInteger('sort_order')
                ->default(1);

            // ==================================================
            // STATUS AKTIF
            // ==================================================
            $table->boolean('is_active')
                ->default(true);

            // ==================================================
            // TIMESTAMP
            // ==================================================
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_resume_options');
    }
};