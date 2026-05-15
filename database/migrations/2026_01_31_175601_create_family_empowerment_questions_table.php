<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_empowerment_questions', function (Blueprint $table) {
            // ======================================================
            // PRIMARY KEY
            // ======================================================
            $table->id();

            // ======================================================
            // SELF JOIN KE DIMENSI
            // ======================================================
            $table->foreignId('dimension_id')
                ->nullable()
                ->constrained('family_empowerment_questions')
                ->cascadeOnDelete();

            // ======================================================
            // TIPE DATA
            // ======================================================
            // $table->enum('type', ['dimension', 'question']);

            // ======================================================
            // NAMA DIMENSI / PERTANYAAN
            // ======================================================
            $table->text('question');

            // ======================================================
            // RENTANG SKOR
            // ======================================================
            $table->unsignedTinyInteger('min_score')->nullable();
            $table->unsignedTinyInteger('max_score')->nullable();

            // ======================================================
            // URUTAN TAMPIL
            // ======================================================
            $table->unsignedInteger('order');

            // ======================================================
            // TIMESTAMP
            // ======================================================
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_empowerment_questions');
    }
};