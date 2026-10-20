@extends('layouts.admin')

@section('page_title', 'Detail Pendaftaran Santri')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation / Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.psb.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar PSB
        </a>

        <!-- Status Pill -->
        @if ($registration->status === 'lulus_berkas')
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                Status: Lulus Berkas
            </span>
        @elseif ($registration->status === 'ditolak')
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                Status: Ditolak
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                Status: Menunggu Verifikasi
            </span>
        @endif
    </div>

    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Santri & Wali Information -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Data Calon Santri Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                            {{ $registration->no_pendaftaran }}
                        </span>
                        <h3 class="text-xl font-bold text-slate-800 mt-2">{{ $registration->nama_lengkap }}</h3>
                        <p class="text-xs text-slate-500 font-mono mt-0.5">NIK: {{ $registration->nik }}</p>
                    </div>
                    <div class="text-right text-xs text-slate-400">
                        <p>Didaftarkan pada</p>
                        <p class="font-semibold text-slate-700 mt-0.5">{{ $registration->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Jenis Kelamin</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $registration->jenis_kelamin }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Tempat, Tanggal Lahir</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $registration->tempat_tanggal_lahir }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-slate-400 font-medium">Alamat Lengkap</p>
                        <p class="font-semibold text-slate-800 mt-0.5 leading-relaxed">{{ $registration->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Orang Tua / Wali Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider pb-3 border-b border-slate-100">
                    Informasi Orang Tua / Wali Santri
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Nama Wali Santri</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $registration->nama_wali }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Nomor WhatsApp / HP</p>
                        <p class="font-semibold text-emerald-700 mt-0.5 font-mono flex items-center gap-1.5">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->no_hp_wali) }}" target="_blank" class="hover:underline">
                                📱 {{ $registration->no_hp_wali }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Akun Pendaftar (Email)</p>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $registration->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Dokumen Berkas Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider pb-3 border-b border-slate-100 mb-4">
                    Berkas Persyaratan yang Diunggah
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kartu Keluarga (KK) -->
                    <div class="p-4 rounded-xl border {{ $registration->file_kk ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg {{ $registration->file_kk ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-slate-800">Kartu Keluarga (KK)</h5>
                                    <p class="text-xs text-slate-500">{{ $registration->file_kk ? 'Telah diunggah' : 'Belum diunggah' }}</p>
                                </div>
                            </div>
                        </div>
                        @if ($registration->file_kk)
                            <div class="mt-3 pt-3 border-t border-emerald-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-500 truncate max-w-[150px]">{{ basename($registration->file_kk) }}</span>
                                <a href="{{ asset('storage/' . $registration->file_kk) }}" target="_blank"
                                   class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-all">
                                    Buka Dokumen
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Ijazah / SKL -->
                    <div class="p-4 rounded-xl border {{ $registration->file_ijazah ? 'border-teal-200 bg-teal-50/40' : 'border-slate-200 bg-slate-50' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg {{ $registration->file_ijazah ? 'bg-teal-600 text-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-slate-800">Ijazah / SKL</h5>
                                    <p class="text-xs text-slate-500">{{ $registration->file_ijazah ? 'Telah diunggah' : 'Belum diunggah' }}</p>
                                </div>
                            </div>
                        </div>
                        @if ($registration->file_ijazah)
                            <div class="mt-3 pt-3 border-t border-teal-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-500 truncate max-w-[150px]">{{ basename($registration->file_ijazah) }}</span>
                                <a href="{{ asset('storage/' . $registration->file_ijazah) }}" target="_blank"
                                   class="px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs transition-all">
                                    Buka Dokumen
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Status Action Card -->
        <div class="space-y-6">

            <!-- Ubah Status Box -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider pb-3 border-b border-slate-100 mb-4">
                    Aksi Verifikasi Berkas
                </h4>

                <form method="POST" action="{{ route('admin.psb.update-status', $registration) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Status Baru</label>
                        <select name="status" class="w-full text-xs rounded-xl border border-slate-300 p-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="menunggu_verifikasi" {{ $registration->status === 'menunggu_verifikasi' ? 'selected' : '' }}>
                                Menunggu Verifikasi
                            </option>
                            <option value="lulus_berkas" {{ $registration->status === 'lulus_berkas' ? 'selected' : '' }}>
                                Lulus Berkas
                            </option>
                            <option value="ditolak" {{ $registration->status === 'ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-700/20 transition-all">
                        Simpan Perubahan Status
                    </button>
                </form>

                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 space-y-2">
                    <p class="font-semibold text-slate-700">Keterangan Alur:</p>
                    <p>• <strong>Lulus Berkas</strong>: Calon santri berhak lanjut ke tahap tes/ujian seleksi atau pembuatan e-wallet.</p>
                    <p>• <strong>Ditolak</strong>: Berkas tidak sah atau tidak memenuhi kriteria minimal.</p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
