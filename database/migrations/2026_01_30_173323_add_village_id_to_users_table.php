<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan field village_id dan puskesmas_id ke tabel users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // // Relasi ke tabel villages
            // $table->foreignId('village_id')
            //     ->nullable()
            //     ->after('birth_date')
            //     ->constrained('villages')
            //     ->nullOnDelete();

            // Relasi ke tabel puskesmas
            $table->foreignId('puskesmas_id')
                ->nullable()
                ->after('birth_date')
                ->constrained('puskesmas')
                ->nullOnDelete();
        });
    }

    /**
     * Menghapus field village_id dan puskesmas_id dari tabel users.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            // $table->dropForeign(['village_id']);
            $table->dropForeign(['puskesmas_id']);

            // Hapus kolom
            $table->dropColumn([
                // 'village_id',
                'puskesmas_id',
            ]);
        });
    }
};