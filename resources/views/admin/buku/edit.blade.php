{{--
    View: resources/views/admin/buku/edit.blade.php
    Deskripsi: Form Edit Data Buku Perpustakaan
    Cara Kerja:
    1. Menampilkan data buku yang ada saat ini (`$buku`) ke dalam form input.
    2. Mengirim perubahan ke route `admin.buku.update` menggunakan method spoofing `@method('PUT')`.
    3. Menyertakan CSRF protection (`@csrf`).
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Data Buku') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi katalog dan jumlah stok buku</p>
            </div>
            <a href="{{ route('admin.buku.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
                &larr; Kembali ke Daftar Buku
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-8">
                
                {{-- Form Kirim ke BukuController@update --}}
                <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Kode Buku --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Kode Buku <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode_buku" value="{{ old('kode_buku', $buku->kode_buku) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('kode_buku') border-rose-500 @enderror" required>
                        @error('kode_buku')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Judul Buku --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Judul Buku <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('judul') border-rose-500 @enderror" required>
                        @error('judul')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pengarang --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Nama Pengarang <span class="text-rose-500">*</span></label>
                        <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('pengarang') border-rose-500 @enderror" required>
                        @error('pengarang')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Penerbit --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Penerbit <span class="text-rose-500">*</span></label>
                        <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('penerbit') border-rose-500 @enderror" required>
                        @error('penerbit')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Jumlah Stok Fisik <span class="text-rose-500">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" min="0" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('stok') border-rose-500 @enderror" required>
                        @error('stok')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow shadow-indigo-600/20 text-sm transition">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.buku.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>