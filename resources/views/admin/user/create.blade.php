{{--
    View: resources/views/admin/user/create.blade.php
    Deskripsi: Form Input Penambahan Anggota / User Baru
    Cara Kerja:
    1. Admin mengisi nama, email, password, dan memilih role ('admin' atau 'user').
    2. Data dikirim ke `UserController@store` via POST (`admin.user.store`).
    3. Controller melakukan hashing password dengan `Hash::make()` sebelum disimpan.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Tambah Anggota / Pengguna Baru') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Daftarkan akun pengguna baru ke dalam sistem perpustakaan</p>
            </div>
            <a href="{{ route('admin.user.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
                &larr; Kembali ke Daftar Anggota
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-8">
                
                {{-- Form Kirim ke UserController@store --}}
                <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('name') border-rose-500 @enderror" required>
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Email --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Alamat Email (Unik) <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: budi@sekolah.sch.id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('email') border-rose-500 @enderror" required>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Password Awal (Min. 6 Karakter) <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" placeholder="Masukkan kata sandi awal akun" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('password') border-rose-500 @enderror" required>
                        @error('password')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilihan Role --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Hak Akses (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('role') border-rose-500 @enderror" required>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Siswa / Anggota (Hanya lihat katalog & pinjaman sendiri)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses penuh kelola perpustakaan)</option>
                        </select>
                        @error('role')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow shadow-indigo-600/20 text-sm transition">
                            Simpan Anggota Baru
                        </button>
                        <a href="{{ route('admin.user.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>