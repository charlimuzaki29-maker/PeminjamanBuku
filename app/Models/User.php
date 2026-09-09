<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model: User
 * 
 * Merepresentasikan data pengguna pada tabel 'users'.
 * Mendukung autentikasi login (Authenticatable) serta sistem otorisasi multi-role:
 * - 'admin': Memiliki akses penuh ke dashboard admin, kelola buku, anggota, dan transaksi peminjaman.
 * - 'user': Siswa/Anggota perpustakaan yang dapat melihat riwayat peminjaman mandiri & katalog buku.
 * 
 * Properti kolom:
 * - id (int, primary key)
 * - name (string)
 * - email (string, unik)
 * - password (string, hash)
 * - role (string: 'admin' | 'user')
 * - email_verified_at (timestamp, nullable)
 * - remember_token (string, nullable)
 * - created_at (timestamp)
 * - updated_at (timestamp)
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Kolom-kolom yang dapat diisi secara massal (Mass Assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Relasi One-to-Many ke model Peminjaman.
     * 
     * Cara kerja:
     * 1 user (siswa) dapat memiliki banyak transaksi peminjaman buku sepanjang waktu.
     * Menghubungkan kolom `id` pada tabel `users` dengan `user_id` pada tabel `peminjamans`.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Kolom yang disembunyikan saat data model diubah menjadi format JSON / Array (keamanan).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Pengaturan konversi tipe data otomatis (Type Casting).
     * 
     * Cara kerja:
     * - `email_verified_at` otomatis di-cast menjadi objek Carbon DateTime.
     * - `password` otomatis di-hash menggunakan algoritma default Laravel saat disimpan.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

