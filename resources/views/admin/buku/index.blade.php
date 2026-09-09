{{--
    View: resources/views/admin/buku/index.blade.php
    Deskripsi: Halaman Manajemen Katalog Buku Perpustakaan
    Cara Kerja:
    1. Menerima variabel `$bukus` dari `BukuController@index`.
    2. Menampilkan tabel daftar buku dengan kolom Kode Buku, Judul, Pengarang, Penerbit, dan Stok.
    3. Menyediakan tombol Tambah Buku (+), Edit Buku, dan Hapus Buku (DELETE method spoofing).
    4. Menampilkan alert notifikasi flash session ('success' atau 'error').
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Kelola Data Buku Perpustakaan') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Manajemen katalog, pengarang, penerbit, dan inventaris stok buku</p>
            </div>
            <a href="{{ route('admin.buku.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow shadow-indigo-600/20 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Buku Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Flash Alert Notifikasi Sukses / Error --}}
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200 text-xs uppercase font-semibold">
                                <th class="p-3 text-center">No</th>
                                <th class="p-3 text-center">Kode Buku</th>
                                <th class="p-3">Judul Buku</th>
                                <th class="p-3">Pengarang</th>
                                <th class="p-3">Penerbit</th>
                                <th class="p-3 text-center">Stok</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bukus as $index => $buku)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="p-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="p-3 text-center font-mono text-xs font-semibold text-gray-700">{{ $buku->kode_buku }}</td>
                                    <td class="p-3 font-semibold text-gray-900">{{ $buku->judul }}</td>
                                    <td class="p-3 text-gray-600">{{ $buku->pengarang }}</td>
                                    <td class="p-3 text-gray-600">{{ $buku->penerbit }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $buku->stok > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $buku->stok }} Tersedia
                                        </span>
                                    </td>
                                    <td class="p-3 text-center space-x-1">
                                        {{-- Tombol Edit Buku --}}
                                        <a href="{{ route('admin.buku.edit', $buku->id) }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-semibold px-3 py-1.5 rounded-lg text-xs shadow transition">
                                            Edit
                                        </a>

                                        {{-- Form Hapus Buku dengan Spoofing Method DELETE --}}
                                        <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ $buku->judul }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs shadow transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400">
                                        Belum ada data buku di perpustakaan. Klik tombol "+ Tambah Buku Baru" untuk menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>