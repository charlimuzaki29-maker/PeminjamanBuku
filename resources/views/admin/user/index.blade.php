{{--
    View: resources/views/admin/user/index.blade.php
    Deskripsi: Halaman Manajemen Akun Pengguna / Anggota Perpustakaan
    Cara Kerja:
    1. Menampilkan seluruh pengguna perpustakaan (Admin maupun Siswa) dari `UserController@index`.
    2. Menampilkan badge status role (Admin = Ungu, Siswa/User = Biru).
    3. Menyediakan tombol Tambah Anggota (+), Edit Anggota, dan Hapus Anggota.
    4. Melindungi admin aktif agar tidak bisa menghapus akunnya sendiri yang sedang digunakan.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Kelola Data Anggota & Pengguna') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Manajemen akun pengguna sistem perpustakaan (Siswa & Administrator)</p>
            </div>
            <a href="{{ route('admin.user.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow shadow-indigo-600/20 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Anggota Baru
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
                                <th class="p-3">Nama Lengkap</th>
                                <th class="p-3">Alamat Email</th>
                                <th class="p-3 text-center">Hak Akses (Role)</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $index => $usr)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="p-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="p-3 font-semibold text-gray-900">
                                        {{ $usr->name }}
                                        @if($usr->id === auth()->id())
                                            <span class="ms-1.5 px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-bold">(Akun Anda)</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-gray-600 font-mono text-xs">{{ $usr->email }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $usr->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $usr->role === 'admin' ? '👑 Administrator' : '🎓 Siswa / User' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center space-x-1">
                                        {{-- Tombol Edit User --}}
                                        <a href="{{ route('admin.user.edit', $usr->id) }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-semibold px-3 py-1.5 rounded-lg text-xs shadow transition">
                                            Edit
                                        </a>

                                        {{-- Tombol Hapus User (Dinonaktifkan jika akun sendiri) --}}
                                        @if($usr->id !== auth()->id())
                                            <form action="{{ route('admin.user.destroy', $usr->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $usr->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs shadow transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" disabled class="bg-gray-200 text-gray-400 font-semibold px-3 py-1.5 rounded-lg text-xs cursor-not-allowed">
                                                Terkunci
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400">
                                        Belum ada data anggota perpustakaan.
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