{{--
    View: resources/views/layouts/navigation.blade.php
    Deskripsi: Komponen Bar Navigasi Atas (Top Navigation Bar)
    Cara Kerja:
    1. Memeriksa role pengguna yang sedang login (`Auth::user()->role`).
    2. Jika 'admin': Menampilkan menu Dashboard Admin, Kelola Buku, Kelola Anggota, dan Transaksi Peminjaman.
    3. Jika 'user': Menampilkan menu Dashboard Siswa (Katalog & Riwayat Pinjaman).
    4. Menampilkan dropdown profil pengguna, penanda role (badge), dan form logout dengan proteksi CSRF.
--}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo & Brand Aplikasi -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-800 text-lg hidden md:inline">Perpus<span class="text-indigo-600">Digital</span></span>
                    </a>
                </div>

                <!-- Navigation Links Berdasarkan Hak Akses Role -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-8 sm:flex">
                    @if(Auth::user()->role === 'admin')
                        {{-- Menu Khusus Administrator --}}
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.buku.index')" :active="request()->routeIs('admin.buku.*')">
                            {{ __('Kelola Buku') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                            {{ __('Kelola Anggota') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.peminjaman.index')" :active="request()->routeIs('admin.peminjaman.*')">
                            {{ __('Transaksi Peminjaman') }}
                        </x-nav-link>
                    @else
                        {{-- Menu Khusus Siswa / User --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard & Katalog') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings & Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="font-semibold">{{ Auth::user()->name }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase tracking-wider {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Profil Pengguna --}}
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <!-- Logout Form dengan Proteksi CSRF Token -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-rose-600 hover:text-rose-700 font-medium">
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Responsive Mobile Button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.buku.index')" :active="request()->routeIs('admin.buku.*')">
                    {{ __('Kelola Buku') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                    {{ __('Kelola Anggota') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.peminjaman.index')" :active="request()->routeIs('admin.peminjaman.*')">
                    {{ __('Transaksi Peminjaman') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard & Katalog') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Mobile Profile Options -->
        <div class="pt-4 pb-3 border-t border-gray-200 px-4">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }} ({{ ucfirst(Auth::user()->role) }})</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil Saya') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-rose-600 font-medium">
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
