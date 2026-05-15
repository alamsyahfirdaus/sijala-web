<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_empowerment_assessments', function (Blueprint $table) {

            // PRIMARY KEY
            $table->id();
            // ID unik asesmen pemberdayaan keluarga

            // RELASI KONSELI
            // $table->foreignId('counselee_id')
            //     ->constrained('counselees')
            //     ->cascadeOnDelete();
            // Konseli (keluarga) yang dinilai

            // $table->foreignId('counselee_id')->comment('user_id')
            //     ->constrained('users')
            //     ->cascadeOnDelete();
            // Relasi ke tabel users
            // Menunjukkan siapa konseli (keluarga) yang dinilai

            // RELASI LANSIA
            // $table->foreignId('elderly_id')
            //     ->nullable()
            //     ->constrained('elderly')
            //     ->nullOnDelete();
            // Lansia yang didampingi

            // RELASI KONSELING
            $table->foreignId('counseling_session_id')
                ->constrained('counseling_sessions')
                ->cascadeOnDelete();
            // Sesi konseling terkait

            // TANGGAL ASESMEN
            // $table->date('assessment_date');
            // Tanggal pengisian kuesioner

            // HASIL
            $table->integer('total_score')->nullable();
            // Total skor pemberdayaan keluarga

            // $table->enum('empowerment_level', ['low', 'medium', 'high'])->nullable();
            $table->string('empowerment_level')->nullable();
            // Tingkat pemberdayaan keluarga

            // CATATAN
            $table->text('note')->nullable();
            // Catatan konselor

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_empowerment_assessments');
    }
};
