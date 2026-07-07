<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_empowerment_questions', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PRIMARY KEY
            |--------------------------------------------------------------------------
            */
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELASI SELF JOIN (DIMENSI)
            |--------------------------------------------------------------------------
            */
            $table->foreignId('dimension_id')
                ->nullable()
                ->constrained('family_empowerment_questions')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | NOMOR ITEM INSTRUMEN
            |--------------------------------------------------------------------------
            | Diisi hanya untuk pertanyaan.
            | Contoh: 1,2,3,...,35
            */
            $table->unsignedTinyInteger('item_number')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TEKS DIMENSI / PERTANYAAN
            |--------------------------------------------------------------------------
            */
            $table->text('question');

            /*
            |--------------------------------------------------------------------------
            | JENIS ITEM
            |--------------------------------------------------------------------------
            | true  = Favorable
            | false = Unfavorable
            | null  = Dimensi
            */
            $table->boolean('is_favorable')->nullable();

            /*
            |--------------------------------------------------------------------------
            | URUTAN TAMPIL
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('order');

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMP
            |--------------------------------------------------------------------------
            */
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_empowerment_questions');
    }
};