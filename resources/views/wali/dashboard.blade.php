@extends('layouts.wali')

@section('title', 'Portal Wali Santri')

@section('content')
<div class="space-y-4">

    <!-- Greeting & Info Santri Card -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-emerald-100 border-2 border-emerald-500/30 flex items-center justify-center text-emerald-800 font-bold text-lg">
                    AF
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wider">
                        Santri Binaan
                    </span>
                    <h2 class="text-base font-bold text-slate-800 leading-tight mt-0.5">{{ $santri['nama'] }}</h2>
                    <p class="text-xs text-slate-500 font-mono">{{ $santri['nis'] }} ΓÇó {{ $santri['kelas'] }}</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                {{ $santri['status'] }}
            </span>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ $santri['kamar'] }}
            </span>
        </div>
    </div>

    <!-- Alert Success Flash -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-medium leading-relaxed">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Dynamic Kartu Status Pendaftaran (PSB Online) -->
    @if ($latestRegistration)
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Pilar 1: PSB Online</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mt-1">Status Pendaftaran Santri Baru</h3>
                </div>

                <!-- Status Badge -->
                @if ($latestRegistration->status === 'lulus_berkas')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Lulus Berkas
                    </span>
                @elseif ($latestRegistration->status === 'ditolak')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                        Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        Menunggu Verifikasi
                    </span>
                @endif
            </div>

            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-xs space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">No. Registrasi</span>
                    <span class="font-mono font-bold text-emerald-800">{{ $latestRegistration->no_pendaftaran }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Calon Santri</span>
                    <span class="font-semibold text-slate-800">{{ $latestRegistration->nama_lengkap }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Berkas Terlampir</span>
                    <span class="text-[11px] font-medium text-slate-600">
                        KK: {{ $latestRegistration->file_kk ? 'Γ£ô' : 'Γ£ù' }} | Ijazah: {{ $latestRegistration->file_ijazah ? 'Γ£ô' : 'Γ£ù' }}
                    </span>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="flex items-center justify-between pt-1">
                <a href="{{ route('wali.pendaftaran.show', $latestRegistration) }}" 
                   class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                    Lihat Rincian Status ΓåÆ
                </a>
                <a href="{{ route('wali.pendaftaran.create') }}" 
                   class="text-xs font-semibold text-slate-500 hover:text-emerald-600">
                    + Daftarkan Santri Lain
                </a>
            </div>
        </div>
    @else
        <!-- CTA Pendaftaran Baru Jika Belum Ada Data -->
        <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-700 rounded-2xl p-4 text-white shadow-md space-y-2.5">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-500/30 text-emerald-100 border border-emerald-400/30">
                    Pilar 1: PSB Online
                </span>
            </div>
            <h3 class="text-sm font-bold text-white">Pendaftaran Santri Baru Telah Dibuka!</h3>
            <p class="text-xs text-emerald-100/90 leading-relaxed">
                Daftarkan putra/putri Anda secara online dengan mudah, upload scan KK & Ijazah secara mandiri.
            </p>
            <div class="pt-1">
                <a href="{{ route('wali.pendaftaran.create') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-emerald-800 font-bold text-xs shadow-md hover:bg-emerald-50 transition-all">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Daftar Santri Baru Sekarang
                </a>
            </div>
        </div>
    @endif
    <!-- E-Wallet Cashless Card (Digital Card Appearance) -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-tr from-emerald-900 via-emerald-800 to-teal-700 p-5 text-white shadow-lg shadow-emerald-900/20">
        <!-- Background Decorative Circles -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-teal-400/20 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col justify-between h-44">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-emerald-200/90 font-medium">Santri Cashless Card</p>
                    <p class="text-xs font-mono tracking-widest text-emerald-100">**** 8842</p>
                </div>
                <div class="px-2.5 py-1 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-[11px] font-semibold text-emerald-100 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    E-Wallet
                </div>
            </div>

            <div class="my-auto">
                <p class="text-xs text-emerald-200 uppercase font-semibold tracking-wider">Saldo Uang Saku</p>
                <h3 class="text-2xl font-extrabold tracking-tight mt-0.5">
                    Rp {{ number_format($santri['saldo_wallet'], 0, ',', '.') }}
                </h3>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-emerald-700/60 text-xs">
                <div>
                    <span class="text-emerald-300/80 text-[11px]">Limit Jajan: </span>
                    <span class="font-semibold text-white">Rp {{ number_format($santri['limit_harian'], 0, ',', '.') }}/hari</span>
                </div>
                <div>
                    <span class="text-emerald-300/80 text-[11px]">Terpakai Hari Ini: </span>
                    <span class="font-semibold text-amber-300">Rp {{ number_format($santri['terpakai_hari_ini'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions (Top Up & Riwayat) -->
    <div class="grid grid-cols-2 gap-3">
        <a href="#topup" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md shadow-emerald-700/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Top Up Saldo
        </a>
        <a href="#atur-limit" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs border border-slate-200/90 shadow-sm transition-all">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Atur Limit Jajan
        </a>
    </div>

    <!-- Tagihan SPP Bulanan -->
    <div id="spp" class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                <h3 class="text-sm font-bold text-slate-800">Tagihan SPP Santri</h3>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700">
                {{ $tagihanSpp['status'] }}
            </span>
        </div>

        <div class="bg-rose-50/50 rounded-xl p-3 border border-rose-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-600 font-medium">Bulan {{ $tagihanSpp['bulan'] }}</p>
                <p class="text-base font-extrabold text-slate-900 mt-0.5">Rp {{ number_format($tagihanSpp['nominal'], 0, ',', '.') }}</p>
                <p class="text-[11px] text-rose-600 mt-0.5">Jatuh tempo: {{ $tagihanSpp['jatuh_tempo'] }}</p>
            </div>
            <a href="#bayar-spp" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition-all">
                Bayar Sekarang
            </a>
        </div>
    </div>

    <!-- Riwayat Transaksi Jajan Santri -->
    <div id="wallet" class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Riwayat Jajan & Transaksi</h3>
            <span class="text-xs text-emerald-600 font-semibold">Terkini</span>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach ($riwayatJajan as $trx)
            <div class="py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $trx['tipe'] === 'kredit' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        @if ($trx['tipe'] === 'kredit')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800">{{ $trx['toko'] }}</p>
                        <p class="text-[11px] text-slate-500 truncate max-w-[170px]">{{ $trx['item'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $trx['waktu'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold {{ $trx['tipe'] === 'kredit' ? 'text-emerald-600' : 'text-slate-800' }}">
                        {{ $trx['tipe'] === 'kredit' ? '+' : '-' }}Rp {{ number_format($trx['nominal'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Security & Role Info -->
    <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-200/60 text-xs text-emerald-800 flex items-center gap-2.5">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <span>Akun ini terautentikasi sebagai <strong>Wali Santri</strong> dengan akses E-Wallet dan SPP.</span>
    </div>

</div>
@endsection
