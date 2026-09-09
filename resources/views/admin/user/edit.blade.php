{{--
    View: resources/views/admin/user/edit.blade.php
    Deskripsi: Form Edit Data Pengguna / Anggota Perpustakaan
    Cara Kerja:
    1. Mengisi form dengan data user yang ada (`$user`).
    2. Password bersifat opsional: jika tidak diisi, password lama tidak akan ditimpa.
    3. Mengirim pembaruan ke `UserController@update` via PUT (`admin.user.update`).
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Data Anggota') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui identitas, email, dan hak akses anggota</p>
            </div>
            <a href="{{ route('admin.user.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
                &larr; Kembali ke Daftar Anggota
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-8">
                
                {{-- Form Kirim ke UserController@update --}}
                <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('name') border-rose-500 @enderror" required>
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Email --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('email') border-rose-500 @enderror" required>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru (Opsional) --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">
                            Password Baru 
                            <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin mengubah password)</span>
                        </label>
                        <input type="password" name="password" placeholder="Biarkan kosong jika tidak diubah" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('password') border-rose-500 @enderror">
                        @error('password')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hak Akses (Role) --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Hak Akses (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('role') border-rose-500 @enderror" required>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Siswa / Anggota (User Biasa)</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow shadow-indigo-600/20 text-sm transition">
                            Simpan Perubahan
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