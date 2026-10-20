@extends('layouts.wali')

@section('title', 'Riwayat Pendaftaran Santri')

@section('content')
<div class="space-y-4">

    <!-- Top Navigation / Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Beranda
        </a>
        <a href="{{ route('wali.pendaftaran.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs shadow-sm hover:bg-emerald-700 transition-all">
            + Daftar Baru
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-700 rounded-2xl p-4 text-white shadow-md">
        <h2 class="text-base font-bold text-white">Status Pendaftaran Calon Santri</h2>
        <p class="text-xs text-emerald-100/90 mt-1">Daftar calon santri yang telah diajukan melalui akun Anda.</p>
    </div>

    <!-- List of Registrations -->
    <div class="space-y-3">
        @forelse ($registrations as $reg)
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[11px] font-mono font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200">
                            {{ $reg->no_pendaftaran }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-800 mt-1.5">{{ $reg->nama_lengkap }}</h3>
                        <p class="text-xs text-slate-500 font-mono">NIK: {{ $reg->nik }}</p>
                    </div>

                    <!-- Status Pill -->
                    @if ($reg->status === 'lulus_berkas')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Lulus Berkas
                        </span>
                    @elseif ($reg->status === 'ditolak')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                            Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            Menunggu Verifikasi
                        </span>
                    @endif
                </div>

                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Tanggal: {{ $reg->created_at->format('d M Y') }}</span>
                    <a href="{{ route('wali.pendaftaran.show', $reg) }}" class="font-semibold text-emerald-600 hover:text-emerald-700">
                        Lihat Detail →
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center border border-slate-200/80 shadow-sm">
                <p class="text-xs text-slate-500">Belum ada santri yang didaftarkan.</p>
                <a href="{{ route('wali.pendaftaran.create') }}" class="inline-block mt-3 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-700/20">
                    Daftar Santri Baru Sekarang
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
