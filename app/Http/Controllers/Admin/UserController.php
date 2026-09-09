<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controller: UserController
 * 
 * Mengelola data pengguna/anggota perpustakaan (siswa maupun sesama admin).
 * Meliputi penambahan akun baru, pengaturan role hak akses, pembaruan data,
 * hashing password yang aman, serta proteksi penghapusan akun sendiri.
 */
class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna/anggota perpustakaan.
     * 
     * Cara kerja:
     * 1. Mengambil semua data pengguna dari database melalui `User::all()` diurutkan berdasarkan role dan nama.
     * 2. Menampilkan data pada view `resources/views/admin/user/index.blade.php`.
     */
    public function index()
    {
        $users = User::orderBy('role', 'asc')->orderBy('name', 'asc')->get();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Menampilkan form untuk mendaftarkan anggota/user baru.
     * 
     * Cara kerja:
     * 1. Mengarahkan admin ke view form `admin.user.create`.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Menyimpan data anggota/user baru ke database.
     * 
     * Cara kerja:
     * 1. Validasi data:
     *    - `name`: wajib string maksimal 255 karakter.
     *    - `email`: wajib format email valid & unik di tabel `users`.
     *    - `password`: wajib minimal 6 karakter.
     *    - `role`: wajib bernilai 'admin' atau 'user'.
     * 2. Meng-enkripsi/hash password menggunakan `Hash::make(...)` dengan algoritma Bcrypt/Argon2.
     * 3. Menyimpan baris baru ke tabel `users`.
     * 4. Redirect ke daftar anggota dengan notifikasi sukses.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'Data anggota baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data anggota.
     * 
     * Cara kerja:
     * 1. Laravel mencari user sesuai ID menggunakan Route-Model Binding.
     * 2. Mengirim data `$user` ke view `admin.user.edit`.
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Menyimpan pembaruan profil/role anggota.
     * 
     * Cara kerja:
     * 1. Validasi input nama, email (dengan pengecualian ID saat ini), dan role.
     * 2. Mempersiapkan data update.
     * 3. Jika input `password` diisi oleh admin, maka validasi dan hash password baru.
     *    Jika dikosongkan, password lama tidak akan tertimpa/berubah.
     * 4. Memperbarui rekod pada database melalui `$user->update(...)`.
     * 5. Redirect ke daftar anggota dengan notifikasi berhasil.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,user',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        // Jika kolom password diisi, lakukan hash dan masukkan ke data update
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Menghapus akun anggota dari database.
     * 
     * Cara kerja:
     * 1. Pengecekan keamanan: Jika ID user yang akan dihapus sama dengan ID admin yang sedang login
     *    (`auth()->id()`), tolak aksi untuk mencegah admin terkunci keluar (lockout prevention).
     * 2. Jalankan `$user->delete()`.
     * 3. Redirect ke daftar anggota dengan notifikasi berhasil.
     */
    public function destroy(User $user)
    {
        // Mencegah admin menghapus akunnya sendiri yang sedang aktif
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index')->with('error', 'Anda tidak dapat menghapus akun sendiri yang sedang digunakan saat ini.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'Data anggota berhasil dihapus dari sistem.');
    }
}

