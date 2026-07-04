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
        Schema::create('consultation_presentations', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | CONSULTATION
            |--------------------------------------------------------------------------
            | Setiap presentasi selalu terhubung dengan satu video call.
            |--------------------------------------------------------------------------
            */
            $table->foreignId('consultation_id')
                ->constrained('consultations')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | EDUCATION CONTENT
            |--------------------------------------------------------------------------
            | Materi edukasi yang dibagikan oleh konselor.
            |--------------------------------------------------------------------------
            */
            $table->foreignId('education_content_id')
                ->constrained('education_contents')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PRESENTER
            |--------------------------------------------------------------------------
            | User (Konselor) yang membagikan materi.
            |--------------------------------------------------------------------------
            */
            $table->foreignId('presenter_id')
                ->comment('User yang membagikan presentasi')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PRESENTATION STATUS
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'playing',
                'paused',
                'stopped',
            ])->default('playing');

            /*
            |--------------------------------------------------------------------------
            | CURRENT POSITION
            |--------------------------------------------------------------------------
            | Posisi materi (detik) apabila berupa video atau slide.
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('current_position')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | ACTIVE FLAG
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | METADATA
            |--------------------------------------------------------------------------
            | Menyimpan informasi tambahan apabila diperlukan.
            |--------------------------------------------------------------------------
            */
            $table->json('metadata')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | START / END TIME
            |--------------------------------------------------------------------------
            */
            $table->timestamp('started_at')->nullable();

            $table->timestamp('ended_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            // Polling status presentasi
            $table->index([
                'consultation_id',
                'is_active',
            ]);

            // Pause / Resume / Stop
            $table->index([
                'consultation_id',
                'status',
            ]);

            // Relasi materi
            $table->index('education_content_id');

            // Riwayat presenter
            $table->index('presenter_id');

            // Riwayat berdasarkan waktu
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_presentations');
    }
};