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
        Schema::create('users', function (Blueprint $table) {

            // =========================
            // PRIMARY KEY
            // =========================
            $table->id(); 
            // ID unik setiap pengguna (primary key)

            // =========================
            // IDENTITAS DASAR PENGGUNA
            // =========================
            $table->string('name');
            // Nama lengkap pengguna

            $table->string('username')->unique()->nullable();
            // Username untuk login (opsional)

            $table->string('email')->unique()->nullable();
            // Email pengguna untuk login & notifikasi (opsional)

            $table->timestamp('email_verified_at')->nullable();
            // Waktu verifikasi email (null jika belum diverifikasi)

            $table->string('password');
            // Password yang sudah di-hash

            $table->rememberToken();
            // Token untuk fitur "remember me"

            // =========================
            // ROLE / PERAN PENGGUNA
            // =========================
            $table->enum('role', ['admin', 'konselor', 'konseli'])->nullable();
            // Peran pengguna dalam sistem:
            // admin = pengelola sistem
            // konselor = tenaga konseling
            // konseli = keluarga / pendamping lansia

            // =========================
            // INFORMASI KONTAK & PROFIL
            // =========================
            $table->string('phone')->nullable();
            // Nomor telepon pengguna

            $table->enum('gender', ['L', 'P'])->nullable();
            // Jenis kelamin:
            // L = Laki-laki
            // P = Perempuan

            $table->string('photo')->nullable();
            // Path foto profil pengguna (disimpan di storage)

            // =========================
            // DATA KELAHIRAN
            // =========================
            $table->string('birth_place')->nullable();
            // Tempat lahir pengguna

            $table->date('birth_date')->nullable();
            // Tanggal lahir pengguna

            // =========================
            // ALAMAT
            // =========================
            // $table->text('address')->nullable();
            // Alamat lengkap pengguna

            // =========================
            // INFORMASI SOSIAL
            // =========================
            $table->string('occupation')->nullable();
            // Pekerjaan pengguna

            $table->string('education')->nullable();
            // Pendidikan terakhir pengguna

            // =========================
            // STATUS AKUN
            // =========================
            $table->boolean('is_active')->default(true);
            // Status akun:
            // true  = aktif
            // false = nonaktif / diblokir

            // =========================
            // STATUS LOGIN
            // =========================
            $table->boolean('is_online')->default(false);
            // Status pengguna:
            // true  = sedang online
            // false = offline

            $table->timestamp('last_login_at')->nullable();
            // Waktu terakhir pengguna berhasil login

            $table->timestamp('last_logout_at')->nullable();
            // Waktu terakhir pengguna logout

            $table->timestamp('last_seen_at')->nullable();
            // Waktu terakhir pengguna aktif di aplikasi

            // =========================
            // TIMESTAMP SISTEM
            // =========================
            $table->timestamps();
            // created_at = waktu pembuatan akun
            // updated_at = waktu terakhir diubah
        });

        // =========================
        // PASSWORD RESET TOKENS
        // =========================
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            // Email pengguna yang melakukan reset password

            $table->string('token');
            // Token reset password

            $table->timestamp('created_at')->nullable();
            // Waktu pembuatan token reset
        });

        // =========================
        // SESSIONS (LOGIN SESSION)
        // =========================
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // ID unik sesi login

            $table->foreignId('user_id')->nullable()->index();
            // Relasi ke tabel users (pengguna yang login)

            $table->string('ip_address', 45)->nullable();
            // Alamat IP pengguna

            $table->text('user_agent')->nullable();
            // Informasi browser / device pengguna

            $table->longText('payload');
            // Data session yang disimpan Laravel

            $table->integer('last_activity')->index();
            // Waktu aktivitas terakhir (timestamp UNIX)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
