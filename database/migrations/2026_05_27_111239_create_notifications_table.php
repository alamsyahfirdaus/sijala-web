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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Penerima notifikasi
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Judul notifikasi
            $table->string('title');

            // Isi notifikasi
            $table->text('body')->nullable();

            // Jenis notifikasi
            $table->enum('type', [
                'incoming_call',
                'missed_call',
                'message',
                'consultation',
                'reminder',
                'system'
            ])->default('system');

            // Data tambahan
            $table->json('data')->nullable();

            // Status dibaca
            $table->boolean('is_read')
                ->default(false);

            // Waktu dibaca
            $table->timestamp('read_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
