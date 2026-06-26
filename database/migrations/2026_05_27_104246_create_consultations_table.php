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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            
            // Konseli yang melakukan panggilan
            $table->foreignId('caller_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Konselor penerima panggilan
            $table->foreignId('receiver_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SESI KONSELING (OPSIONAL)
            |--------------------------------------------------------------------------
            */
            $table->foreignId('counseling_session_id')
                ->nullable()
                ->constrained('counseling_sessions')
                ->nullOnDelete();


            // Nama channel Agora
            $table->string('channel_name')->unique();

            // Token Agora
            $table->text('token')->nullable();

            // Jenis panggilan
            $table->enum('call_type', [
                'video',
                'audio'
            ])->default('video');

            // Status konsultasi
            $table->enum('status', [
                'calling',
                'ringing',
                'accepted',
                'rejected',
                'ended',
                'missed'
            ])->default('calling');

            // Waktu mulai panggilan
            $table->timestamp('started_at')->nullable();

            // Waktu selesai panggilan
            $table->timestamp('ended_at')->nullable();

            // Durasi dalam detik
            $table->integer('duration')->default(0);

            // Catatan konsultasi
            $table->text('notes')->nullable();

            // Apakah screen sharing aktif
            $table->boolean('is_screen_sharing')
                ->default(false);

            // Metadata tambahan
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
