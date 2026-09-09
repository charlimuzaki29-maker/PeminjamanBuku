<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model: Buku
 * 
 * Merepresentasikan tabel 'bukus' di dalam basis data.
 * Digunakan untuk mengelola informasi katalog buku perpustakaan.
 * 
 * Properti kolom:
 * - id (int, primary key)
 * - kode_buku (string, unik)
 * - judul (string)
 * - pengarang (string)
 * - penerbit (string)
 * - stok (int)
 * - created_at (timestamp)
 * - updated_at (timestamp)
 */
class Buku extends Model
{
    use HasFactory;

    /**
     * $guarded = ['id']
     * Menandakan bahwa seluruh kolom pada tabel `bukus` diizinkan untuk diisi massal (Mass Assignment),
     * kecuali kolom 'id' yang dilindungi.
     */
    protected $guarded = ['id'];

    /**
     * Relasi One-to-Many ke model Peminjaman.
     * 
     * Cara kerja:
     * 1 buku dapat tercatat dalam banyak transaksi peminjaman (riwayat).
     * Relasi ini menghubungkan kolom `id` pada tabel `bukus` dengan `buku_id` pada tabel `peminjamans`.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}

