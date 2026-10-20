
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Dashboard - Pesantren Modern' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-slate-800" x-data="{ sidebarOpen: false }">
    <div class="flex flex-col min-h-screen lg:flex-row bg-slate-100">

        <!-- Mobile Sidebar Overlay -->
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
            style="display: none;">
        </div>

        <!-- Sidebar Navigation -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 flex flex-col text-white transition-transform duration-300 ease-in-out shadow-2xl w-72 bg-emerald-900 lg:static lg:inset-auto lg:translate-x-0 lg:shadow-none">

            <!-- Brand / Logo -->
            <div class="flex items-center justify-between h-20 px-6 border-b bg-emerald-950/60 border-emerald-800/40">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 font-bold shadow-md rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-300 text-emerald-950 shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-emerald-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>

                    <div>
                        <span class="block text-lg font-bold tracking-wide text-emerald-50">E-Pesantren</span>
                        <span class="block text-xs font-medium text-emerald-300/80">Panel Administrasi</span>
                    </div>
                </a>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="p-1 rounded-lg lg:hidden text-emerald-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

                <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-emerald-400/80">
                    Menu Utama
                </div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span>Dashboard</span>
                </a>

                <!-- Pendaftaran (PSB) -->
                <a href="{{ route('admin.psb.index') }}"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.psb.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    <span>Pendaftaran (PSB)</span>
                </a>

                <!-- Data Santri -->
                <a href="{{ route('admin.santri.index') }}"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.santri.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span>Data Santri</span>
                </a>

                <!-- KEUANGAN Section -->
                <div class="pt-3">
                    <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-emerald-400/80">Keuangan</div>

                    <a href="{{ route('admin.spp.index') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.spp.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        <span>SPP Santri</span>
                    </a>

                    <a href="{{ route('admin.wallet.index') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.wallet.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        <span>E-Wallet Santri</span>
                    </a>

                    <a href="{{ route('admin.kas.index') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.kas.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Kas Pondok</span>
                    </a>

                    <a href="{{ route('admin.donasi.index') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.donasi.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        <span>Donasi &amp; Infaq</span>
                    </a>

                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        <span>Laporan Keuangan</span>
                    </a>
                </div>

                <!-- KOPERASI Section -->
                <div class="pt-3">
                    <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-emerald-400/80">Koperasi</div>

                    <a href="{{ route('admin.koperasi.products') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.koperasi.products*') || request()->routeIs('admin.koperasi.index') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span>Manajemen Produk</span>
                    </a>

                    <a href="{{ route('admin.koperasi.kasir') }}"
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.koperasi.kasir') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span>Kasir (POS)</span>
                    </a>
                </div>
            </nav>

                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Pendaftaran (PSB) -->
                <a
                    href="{{ route('admin.psb.index') }}"
                    class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.psb.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30 font-semibold' : 'text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white' }}">
                    <div class="flex items-center gap-3.5">
                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Pendaftaran (PSB)</span>
                    </div>

                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-300">
                        Pilar 1
                    </span>
                </a>

                <!-- Keuangan (E-Wallet & SPP) -->
                <a
                    href="#"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white transition-all">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>Keuangan &amp; SPP</span>
                </a>

                <!-- Koperasi (Kopontren) -->
                <a
                    href="#"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white transition-all">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Koperasi (Kopontren)</span>
                </a>

                <!-- Donasi (Wakaf) -->
                <a
                    href="#"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-medium text-emerald-100/90 hover:bg-emerald-800/60 hover:text-white transition-all">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>Wakaf &amp; Donasi</span>
                </a>
            </nav>

            <!-- Bottom User Profile Card -->
            <div class="p-4 border-t border-emerald-800/40 bg-emerald-950/40">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 font-bold rounded-full bg-emerald-700 text-emerald-100">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </p>
                        <p class="text-xs truncate text-emerald-300">
                            {{ Auth::user()->email ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            <!-- Top Navbar -->
            <header class="sticky top-0 z-30 flex items-center justify-between h-20 px-4 bg-white border-b shadow-sm border-slate-200/80 sm:px-8">
                <div class="flex items-center gap-4">

                    <!-- Mobile Hamburger -->
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="p-2 rounded-lg lg:hidden text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-lg font-bold sm:text-xl text-slate-800">
                            @yield('page_title', 'Dashboard Administrasi')
                        </h1>
                        <p class="hidden text-xs text-slate-500 sm:block">
                            Kelola seluruh aktivitas dan operasional pesantren modern
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">

                    <!-- Role Pill Badge -->
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold border rounded-full bg-emerald-100 text-emerald-800 border-emerald-200">
                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-600"></span>
                        Admin
                    </span>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button
                            type="submit"
                            class="flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold text-rose-600 hover:text-white hover:bg-rose-600 rounded-xl transition-all border border-rose-200 hover:border-transparent">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Page Slot -->
            <main class="flex-1 p-4 overflow-y-auto sm:p-6 lg:p-8">
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>
</body>

</html>
