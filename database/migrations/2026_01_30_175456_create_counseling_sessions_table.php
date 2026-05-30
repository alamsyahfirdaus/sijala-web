<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     */
    public function up(): void
    {
        Schema::create('counseling_sessions', function (Blueprint $table) {

            // ==================================================
            // PRIMARY KEY
            // ==================================================
            $table->id();
            // ID unik untuk setiap sesi konseling.

            // ==================================================
            // RELASI LANSIA YANG DIDAMPINGI
            // ==================================================
            $table->foreignId('elderly_counselee_id')
                ->constrained('elderly_counselee')
                ->cascadeOnDelete();
            // Menunjukkan data lansia yang menjadi fokus
            // dalam sesi konseling.
            // Jika data lansia dihapus, sesi konseling
            // yang terkait juga akan ikut terhapus.

            // ==================================================
            // RELASI KONSELOR
            // ==================================================
            $table->foreignId('counselor_id')
                ->comment('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            // Menunjukkan konselor yang menangani sesi.
            // Nullable karena sesi dapat dibuat sebelum
            // konselor ditentukan.
            // Jika data konselor dihapus, nilainya menjadi NULL.

            // ==================================================
            // JADWAL KONSELING
            // ==================================================
            // $table->date('schedule_date')->nullable();
            // $table->time('schedule_time')->nullable();
            // Tanggal dan waktu pelaksanaan konseling.
            // Dapat bernilai NULL jika belum dijadwalkan.

            // ==================================================
            // JENIS LAYANAN
            // ==================================================
            $table->enum('service_mode', ['chat', 'video'])
                ->default('chat');
            // chat  = Konseling melalui pesan teks
            // video = Konseling melalui video call

            // ==================================================
            // STATUS SESI
            // ==================================================
            $table->enum('status', [
                'ongoing',
                'completed',
            ])->default('ongoing');
            
            // $table->text('health_problems')->nullable();
            // $table->boolean('has_fallen')->nullable();
            
            // ==================================================
            // CATATAN KONSELOR
            // ==================================================
            $table->text('note')->nullable();
            // Ringkasan hasil konseling, rekomendasi,
            // dan tindak lanjut.

            // ==================================================
            // PENANDA SESI TERAKHIR
            // ==================================================
            $table->boolean('is_latest')->default(false);
            // Menandai apakah sesi ini adalah sesi konseling
            // terbaru untuk lansia tersebut.

            // ==================================================
            // USER YANG TERAKHIR MEMPERBARUI
            // ==================================================
            // $table->foreignId('updated_by')
            //     ->nullable()
            //     ->comment('user_id')
            //     ->constrained('users')
            //     ->nullOnDelete();
            // Menunjukkan pengguna yang terakhir
            // memperbarui data sesi konseling.
            // Jika pengguna dihapus, nilainya menjadi NULL.

            // ==================================================
            // TIMESTAMP
            // ==================================================
            $table->timestamps();
            // created_at = waktu data dibuat
            // updated_at = waktu data terakhir diperbarui
        });
    }

    /**
     * Membatalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_sessions');
    }
};