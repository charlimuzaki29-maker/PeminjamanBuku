{{--
    View: resources/views/admin/buku/create.blade.php
    Deskripsi: Form Input Penambahan Data Buku Baru
    Cara Kerja:
    1. Mengirim data form via method POST ke route `admin.buku.store`.
    2. Menyertakan token keamanan CSRF (`@csrf`).
    3. Menampilkan pesan error validasi di bawah setiap input jika ada isian yang tidak valid.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Tambah Buku Baru') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Isi data buku baru ke dalam katalog perpustakaan</p>
            </div>
            <a href="{{ route('admin.buku.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
                &larr; Kembali ke Daftar Buku
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-8">
                
                {{-- Form Kirim ke BukuController@store --}}
                <form action="{{ route('admin.buku.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Kode Buku --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Kode Buku (Unik) <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode_buku" value="{{ old('kode_buku') }}" placeholder="Contoh: BK-001" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('kode_buku') border-rose-500 @enderror" required>
                        @error('kode_buku')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Judul Buku --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Judul Buku <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul buku lengkap" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('judul') border-rose-500 @enderror" required>
                        @error('judul')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pengarang --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Nama Pengarang / Penulis <span class="text-rose-500">*</span></label>
                        <input type="text" name="pengarang" value="{{ old('pengarang') }}" placeholder="Nama pengarang buku" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('pengarang') border-rose-500 @enderror" required>
                        @error('pengarang')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Penerbit --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Penerbit <span class="text-rose-500">*</span></label>
                        <input type="text" name="penerbit" value="{{ old('penerbit') }}" placeholder="Nama penerbit buku" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('penerbit') border-rose-500 @enderror" required>
                        @error('penerbit')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah Stok --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Jumlah Stok Fisik <span class="text-rose-500">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" placeholder="Jumlah stok buku" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('stok') border-rose-500 @enderror" required>
                        @error('stok')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow shadow-indigo-600/20 text-sm transition">
                            Simpan Buku Baru
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