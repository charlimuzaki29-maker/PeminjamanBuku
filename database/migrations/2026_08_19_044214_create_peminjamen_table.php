<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: CreatePeminjamenTable
 * 
 * Bertanggung jawab membuat tabel transaksi `peminjamans` di basis data.
 * Menghubungkan entitas User (Siswa/Peminjam) dan Buku melalui Foreign Key Constraints.
 */
return new class extends Migration
{
    /**
     * Menjalankan migrasi pembuatan tabel `peminjamans`.
     * 
     * Kolom:
     * - id: Auto-increment Primary Key
     * - user_id: Foreign Key mengarah ke `users.id` (dengan onDelete('cascade') jika akun dihapus)
     * - buku_id: Foreign Key mengarah ke `bukus.id` (dengan onDelete('cascade') jika buku dihapus)
     * - tanggal_pinjam: Tanggal saat buku mulai dipinjam
     * - tanggal_kembali: Tanggal batas jatuh tempo pengembalian
     * - status: Status peminjaman saat ini ('dipinjam' atau 'dikembalikan')
     * - timestamps: created_at & updated_at
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (siapa siswa yang meminjam)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Relasi ke tabel bukus (buku apa yang dipinjam)
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            // Status transaksi: 'dipinjam' (default) atau 'dikembalikan'
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi (Menghapus tabel `peminjamans`).
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};

