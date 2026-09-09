<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Controller: ProfileController
 * 
 * Mengelola pengelolaan profil pengguna yang sedang login (baik Admin maupun Siswa).
 * Meliputi pembaruan nama/email, reset status verifikasi jika email berganti,
 * serta penghapusan akun mandiri secara aman.
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil akun sendiri.
     * 
     * Cara kerja:
     * 1. Mengambil data user yang sedang login dari `$request->user()`.
     * 2. Menampilkan view form edit `resources/views/profile/edit.blade.php`.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     * 
     * Cara kerja:
     * 1. Menggunakan form request `ProfileUpdateRequest` untuk validasi nama dan email.
     * 2. Mengisi model User dengan data yang tervalidasi (`$request->user()->fill(...)`).
     * 3. Jika email diubah (`isDirty('email')`), reset `email_verified_at` menjadi null.
     * 4. Simpan perubahan ke database dan redirect kembali dengan notifikasi 'profile-updated'.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna yang sedang login secara permanen.
     * 
     * Cara kerja:
     * 1. Memvalidasi password saat ini (`current_password`) untuk konfirmasi otentikasi.
     * 2. Logout sesi pengguna (`Auth::logout()`).
     * 3. Hapus data pengguna dari database (`$user->delete()`).
     * 4. Invalidate session dan regenerate CSRF token untuk mencegah session fixation.
     * 5. Redirect ke halaman utama ('/').
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

