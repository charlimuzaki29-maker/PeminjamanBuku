{{--
    View: resources/views/admin/peminjaman/create.blade.php
    Deskripsi: Form Input Pencatatan Transaksi Peminjaman Buku Baru
    Cara Kerja:
    1. Mengambil data `$users` (anggota siswa) dan `$bukus` (buku dengan stok > 0).
    2. Admin memilih siswa peminjam, buku yang dipinjam, tanggal pinjam, dan tanggal batas kembali.
    3. Mengirim data ke `PeminjamanController@store` via POST (`admin.peminjaman.store`).
    4. Controller akan otomatis mengurangi stok buku fisik sebanyak 1.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Form Peminjaman Buku') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Catat transaksi peminjaman buku oleh anggota perpustakaan</p>
            </div>
            <a href="{{ route('admin.peminjaman.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
                &larr; Kembali ke Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-8">
                
                {{-- Form Kirim ke PeminjamanController@store --}}
                <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Pilih Siswa / Peminjam --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Pilih Siswa / Anggota <span class="text-rose-500">*</span></label>
                        <select name="user_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('user_id') border-rose-500 @enderror" required>
                            <option value="">-- Pilih Anggota Peminjam --</option>
                            @foreach($users as $usr)
                                <option value="{{ $usr->id }}" {{ old('user_id') == $usr->id ? 'selected' : '' }}>
                                    {{ $usr->name }} ({{ $usr->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilih Buku (Stok > 0) --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Pilih Buku (Hanya buku yang stoknya tersedia) <span class="text-rose-500">*</span></label>
                        <select name="buku_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('buku_id') border-rose-500 @enderror" required>
                            <option value="">-- Pilih Judul Buku --</option>
                            @foreach($bukus as $buku)
                                <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                    [{{ $buku->kode_buku }}] {{ $buku->judul }} — (Sisa Stok: {{ $buku->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Pinjam --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('tanggal_pinjam') border-rose-500 @enderror" required>
                        @error('tanggal_pinjam')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Jatuh Tempo Pengembalian (Default +7 Hari) --}}
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 mb-1">Tanggal Batas Pengembalian <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm @error('tanggal_kembali') border-rose-500 @enderror" required>
                        <p class="text-xs text-gray-400 mt-1">Standar batas waktu peminjaman adalah 7 hari kalender.</p>
                        @error('tanggal_kembali')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow shadow-indigo-600/20 text-sm transition">
                            Simpan Transaksi Peminjaman
                        </button>
                        <a href="{{ route('admin.peminjaman.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>