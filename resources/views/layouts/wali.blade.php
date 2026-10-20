<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal Wali Santri - Pesantren Modern' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100 selection:bg-emerald-500 selection:text-white">
    <!-- Mobile App Container (Optimized for Mobile Screens & Centered on Desktop) -->
    <div class="min-h-screen max-w-md mx-auto bg-slate-50 flex flex-col relative shadow-2xl border-x border-slate-200/80">

        <!-- Mobile Top App Header -->
        <header class="sticky top-0 z-40 bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-700 text-white px-5 py-4 shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center font-bold text-white shadow-inner">
                        <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-500/30 text-emerald-100 border border-emerald-400/30">Portal Wali</span>
                        </div>
                        <h1 class="text-base font-bold text-white tracking-tight">Pesantren Digital</h1>
                    </div>
                </div>

                <!-- Header Actions (Logout) -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" title="Keluar / Logout" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-rose-500/80 transition-colors flex items-center justify-center text-white border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body Content (With safe padding for Bottom Navigation Bar) -->
        <main class="flex-1 px-4 py-5 pb-24 overflow-y-auto space-y-5">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>

        <!-- Mobile Bottom Navigation Bar (Fixed at bottom) -->
        <nav class="fixed bottom-0 inset-x-0 mx-auto max-w-md bg-white/95 backdrop-blur-md border-t border-slate-200/90 z-50 px-3 py-2 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
            <div class="grid grid-cols-4 items-center">
                
                <!-- 1. Beranda -->
                <a href="{{ route('wali.dashboard') }}" 
                   class="flex flex-col items-center justify-center py-1 transition-all group {{ request()->routeIs('wali.dashboard') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-emerald-600 font-medium' }}">
                    <div class="relative p-1 rounded-xl transition-all {{ request()->routeIs('wali.dashboard') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="text-[11px] tracking-tight mt-0.5">Beranda</span>
                </a>

                <!-- 2. E-Wallet -->
                <a href="#wallet" 
                   class="flex flex-col items-center justify-center py-1 text-slate-500 hover:text-emerald-600 font-medium transition-all group">
                    <div class="p-1 rounded-xl group-hover:bg-emerald-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] tracking-tight mt-0.5">E-Wallet</span>
                </a>

                <!-- 3. SPP -->
                <a href="#spp" 
                   class="flex flex-col items-center justify-center py-1 text-slate-500 hover:text-emerald-600 font-medium transition-all group">
                    <div class="p-1 rounded-xl group-hover:bg-emerald-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] tracking-tight mt-0.5">SPP</span>
                </a>

                <!-- 4. Profil -->
                <a href="{{ route('profile.edit') }}" 
                   class="flex flex-col items-center justify-center py-1 transition-all group {{ request()->routeIs('profile.edit') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-emerald-600 font-medium' }}">
                    <div class="p-1 rounded-xl group-hover:bg-emerald-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] tracking-tight mt-0.5">Profil</span>
                </a>
            </div>
        </nav>
    </div>
</body>
</html>
