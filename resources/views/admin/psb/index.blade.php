@extends('layouts.admin')

@section('page_title', 'Manajemen PSB Online')

@section('content')
<div class="space-y-6" x-data="{ 
    selectedReg: null, 
    previewModal: false, 
    previewUrl: '', 
    previewTitle: '',
    openPreview(url, title) {
        this.previewUrl = url;
        this.previewTitle = title;
        this.previewModal = true;
    }
}">

    <!-- Header & Action Summary -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-700 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-600/50 backdrop-blur-sm border border-emerald-400/30 text-emerald-100">
                Pilar 1: PSB Online
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-2">Penerimaan Santri Baru (PSB)</h2>
            <p class="text-emerald-100/90 text-sm max-w-xl mt-1">
                Kelola data calon santri baru, verifikasi keabsahan dokumen (KK & Ijazah), dan tentukan status kelulusan berkas secara terpadu.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20 text-xs font-bold text-white">
                Tahun Ajaran 2026/2027
            </span>
        </div>
    </div>

    <!-- Alert Success Flash -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <a href="{{ route('admin.psb.index') }}" class="bg-white rounded-2xl p-5 border {{ !request('status') || request('status') === 'all' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200/80' }} shadow-sm hover:shadow-md transition-all">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pendaftar</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $counts['total'] }}</h3>
            <p class="text-xs text-slate-400 mt-1">Semua calon santri</p>
        </a>

        <a href="{{ route('admin.psb.index', ['status' => 'menunggu_verifikasi']) }}" class="bg-white rounded-2xl p-5 border {{ request('status') === 'menunggu_verifikasi' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200/80' }} shadow-sm hover:shadow-md transition-all">
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Menunggu Verifikasi</p>
            <h3 class="text-2xl font-bold text-amber-700 mt-1">{{ $counts['menunggu'] }}</h3>
            <p class="text-xs text-amber-600/80 mt-1">Perlu dicek dokumen</p>
        </a>

        <a href="{{ route('admin.psb.index', ['status' => 'lulus_berkas']) }}" class="bg-white rounded-2xl p-5 border {{ request('status') === 'lulus_berkas' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200/80' }} shadow-sm hover:shadow-md transition-all">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Lulus Berkas</p>
            <h3 class="text-2xl font-bold text-emerald-700 mt-1">{{ $counts['lulus'] }}</h3>
            <p class="text-xs text-emerald-600/80 mt-1">Memenuhi syarat berkas</p>
        </a>

        <a href="{{ route('admin.psb.index', ['status' => 'ditolak']) }}" class="bg-white rounded-2xl p-5 border {{ request('status') === 'ditolak' ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-slate-200/80' }} shadow-sm hover:shadow-md transition-all">
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Ditolak</p>
            <h3 class="text-2xl font-bold text-rose-700 mt-1">{{ $counts['ditolak'] }}</h3>
            <p class="text-xs text-rose-500 mt-1">Berkas tidak sesuai</p>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs">
            <a href="{{ route('admin.psb.index', ['search' => request('search')]) }}" 
               class="px-3.5 py-2 rounded-xl font-semibold transition-all whitespace-nowrap {{ !request('status') || request('status') === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua ({{ $counts['total'] }})
            </a>
            <a href="{{ route('admin.psb.index', ['status' => 'menunggu_verifikasi', 'search' => request('search')]) }}" 
               class="px-3.5 py-2 rounded-xl font-semibold transition-all whitespace-nowrap {{ request('status') === 'menunggu_verifikasi' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Menunggu ({{ $counts['menunggu'] }})
            </a>
            <a href="{{ route('admin.psb.index', ['status' => 'lulus_berkas', 'search' => request('search')]) }}" 
               class="px-3.5 py-2 rounded-xl font-semibold transition-all whitespace-nowrap {{ request('status') === 'lulus_berkas' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Lulus ({{ $counts['lulus'] }})
            </a>
            <a href="{{ route('admin.psb.index', ['status' => 'ditolak', 'search' => request('search')]) }}" 
               class="px-3.5 py-2 rounded-xl font-semibold transition-all whitespace-nowrap {{ request('status') === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Ditolak ({{ $counts['ditolak'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.psb.index') }}" class="flex items-center gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1 md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / nomor / NIK..."
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.psb.index', ['status' => request('status')]) }}" class="px-2.5 py-2 text-xs text-slate-500 hover:text-rose-600">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table of Registrations -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">No. Registrasi</th>
                        <th class="py-3.5 px-4">Calon Santri</th>
                        <th class="py-3.5 px-4">Wali Santri & Kontak</th>
                        <th class="py-3.5 px-4 text-center">Berkas Dokumen</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($registrations as $reg)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <!-- No. Pendaftaran & Tanggal -->
                        <td class="py-4 px-4 align-top">
                            <span class="inline-block font-mono font-bold text-xs px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ $reg->no_pendaftaran }}
                            </span>
                            <p class="text-[11px] text-slate-400 mt-1.5">{{ $reg->created_at->format('d M Y, H:i') }} WIB</p>
                        </td>

                        <!-- Calon Santri -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-bold text-slate-800">{{ $reg->nama_lengkap }}</div>
                            <div class="text-xs text-slate-500 font-mono mt-0.5">NIK: {{ $reg->nik }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                <span class="inline-block font-medium text-slate-700">{{ $reg->jenis_kelamin }}</span> • {{ $reg->tempat_tanggal_lahir }}
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1 max-w-xs truncate" title="{{ $reg->alamat }}">
                                📍 {{ $reg->alamat }}
                            </div>
                        </td>

                        <!-- Wali Santri & Kontak -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-semibold text-slate-800">{{ $reg->nama_wali }}</div>
                            <div class="text-xs text-emerald-700 mt-0.5 flex items-center gap-1 font-mono">
                                <span>📱 {{ $reg->no_hp_wali }}</span>
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1">
                                Akun: {{ $reg->user->email ?? '-' }}
                            </div>
                        </td>

                        <!-- Berkas Dokumen (KK & Ijazah) -->
                        <td class="py-4 px-4 align-top text-center">
                            <div class="flex flex-col gap-1.5 items-center justify-center">
                                <!-- Berkas KK -->
                                @if ($reg->file_kk)
                                    <a href="{{ asset('storage/' . $reg->file_kk) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Pratinjau KK
                                    </a>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">KK: Belum ada</span>
                                @endif

                                <!-- Berkas Ijazah -->
                                @if ($reg->file_ijazah)
                                    <a href="{{ asset('storage/' . $reg->file_ijazah) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-50 text-teal-700 hover:bg-teal-100 border border-teal-200 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Pratinjau Ijazah
                                    </a>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Ijazah: Belum ada</span>
                                @endif
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-4 align-top text-center">
                            @if ($reg->status === 'lulus_berkas')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Lulus Berkas
                                </span>
                            @elseif ($reg->status === 'ditolak')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </td>

                        <!-- Aksi Cepat (Ubah Status) -->
                        <td class="py-4 px-4 align-top text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Tombol Verifikasi / Luluskan -->
                                @if ($reg->status !== 'lulus_berkas')
                                <form method="POST" action="{{ route('admin.psb.update-status', $reg) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="lulus_berkas">
                                    <button type="submit" title="Luluskan Berkas"
                                            onclick="return confirm('Verifikasi & luluskan berkas pendaftaran {{ $reg->nama_lengkap }}?')"
                                            class="p-2 rounded-xl bg-emerald-100 hover:bg-emerald-600 text-emerald-700 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif

                                <!-- Tombol Kembalikan ke Menunggu Verifikasi jika sudah diproses -->
                                @if ($reg->status !== 'menunggu_verifikasi')
                                <form method="POST" action="{{ route('admin.psb.update-status', $reg) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="menunggu_verifikasi">
                                    <button type="submit" title="Set Menunggu Verifikasi"
                                            onclick="return confirm('Ubah status {{ $reg->nama_lengkap }} kembali ke Menunggu Verifikasi?')"
                                            class="p-2 rounded-xl bg-amber-100 hover:bg-amber-600 text-amber-700 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                                @endif

                                <!-- Tombol Tolak Berkas -->
                                @if ($reg->status !== 'ditolak')
                                <form method="POST" action="{{ route('admin.psb.update-status', $reg) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" title="Tolak Berkas"
                                            onclick="return confirm('Tolak berkas pendaftaran {{ $reg->nama_lengkap }}?')"
                                            class="p-2 rounded-xl bg-rose-100 hover:bg-rose-600 text-rose-700 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                                @endif

                                <!-- Tombol Lihat Detail -->
                                <a href="{{ route('admin.psb.show', $reg) }}" title="Lihat Rincian Lengkap"
                                   class="p-2 rounded-xl bg-slate-100 hover:bg-slate-700 text-slate-600 hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm font-semibold text-slate-600">Tidak ada data pendaftaran yang sesuai filter.</p>
                            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau bersihkan filter pencarian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($registrations->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/80">
            {{ $registrations->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
