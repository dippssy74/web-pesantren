@extends('layouts.wali')

@section('title', 'Rincian Pendaftaran Santri')

@section('content')
<div class="space-y-4">

    <!-- Top Navigation / Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Beranda
        </a>
        <a href="{{ route('wali.pendaftaran.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-700">
            Semua Pendaftaran
        </a>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm text-center space-y-3">
        <span class="inline-block font-mono font-bold text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
            {{ $registration->no_pendaftaran }}
        </span>

        <h2 class="text-lg font-bold text-slate-800">{{ $registration->nama_lengkap }}</h2>
        <p class="text-xs text-slate-500 font-mono">NIK: {{ $registration->nik }}</p>

        <!-- Dynamic Status Banner -->
        @if ($registration->status === 'lulus_berkas')
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-1">
                <div class="font-bold flex items-center justify-center gap-1.5 text-emerald-900">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Selamat! Berkas Dinyatakan Lulus
                </div>
                <p class="text-[11px] text-emerald-700">Silakan menunggu instruksi jadwal tes wawancara dan aktivasi e-wallet santri.</p>
            </div>
        @elseif ($registration->status === 'ditolak')
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                <div class="font-bold flex items-center justify-center gap-1.5 text-rose-900">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Berkas Pendaftaran Belum Memenuhi Syarat
                </div>
                <p class="text-[11px] text-rose-700">Silakan hubungi pihak panitia PSB untuk konfirmasi perbaikan dokumen.</p>
            </div>
        @else
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 space-y-1">
                <div class="font-bold flex items-center justify-center gap-1.5 text-amber-900">
                    <svg class="w-4 h-4 text-amber-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Sedang Diverifikasi oleh Panitia PSB
                </div>
                <p class="text-[11px] text-amber-700">Panitia sedang meninjau kelengkapan dokumen yang Anda kirimkan.</p>
            </div>
        @endif
    </div>

    <!-- Data Detail Santri -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 pb-2 border-b border-slate-100">
            Rincian Data Pendaftaran
        </h3>

        <div class="space-y-2 text-xs">
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400">Jenis Kelamin</span>
                <span class="font-semibold text-slate-700">{{ $registration->jenis_kelamin }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400">TTL</span>
                <span class="font-semibold text-slate-700">{{ $registration->tempat_tanggal_lahir }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400">Nama Wali</span>
                <span class="font-semibold text-slate-700">{{ $registration->nama_wali }}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-slate-50">
                <span class="text-slate-400">No. WhatsApp</span>
                <span class="font-mono font-semibold text-emerald-700">{{ $registration->no_hp_wali }}</span>
            </div>
            <div class="pt-1">
                <span class="text-slate-400 block mb-0.5">Alamat</span>
                <span class="font-medium text-slate-700 leading-relaxed">{{ $registration->alamat }}</span>
            </div>
        </div>
    </div>

    <!-- Berkas Dokumen -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 pb-2 border-b border-slate-100">
            Berkas Terlampir
        </h3>

        <div class="space-y-2">
            <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50">
                <div class="flex items-center gap-2">
                    <span class="text-base">📄</span>
                    <span class="text-xs font-semibold text-slate-700">Kartu Keluarga (KK)</span>
                </div>
                @if ($registration->file_kk)
                    <a href="{{ asset('storage/' . $registration->file_kk) }}" target="_blank"
                       class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                        Buka Berkas ↗
                    </a>
                @else
                    <span class="text-[11px] text-slate-400 italic">Belum diunggah</span>
                @endif
            </div>

            <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50">
                <div class="flex items-center gap-2">
                    <span class="text-base">🎓</span>
                    <span class="text-xs font-semibold text-slate-700">Ijazah / SKL</span>
                </div>
                @if ($registration->file_ijazah)
                    <a href="{{ asset('storage/' . $registration->file_ijazah) }}" target="_blank"
                       class="text-xs font-bold text-teal-600 hover:text-teal-700">
                        Buka Berkas ↗
                    </a>
                @else
                    <span class="text-[11px] text-slate-400 italic">Belum diunggah</span>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
