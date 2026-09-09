<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Rute Web Aplikasi Perpustakaan)
|--------------------------------------------------------------------------
|
| Di file ini seluruh endpoint URL aplikasi didefinisikan.
| Alur request dipetakan ke Closure fungsi atau Controller tertentu
| dengan middleware keamanan otentikasi dan otorisasi role.
|
*/

// 1. Halaman Beranda (Landing Page / Portal Perpustakaan)
// Menampilkan landing page publik dengan informasi perpustakaan dan petunjuk login.
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard Siswa / Anggota (Role: User)
// Menampilkan ringkasan status buku yang sedang dipinjam siswa dan katalog buku perpustakaan.
// Middleware: 'auth' (wajib login) dan 'verified' (email terverifikasi).
Route::get('/dashboard', function () {
    // Ambil semua daftar katalog buku
    $bukus = Buku::all();

    // Ambil riwayat transaksi khusus untuk siswa yang sedang login saat ini
    $myPeminjamans = Peminjaman::with('buku')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('dashboard', compact('bukus', 'myPeminjamans'));
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Grup Rute Admin (Role: Admin)
// Seluruh rute di dalam grup ini diproteksi oleh middleware auth dan pengecekan role 'admin'.
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Khusus Admin
    // Menghitung statistik ringkas untuk ringkasan di dashboard admin
    Route::get('/admin/dashboard', function () {
        // Proteksi Otorisasi: Pastikan hanya akun dengan role 'admin' yang dapat membuka rute ini
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses administrator.');
        }

        $totalBuku       = Buku::count();
        $totalStokBuku   = Buku::sum('stok');
        $totalUser       = User::where('role', 'user')->count();
        $totalDipinjam   = Peminjaman::where('status', 'dipinjam')->count();
        $totalPeminjaman = Peminjaman::count();

        // 5 transaksi peminjaman terbaru untuk tabel aktivitas
        $recentPeminjamans = Peminjaman::with(['user', 'buku'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalStokBuku',
            'totalUser',
            'totalDipinjam',
            'totalPeminjaman',
            'recentPeminjamans'
        ));
    })->name('admin.dashboard');

    // Route Resource CRUD Buku (/admin/buku, /admin/buku/create, /admin/buku/{id}/edit, dll.)
    Route::resource('/admin/buku', BukuController::class, ['as' => 'admin']);

    // Route Resource CRUD Anggota (/admin/user, /admin/user/create, /admin/user/{id}/edit, dll.)
    Route::resource('/admin/user', UserController::class, ['as' => 'admin']);

    // Route Resource CRUD Transaksi Peminjaman (/admin/peminjaman, /admin/peminjaman/create, dll.)
    Route::resource('/admin/peminjaman', PeminjamanController::class, ['as' => 'admin']);

    // Route Khusus untuk memproses Pengembalian Buku (mengubah status jadi 'dikembalikan' & menambah stok)
    Route::patch('/admin/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'updateStatus'])->name('admin.peminjaman.kembali');
});

// 4. Rute Pengelolaan Profil Akun (Breeze Default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Rute Otentikasi (Login, Register, Logout, Reset Password)
require __DIR__.'/auth.php';