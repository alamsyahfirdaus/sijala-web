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

            // Sesi Konseling
            $table->foreignId('consultation_session_id')
                ->nullable()
                ->constrained('counseling_sessions')
                ->nullOnDelete();

            // Materi Edukasi
            $table->foreignId('education_content_id')
                ->nullable()
                ->constrained('education_contents')
                ->nullOnDelete();

            // Konselor yang membagikan
            $table->foreignId('presenter_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Status Presentasi
            $table->enum('status', [
                'playing',
                'paused',
                'stopped',
            ])->default('playing');

            // Posisi video (detik)
            $table->unsignedInteger('current_position')->default(0);

            // Presentasi masih aktif?
            $table->boolean('is_active')->default(true);

            // Waktu mulai
            $table->timestamp('started_at')->nullable();

            // Waktu selesai
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();

            // Index
            $table->index('consultation_session_id');
            $table->index('education_content_id');
            $table->index('presenter_id');
            $table->index('status');
            $table->index('is_active');
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