{{--
    View: resources/views/admin/dashboard.blade.php
    Deskripsi: Halaman Dashboard Utama untuk Administrator Perpustakaan
    Fitur:
    1. Menampilkan kartu statistik ringkas (Total Buku, Total Anggota, Buku Sedang Dipinjam, Total Peminjaman).
    2. Tombol shortcut / aksi cepat untuk mencatat peminjaman baru, menambah buku, dan mengelola user.
    3. Tabel ringkasan 5 transaksi peminjaman terbaru.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Administrator') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Sistem Informasi Pengelolaan & Peminjaman Perpustakaan Digital</p>
            </div>
            <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-200 text-indigo-700 px-3 py-1.5 rounded-full text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Login sebagai: {{ auth()->user()->name }} (Admin)
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Kartu Statistik Ringkas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                {{-- Kartu Total Judul Buku --}}
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Judul Buku</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalBuku ?? 0 }}</h3>
                            <p class="text-xs text-emerald-600 mt-1 font-medium">Total stok: {{ $totalStokBuku ?? 0 }} eksemplar</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Kartu Total Anggota / Siswa --}}
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Anggota Siswa</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalUser ?? 0 }}</h3>
                            <p class="text-xs text-indigo-600 mt-1 font-medium">Terdaftar aktif di sistem</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Kartu Sedang Dipinjam --}}
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Dipinjam</p>
                            <h3 class="text-3xl font-extrabold text-amber-600 mt-1">{{ $totalDipinjam ?? 0 }}</h3>
                            <p class="text-xs text-amber-600 mt-1 font-medium">Belum dikembalikan</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Kartu Total Transaksi --}}
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
                            <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $totalPeminjaman ?? 0 }}</h3>
                            <p class="text-xs text-emerald-600 mt-1 font-medium">Sepanjang riwayat</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 2. Tombol Shortcut / Aksi Cepat --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Aksi Cepat Menu Utama
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('admin.peminjaman.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-lg">+</div>
                        <div>
                            <h4 class="font-semibold text-sm">Catat Peminjaman Baru</h4>
                            <p class="text-xs text-emerald-700">Form input transaksi peminjaman</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.buku.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-800 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-lg">+</div>
                        <div>
                            <h4 class="font-semibold text-sm">Tambah Buku Baru</h4>
                            <p class="text-xs text-blue-700">Tambahkan judul & stok buku baru</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.user.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-800 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-lg">+</div>
                        <div>
                            <h4 class="font-semibold text-sm">Tambah Anggota Baru</h4>
                            <p class="text-xs text-purple-700">Daftarkan akun siswa atau admin baru</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- 3. Tabel Aktivitas Peminjaman Terbaru --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">5 Transaksi Peminjaman Terbaru</h3>
                        <p class="text-xs text-gray-500">Daftar transaksi peminjaman buku yang baru saja dicatat</p>
                    </div>
                    <a href="{{ route('admin.peminjaman.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 underline">
                        Lihat Semua Transaksi &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200 text-xs uppercase font-semibold">
                                <th class="p-3">Peminjam</th>
                                <th class="p-3">Judul Buku</th>
                                <th class="p-3">Tgl Pinjam</th>
                                <th class="p-3">Batas Kembali</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentPeminjamans ?? [] as $item)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="p-3 font-medium text-gray-900">{{ $item->user->name ?? '-' }}</td>
                                    <td class="p-3 text-gray-700">{{ $item->buku->judul ?? '-' }}</td>
                                    <td class="p-3 text-gray-600">{{ $item->tanggal_pinjam }}</td>
                                    <td class="p-3 text-gray-600">{{ $item->tanggal_kembali }}</td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->status === 'dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-400">
                                        Belum ada transaksi peminjaman yang dicatat.
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