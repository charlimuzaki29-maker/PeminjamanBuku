<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: CreateBukusTable
 * 
 * Bertanggung jawab membuat tabel `bukus` di basis data.
 * Menyimpan data katalog fisik dan stok inventaris buku.
 */
return new class extends Migration
{
    /**
     * Menjalankan migrasi pembuatan tabel `bukus`.
     * 
     * Kolom:
     * - id: Auto-increment Primary Key
     * - kode_buku: Kode unik buku (misal: 'BK-001')
     * - judul: Judul buku
     * - pengarang: Nama penulis/pengarang
     * - penerbit: Nama penerbit buku
     * - stok: Jumlah stok fisik yang tersedia untuk dipinjam
     * - timestamps: Tanggal pembuatan & pembaruan data
     */
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku')->unique();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi (Menghapus tabel `bukus`).
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};

