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
        Schema::create('consultation_messages', function (Blueprint $table) {
            $table->id();

            // Relasi ke konsultasi/video call
            $table->foreignId('consultation_id')
                ->constrained('consultations')
                ->cascadeOnDelete();

            // Pengirim pesan
            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Jenis pesan
            $table->enum('message_type', [
                'text',
                'image',
                'file',
                'audio',
                'video',
                'system'
            ])->default('text');

            // Isi pesan
            $table->text('message')->nullable();

            // File attachment
            $table->string('file_path')->nullable();

            // Nama file
            $table->string('file_name')->nullable();

            // Ukuran file
            $table->integer('file_size')->nullable();

            // Mime type
            $table->string('mime_type')->nullable();

            // Status dibaca
            $table->boolean('is_read')
                ->default(false);

            // Waktu dibaca
            $table->timestamp('read_at')
                ->nullable();

            // Metadata tambahan
            $table->json('metadata')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_messages');
    }
};
