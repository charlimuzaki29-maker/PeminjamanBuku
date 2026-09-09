{{--
    View: resources/views/dashboard.blade.php
    Deskripsi: Halaman Dashboard Utama untuk Siswa / Anggota Perpustakaan
    Fitur:
    1. Menyapa siswa yang sedang login.
    2. Menampilkan riwayat dan status peminjaman buku milik siswa yang bersangkutan.
    3. Menampilkan katalog seluruh buku di perpustakaan beserta ketersediaan stoknya.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Siswa - Perpustakaan Digital') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Portal Layanan Peminjaman Buku & Informasi Katalog Perpustakaan</p>
            </div>
            <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 px-3 py-1.5 rounded-full text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                Anggota: {{ auth()->user()->name }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Kartu Banner Selamat Datang --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-md p-6 text-white">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm">Selamat Datang 👋</span>
                        <h3 class="text-2xl font-extrabold mt-2">{{ auth()->user()->name }}</h3>
                        <p class="text-blue-100 text-sm mt-1 max-w-2xl">
                            Anda login sebagai Anggota Perpustakaan. Pantau status peminjaman buku Anda secara berkala agar tidak terlambat mengembalikan buku.
                        </p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 text-center min-w-[140px]">
                        <p class="text-xs text-blue-200 font-medium">Buku Sedang Dipinjam</p>
                        <p class="text-3xl font-black mt-1">{{ $myPeminjamans->where('status', 'dipinjam')->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- 2. Tabel Riwayat Peminjaman Siswa --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Riwayat & Status Peminjaman Saya
                        </h3>
                        <p class="text-xs text-gray-500">Daftar buku yang pernah atau sedang Anda pinjam di perpustakaan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200 text-xs uppercase font-semibold">
                                <th class="p-3 text-center">No</th>
                                <th class="p-3 text-center">Kode Buku</th>
                                <th class="p-3">Judul Buku</th>
                                <th class="p-3 text-center">Tgl Pinjam</th>
                                <th class="p-3 text-center">Batas Pengembalian</th>
                                <th class="p-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($myPeminjamans as $index => $p)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="p-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="p-3 text-center font-mono text-xs font-semibold text-gray-700">{{ $p->buku->kode_buku ?? '-' }}</td>
                                    <td class="p-3 font-semibold text-gray-800">{{ $p->buku->judul ?? 'Buku telah dihapus dari katalog' }}</td>
                                    <td class="p-3 text-center text-gray-600">{{ $p->tanggal_pinjam }}</td>
                                    <td class="p-3 text-center text-gray-600">{{ $p->tanggal_kembali }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $p->status === 'dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $p->status === 'dipinjam' ? '⏳ Sedang Dipinjam' : '✅ Sudah Dikembalikan' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400">
                                        Anda belum memiliki catatan transaksi peminjaman buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. Tabel Katalog Buku Perpustakaan --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Daftar Katalog Buku Perpustakaan
                    </h3>
                    <p class="text-xs text-gray-500">Koleksi buku bacaan & referensi yang tersedia di perpustakaan</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200 text-xs uppercase font-semibold">
                                <th class="p-3 text-center">No</th>
                                <th class="p-3 text-center">Kode</th>
                                <th class="p-3">Judul Buku</th>
                                <th class="p-3">Pengarang</th>
                                <th class="p-3">Penerbit</th>
                                <th class="p-3 text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bukus as $index => $buku)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="p-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="p-3 text-center font-mono text-xs font-semibold text-gray-700">{{ $buku->kode_buku }}</td>
                                    <td class="p-3 font-semibold text-gray-800">{{ $buku->judul }}</td>
                                    <td class="p-3 text-gray-600">{{ $buku->pengarang }}</td>
                                    <td class="p-3 text-gray-600">{{ $buku->penerbit }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $buku->stok > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $buku->stok > 0 ? $buku->stok . ' Tersedia' : 'Habis' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400">
                                        Belum ada koleksi buku di perpustakaan saat ini.
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