<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controller: PeminjamanController
 * 
 * Mengelola alur transaksi peminjaman dan pengembalian buku perpustakaan.
 * Meliputi pencatatan peminjaman baru, pengurangan stok otomatis saat dipinjam,
 * penambahan stok kembali saat buku dikembalikan, serta pembatalan/penghapusan data.
 */
class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi peminjaman buku.
     * 
     * Cara kerja:
     * 1. Mengambil data transaksi peminjaman terbaru beserta relasi `user` (anggota)
     *    dan relasi `buku` menggunakan Eager Loading `with(['user', 'buku'])` untuk efisiensi query (mencegah N+1 problem).
     * 2. Menampilkan data pada view `resources/views/admin/peminjaman/index.blade.php`.
     */
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Menampilkan form untuk mencatat transaksi peminjaman baru.
     * 
     * Cara kerja:
     * 1. Mengambil daftar user dengan role 'user' (hanya siswa/anggota yang meminjam).
     * 2. Mengambil daftar buku yang memiliki stok > 0 (buku yang habis tidak ditampilkan/dibatasi).
     * 3. Mengirimkan data `$users` dan `$bukus` ke view `admin.peminjaman.create`.
     */
    public function create()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        return view('admin.peminjaman.create', compact('users', 'bukus'));
    }

    /**
     * Menyimpan transaksi peminjaman baru & mengurangi stok buku secara otomatis.
     * 
     * Cara kerja:
     * 1. Validasi input:
     *    - `user_id`: wajib ada dan terdaftar di tabel `users`.
     *    - `buku_id`: wajib ada dan terdaftar di tabel `bukus`.
     *    - `tanggal_pinjam`: wajib format tanggal yang valid.
     *    - `tanggal_kembali`: wajib format tanggal dan minimal sama atau setelah `tanggal_pinjam`.
     * 2. Mengecek ketersediaan stok buku secara real-time. Jika stok <= 0, kembalikan dengan pesan error.
     * 3. Membuat baris baru di tabel `peminjamans` dengan status default 'dipinjam'.
     * 4. Mengurangi jumlah stok buku sebanyak 1 menggunakan metode `$buku->decrement('stok')`.
     * 5. Redirect ke halaman index peminjaman dengan flash message sukses.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'buku_id'         => 'required|exists:bukus,id',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        // Pengecekan keamanan stok buku
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku ini sedang habis, tidak dapat dipinjam.');
        }

        // Simpan rekod transaksi peminjaman
        Peminjaman::create([
            'user_id'         => $request->user_id,
            'buku_id'         => $request->buku_id,
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => 'dipinjam',
        ]);

        // Kurangi stok buku sebesar 1 secara atomik
        $buku->decrement('stok');

        return redirect()->route('admin.peminjaman.index')->with('success', 'Transaksi peminjaman berhasil dicatat dan stok buku telah dikurangi.');
    }

    /**
     * Memproses pengembalian buku yang sedang dipinjam.
     * 
     * Cara kerja:
     * 1. Memeriksa status peminjaman. Jika status masih 'dipinjam':
     *    a. Ubah status menjadi 'dikembalikan'.
     *    b. Tambahkan kembali stok buku sebanyak 1 menggunakan `$peminjaman->buku->increment('stok')`.
     *    c. Berikan pesan sukses ke user.
     * 2. Jika status sudah 'dikembalikan', sistem tidak akan menambah stok berulang kali (idempotent guard).
     */
    public function updateStatus(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam') {
            $peminjaman->update(['status' => 'dikembalikan']);

            // Kembalikan stok buku ke database
            $peminjaman->buku->increment('stok');

            return redirect()->route('admin.peminjaman.index')->with('success', 'Buku telah berhasil dikembalikan dan stok buku bertambah +1.');
        }

        return redirect()->route('admin.peminjaman.index')->with('error', 'Transaksi ini sudah berstatus dikembalikan sebelumnya.');
    }

    /**
     * Menghapus catatan transaksi peminjaman.
     * 
     * Cara kerja:
     * 1. Jika transaksi dihapus saat buku masih berstatus 'dipinjam' (misal salah input peminjaman),
     *    maka stok buku dikembalikan (+1) agar jumlah stok di gudang/katalog tetap akurat.
     * 2. Menghapus data transaksi dari tabel `peminjamans`.
     * 3. Redirect ke daftar peminjaman dengan pesan sukses.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        // Jika data peminjaman dihapus saat masih status dipinjam, kembalikan stoknya
        if ($peminjaman->status === 'dipinjam' && $peminjaman->buku) {
            $peminjaman->buku->increment('stok');
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data transaksi peminjaman berhasil dihapus.');
    }
}

