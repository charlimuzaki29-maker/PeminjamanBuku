<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model: Peminjaman
 * 
 * Merepresentasikan tabel transaksi 'peminjamans' di dalam basis data.
 * Menghubungkan entitas Pengguna (Siswa) dengan Buku yang dipinjam beserta tanggal dan statusnya.
 * 
 * Properti kolom:
 * - id (int, primary key)
 * - user_id (foreign key -> users.id)
 * - buku_id (foreign key -> bukus.id)
 * - tanggal_pinjam (date)
 * - tanggal_kembali (date)
 * - status (enum: 'dipinjam', 'dikembalikan')
 * - created_at (timestamp)
 * - updated_at (timestamp)
 */
class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel eksplisit.
     * Secara konvensi bahasa Inggris jamak, Laravel mungkin mencari 'peminjamen'.
     * Baris ini memastikan tabel yang digunakan adalah 'peminjamans'.
     */
    protected $table = 'peminjamans';

    /**
     * Mengizinkan mass assignment untuk semua kolom kecuali 'id'.
     */
    protected $guarded = ['id'];

    /**
     * Relasi Many-to-One (BelongsTo) ke model User.
     * 
     * Cara kerja:
     * Setiap 1 baris transaksi peminjaman dimiliki/dilakukan oleh 1 orang pengguna (siswa/anggota).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-One (BelongsTo) ke model Buku.
     * 
     * Cara kerja:
     * Setiap 1 baris transaksi peminjaman mengacu pada 1 buah buku perpustakaan.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}

