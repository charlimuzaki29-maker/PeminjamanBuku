<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder: DatabaseSeeder
 * 
 * Mengisi database dengan data dummy awal untuk keperluan pengujian (testing/development).
 * Dijalankan dengan perintah: `php artisan db:seed` atau `php artisan migrate:fresh --seed`.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan proses seeding.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin Utama
        // Kredensial untuk masuk ke panel admin perpustakaan
        User::create([
            'name'     => 'Administrator Perpus',
            'email'    => 'admin@perpus.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // 2. Membuat Akun Siswa / Anggota Awal
        // Kredensial untuk siswa melihat katalog dan riwayat peminjaman
        User::create([
            'name'     => 'Siswa Teladan',
            'email'    => 'siswa@perpus.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);

        // 3. Membuat Contoh Data Buku Awal
        Buku::create([
            'kode_buku' => 'BK-001',
            'judul'     => 'Pemrograman Web Laravel Dasar',
            'pengarang' => 'Eko Kurniawan',
            'penerbit'  => 'Media Ilmu',
            'stok'      => 5,
        ]);

        Buku::create([
            'kode_buku' => 'BK-002',
            'judul'     => 'Belajar Basis Data MySQL untuk Pemula',
            'pengarang' => 'Budi Raharjo',
            'penerbit'  => 'Informatika',
            'stok'      => 3,
        ]);

        Buku::create([
            'kode_buku' => 'BK-003',
            'judul'     => 'Algoritma & Struktur Data Modern',
            'pengarang' => 'Rinaldi Munir',
            'penerbit'  => 'Informatika Bandung',
            'stok'      => 4,
        ]);
    }
}

