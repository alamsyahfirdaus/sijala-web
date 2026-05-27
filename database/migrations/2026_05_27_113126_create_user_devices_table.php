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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();

            // Relasi ke user
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Firebase Cloud Messaging Token
            $table->text('fcm_token');

            // Jenis device
            $table->enum('device_type', [
                'android',
                'ios',
                'web',
                'tablet'
            ])->default('android');

            // Nama device
            $table->string('device_name')
                ->nullable();

            // Versi aplikasi
            $table->string('app_version')
                ->nullable();

            // Status aktif
            $table->boolean('is_active')
                ->default(true);

            // Waktu login terakhir
            $table->timestamp('last_login_at')
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
        Schema::dropIfExists('user_devices');
    }
};
