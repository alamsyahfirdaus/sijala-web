<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_empowerment_answers', function (Blueprint $table) {

            $table->id();

            // Relasi ke header asesmen
            $table->unsignedBigInteger('empowerment_id');

            // Relasi ke master pertanyaan
            $table->foreignId('question_id')
                ->constrained('family_empowerment_questions')
                ->cascadeOnDelete();

            $table->string('answer')->nullable();
            $table->integer('score')->nullable();

            $table->timestamps();

            // Foreign key manual (nama dipendekkan)
            $table->foreign(
                'empowerment_id',
                'fk_fe_answers_assessment'
            )->references('id')
                ->on('family_empowerment_assessments')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_empowerment_answers');
    }
};
