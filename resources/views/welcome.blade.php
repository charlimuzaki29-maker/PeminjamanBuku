{{--
    View: resources/views/welcome.blade.php
    Deskripsi: Halaman Utama (Landing Page) Sistem Perpustakaan Digital
    Fitur:
    1. Memperkenalkan aplikasi sistem peminjaman perpustakaan berbasis Laravel.
    2. Menyediakan akses cepat ke halaman Login & Register.
    3. Memberikan kartu informasi akun demo (Admin & Siswa) untuk pengujian langsung.
    4. Menyajikan ringkasan fitur utama sistem (Manajemen Buku, Transaksi, Auto-stok).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistem Peminjaman Perpustakaan Digital</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts Vite -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-slate-100 min-h-screen flex flex-col font-sans selection:bg-indigo-500 selection:text-white">
        
        {{-- Navbar Header --}}
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-white leading-tight">PerpusDigital</h1>
                    <p class="text-xs text-indigo-300">Sistem Peminjaman Buku</p>
                </div>
            </div>

            <nav class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow transition">
                            Dashboard Admin &rarr;
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow transition">
                            Dashboard Siswa &rarr;
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-200 hover:text-white transition">
                        Masuk (Log in)
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow shadow-indigo-600/30 transition">
                            Daftar Akun
                        </a>
                    @endif
                @endauth
            </nav>
        </header>

        {{-- Main Hero Section --}}
        <main class="flex-1 flex flex-col items-center justify-center px-6 py-10 max-w-5xl mx-auto w-full text-center">
            
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                Sistem Peminjaman Perpustakaan Berbasis Laravel
            </div>

            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight max-w-3xl leading-tight">
                Kelola Koleksi Buku & Transaksi Peminjaman Lebih Mudah
            </h2>

            <p class="mt-4 text-slate-300 text-base sm:text-lg max-w-2xl">
                Aplikasi perpustakaan modern dengan manajemen stok otomatis, pemisahan hak akses Admin & Siswa, serta pencatatan riwayat peminjaman yang transparan.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/40 transition-all text-sm">
                        Buka Dashboard Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/40 transition-all text-sm">
                        Mulai Masuk Aplikasi
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-bold rounded-xl transition text-sm">
                        Registrasi Siswa Baru
                    </a>
                @endauth
            </div>

            {{-- Kartu Kredensial Demo Akun --}}
            <div class="mt-14 w-full text-left bg-slate-800/60 backdrop-blur-xl border border-slate-700/60 rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <h3 class="font-bold text-sm text-white uppercase tracking-wider">Akun Demo Pengujian (Seeder)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Akun Admin --}}
                    <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-700/80">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-purple-500/20 text-purple-300 border border-purple-500/30">Role: Admin</span>
                            <span class="text-xs text-slate-400">Akses Penuh</span>
                        </div>
                        <p class="text-xs text-slate-400">Email: <span class="font-mono text-white font-semibold">admin@perpus.com</span></p>
                        <p class="text-xs text-slate-400 mt-1">Password: <span class="font-mono text-white font-semibold">password123</span></p>
                        <p class="text-xs text-indigo-300 mt-2 font-medium">Bisa: Kelola Buku, Anggota, Catat Pinjam, Pengembalian, dan Pantau Statistik.</p>
                    </div>

                    {{-- Akun Siswa --}}
                    <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-700/80">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">Role: User (Siswa)</span>
                            <span class="text-xs text-slate-400">Akses Siswa</span>
                        </div>
                        <p class="text-xs text-slate-400">Email: <span class="font-mono text-white font-semibold">siswa@perpus.com</span></p>
                        <p class="text-xs text-slate-400 mt-1">Password: <span class="font-mono text-white font-semibold">password123</span></p>
                        <p class="text-xs text-blue-300 mt-2 font-medium">Bisa: Lihat Katalog Buku & Pantau Status / Batas Waktu Pinjaman Sendiri.</p>
                    </div>
                </div>
            </div>

            {{-- Kartu Fitur Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8 w-full text-left">
                <div class="p-5 rounded-xl bg-slate-800/40 border border-slate-700/50">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-sm mb-3">01</div>
                    <h4 class="font-bold text-white text-sm">Manajemen Buku</h4>
                    <p class="text-xs text-slate-400 mt-1">Kelola data buku perpustakaan (judul, pengarang, penerbit, kode unik, dan stok).</p>
                </div>
                <div class="p-5 rounded-xl bg-slate-800/40 border border-slate-700/50">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm mb-3">02</div>
                    <h4 class="font-bold text-white text-sm">Stok Otomatis</h4>
                    <p class="text-xs text-slate-400 mt-1">Stok buku otomatis berkurang saat dipinjam dan otomatis bertambah saat dikembalikan.</p>
                </div>
                <div class="p-5 rounded-xl bg-slate-800/40 border border-slate-700/50">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-sm mb-3">03</div>
                    <h4 class="font-bold text-white text-sm">Otorisasi Role Multi-Level</h4>
                    <p class="text-xs text-slate-400 mt-1">Keamanan terproteksi middleware dengan pemisahan akses Admin dan Siswa.</p>
                </div>
            </div>

        </main>

        {{-- Footer --}}
        <footer class="w-full text-center py-6 text-xs text-slate-500 border-t border-slate-800/80">
            &copy; {{ date('Y') }} Sistem Informasi Perpustakaan. Dikembangkan dengan Laravel & Tailwind CSS.
        </footer>
    </body>
</html>
