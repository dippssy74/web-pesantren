@extends('layouts.admin')

@section('page_title', 'Dashboard Administrasi')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-700 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-600/50 backdrop-blur-sm border border-emerald-400/30 text-emerald-100">
                Tahun Ajaran 2026/2027
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Ahlan wa Sahlan, {{ Auth::user()->name }}!</h2>
            <p class="text-emerald-100/90 text-sm max-w-xl">
                Sistem Pusat Manajemen Terpadu Pesantren Modern. Pantau pendaftaran santri baru, alur kas e-wallet, unit bisnis kopontren, dan transparansi donasi dalam satu tempat.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs text-emerald-200">Status Sistem</p>
                <p class="text-sm font-bold text-white flex items-center gap-1.5 justify-end">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Online & Aman
                </p>
            </div>
        </div>
    </div>

    <!-- 4 Pillars Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Pilar 1: PSB Online -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">PSB Online</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['pendaftar_baru'] }} <span class="text-xs font-normal text-slate-500">Santri</span></h3>
                    <p class="text-xs text-emerald-600 font-medium mt-1">Γåæ +14% dari minggu lalu</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total Santri Aktif</span>
                <span class="font-bold text-slate-700">{{ $stats['total_santri'] }}</span>
            </div>
        </div>

        <!-- Pilar 2: Cashless & E-Wallet -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo Kas & Wallet</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($stats['saldo_kas'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-emerald-600 font-medium mt-1">Transaksi lancar</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Sistem E-Wallet</span>
                <span class="font-bold text-emerald-600">Aktif</span>
            </div>
        </div>

        <!-- Pilar 3: Kopontren E-Commerce -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Omset Kopontren</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($stats['transaksi_kopontren'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-emerald-600 font-medium mt-1">Bulan Berjalan</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Unit Bisnis Santri</span>
                <span class="font-bold text-slate-700">Koperasi Aktif</span>
            </div>
        </div>

        <!-- Pilar 4: Wakaf & Donasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Donasi & Wakaf</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($stats['donasi_terkumpul'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-emerald-600 font-medium mt-1">Transparan & Terlapor</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Campaign Aktif</span>
                <span class="font-bold text-slate-700">4 Penggalangan</span>
            </div>
        </div>
    </div>

    <!-- Recent Registrations & Quick Actions Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Pendaftaran Terbaru (PSB) Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Pendaftaran Santri Terbaru (PSB)</h3>
                    <p class="text-xs text-slate-500">Daftar calon santri yang mendaftar melalui modul PSB Online</p>
                </div>
                <a href="{{ route('admin.psb.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua ΓåÆ</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3">No. Registrasi</th>
                            <th class="py-3 px-3">Nama Santri</th>
                            <th class="py-3 px-3">Wali Santri</th>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($realRegistrations as $reg)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-3 font-mono font-bold text-xs text-emerald-800">{{ $reg->no_pendaftaran }}</td>
                            <td class="py-3.5 px-3 font-semibold text-slate-800">{{ $reg->nama_lengkap }}</td>
                            <td class="py-3.5 px-3 text-slate-600">{{ $reg->nama_wali }}</td>
                            <td class="py-3.5 px-3 text-slate-500 text-xs">{{ $reg->created_at->format('d M Y') }}</td>
                            <td class="py-3.5 px-3">
                                @if ($reg->status === 'lulus_berkas')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Lulus Berkas
                                    </span>
                                @elseif ($reg->status === 'ditolak')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        Menunggu Verifikasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 text-sm">Belum ada pendaftaran santri baru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions & System Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4">Aksi Cepat Admin</h3>
                <div class="space-y-2.5">
                    <a href="{{ route('admin.psb.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 font-medium text-sm transition-all group">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Verifikasi Berkas PSB
                        </span>
                        <span class="text-xs text-slate-400 group-hover:text-emerald-600">ΓåÆ</span>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 font-medium text-sm transition-all group">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Top Up Wallet Manual
                        </span>
                        <span class="text-xs text-slate-400 group-hover:text-emerald-600">ΓåÆ</span>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 font-medium text-sm transition-all group">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Tambah Produk Kopontren
                        </span>
                        <span class="text-xs text-slate-400 group-hover:text-emerald-600">ΓåÆ</span>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 font-medium text-sm transition-all group">
                        <span class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Laporan Donasi Masuk
                        </span>
                        <span class="text-xs text-slate-400 group-hover:text-emerald-600">ΓåÆ</span>
                    </a>
                </div>
            </div>

            <!-- Role Middleware Test Info Box -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-950">Proteksi Role: Administrator</h4>
                        <p class="text-xs text-emerald-800 mt-1">
                            Halaman ini diproteksi oleh <code>RoleMiddleware</code>. Hanya pengguna dengan peran <strong>admin</strong> yang diizinkan mengakses URL ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
