<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

/**
 * Controller: BukuController
 * 
 * Bertanggung jawab atas operasi CRUD (Create, Read, Update, Delete) data buku perpustakaan.
 * Hanya dapat diakses oleh user dengan role 'admin'.
 */
class BukuController extends Controller
{
    /**
     * Menampilkan daftar seluruh buku di perpustakaan.
     * 
     * Cara kerja:
     * 1. Mengambil semua rekod buku dari database menggunakan model Eloquent `Buku::all()`.
     * 2. Mengirim data `$bukus` ke view `resources/views/admin/buku/index.blade.php`.
     */
    public function index()
    {
        $bukus = Buku::latest()->get();
        return view('admin.buku.index', compact('bukus'));
    }

    /**
     * Menampilkan form input untuk menambahkan buku baru.
     * 
     * Cara kerja:
     * 1. Mengarahkan admin ke view form input `admin.buku.create`.
     */
    public function create()
    {
        return view('admin.buku.create');
    }

    /**
     * Menyimpan data buku baru ke database.
     * 
     * Cara kerja:
     * 1. Memvalidasi data input dari `$request`:
     *    - `kode_buku`: wajib diisi (required) & tidak boleh kembar (unique di tabel bukus).
     *    - `judul`, `pengarang`, `penerbit`: wajib diisi berupa teks.
     *    - `stok`: wajib diisi, berupa angka integer minimal 0.
     * 2. Jika validasi lolos, simpan data ke database melalui `Buku::create(...)`.
     * 3. Redirect kembali ke halaman index buku dengan pesan sukses (flash message).
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|string|max:50|unique:bukus,kode_buku',
            'judul'     => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'stok'      => 'required|integer|min:0',
        ]);

        Buku::create($request->all());

        return redirect()->route('admin.buku.index')->with('success', 'Data buku baru berhasil ditambahkan ke katalog perpustakaan.');
    }

    /**
     * Menampilkan form untuk mengedit data buku yang sudah ada.
     * 
     * Cara kerja:
     * 1. Laravel memanfaatkan Route-Model Binding untuk otomatis mencari buku berdasarkan ID di URL.
     * 2. Mengirim data `$buku` ke view `admin.buku.edit`.
     */
    public function edit(Buku $buku)
    {
        return view('admin.buku.edit', compact('buku'));
    }

    /**
     * Menyimpan pembaruan data buku yang telah diedit.
     * 
     * Cara kerja:
     * 1. Memvalidasi input dari admin. Pada aturan `unique:bukus,kode_buku,` ditambahkan pengecualian
     *    untuk ID buku saat ini agar tidak error saat kode buku tidak diubah.
     * 2. Memperbarui rekod pada database menggunakan `$buku->update(...)`.
     * 3. Redirect kembali ke halaman daftar buku dengan notifikasi sukses.
     */
    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode_buku' => 'required|string|max:50|unique:bukus,kode_buku,' . $buku->id,
            'judul'     => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'stok'      => 'required|integer|min:0',
        ]);

        $buku->update($request->all());

        return redirect()->route('admin.buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Menghapus buku dari katalog perpustakaan.
     * 
     * Cara kerja:
     * 1. Memanggil `$buku->delete()` untuk menghapus baris data dari database.
     *    (Catatan: Jika terdapat foreign key cascade pada transaksi peminjaman, relasi terkait ikut terhapus).
     * 2. Redirect ke daftar buku dengan pesan sukses.
     */
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Data buku berhasil dihapus dari perpustakaan.');
    }
}

