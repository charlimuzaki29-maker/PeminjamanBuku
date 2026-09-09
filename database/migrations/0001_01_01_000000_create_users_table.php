<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: CreateUsersTable
 * 
 * Membuat struktur tabel `users`, `password_reset_tokens`, dan `sessions`.
 * Pada tabel `users` ditambahkan kolom `role` ('admin' | 'user') untuk otentikasi multi-peran.
 */
return new class extends Migration
{
    /**
     * Menjalankan migrasi (Membuat tabel ke database).
     * 
     * Kolom tabel `users`:
     * - id: Auto-increment Primary Key
     * - name: Nama lengkap user / siswa
     * - email: Alamat email unik untuk login
     * - email_verified_at: Waktu konfirmasi email
     * - password: Password yang di-hash
     * - role: Hak akses pengguna ('admin' untuk pengelola perpus, 'user' untuk siswa peminjam)
     * - remember_token: Token keamanan 'ingat saya' pada sesi login
     * - timestamps: created_at & updated_at
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // Kolom role untuk otorisasi hak akses (Admin atau Siswa)
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel untuk menampung token reset password via email
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel untuk manajemen session pengguna berbasis database driver
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Membatalkan migrasi (Rollback tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
