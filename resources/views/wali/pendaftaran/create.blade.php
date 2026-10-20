@extends('layouts.wali')

@section('title', 'Pendaftaran Santri Baru (PSB)')

@section('content')
<div class="space-y-4">

    <!-- Top Navigation / Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('wali.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Beranda
        </a>
        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-full">
            T.A 2026/2027
        </span>
    </div>

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-700 rounded-2xl p-5 text-white shadow-lg shadow-emerald-900/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center font-bold text-white">
                <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-emerald-200 uppercase tracking-wider">Pilar 1: PSB Online</span>
                <h2 class="text-lg font-bold text-white leading-tight">Formulir Santri Baru</h2>
            </div>
        </div>
        <p class="text-xs text-emerald-100/90 mt-2.5 leading-relaxed">
            Silakan lengkapi formulir identitas calon santri dan unggah dokumen berkas (KK & Ijazah).
        </p>
    </div>

    <!-- Validation Errors Alert -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-xs text-rose-800 space-y-1 shadow-sm">
            <div class="font-bold flex items-center gap-1.5 text-rose-900">
                <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Terdapat kesalahan pengisian formulir:
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Pendaftaran -->
    <form method="POST" action="{{ route('wali.pendaftaran.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- SECTION 1: Identitas Calon Santri -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3.5">
            <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center justify-center">1</span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Data Calon Santri</h3>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block text-xs font-semibold text-slate-700 mb-1">
                    Nama Lengkap Santri <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                       placeholder="Contoh: Muhammad Fatih Al-Ayyubi"
                       class="w-full text-xs rounded-xl border border-slate-300 p-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nama_lengkap') border-rose-500 @enderror">
                @error('nama_lengkap')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIK (16 Digit) -->
            <div>
                <label for="nik" class="block text-xs font-semibold text-slate-700 mb-1">
                    Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required maxlength="16" pattern="\d{16}"
                       placeholder="16 Digit NIK sesuai Kartu Keluarga"
                       class="w-full text-xs font-mono rounded-xl border border-slate-300 p-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nik') border-rose-500 @enderror">
                <p class="text-[10px] text-slate-400 mt-0.5">Wajib 16 digit angka sesuai dokumen resmi</p>
                @error('nik')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div>
                <label for="tempat_tanggal_lahir" class="block text-xs font-semibold text-slate-700 mb-1">
                    Tempat, Tanggal Lahir <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="tempat_tanggal_lahir" name="tempat_tanggal_lahir" value="{{ old('tempat_tanggal_lahir') }}" required
                       placeholder="Contoh: Bogor, 12 Mei 2012"
                       class="w-full text-xs rounded-xl border border-slate-300 p-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('tempat_tanggal_lahir') border-rose-500 @enderror">
                @error('tempat_tanggal_lahir')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Jenis Kelamin <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-emerald-50 transition-colors">
                        <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin', 'Laki-laki') === 'Laki-laki' ? 'checked' : '' }}
                               class="text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-medium text-slate-700">Laki-laki (Ikhwan)</span>
                    </label>
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-emerald-50 transition-colors">
                        <input type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'checked' : '' }}
                               class="text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-medium text-slate-700">Perempuan (Akhwat)</span>
                    </label>
                </div>
                @error('jenis_kelamin')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat Domisili -->
            <div>
                <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">
                    Alamat Domisili Lengkap <span class="text-rose-500">*</span>
                </label>
                <textarea id="alamat" name="alamat" rows="2" required
                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos"
                          class="w-full text-xs rounded-xl border border-slate-300 p-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('alamat') border-rose-500 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- SECTION 2: Data Orang Tua / Wali -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3.5">
            <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center justify-center">2</span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Data Orang Tua / Wali</h3>
            </div>

            <!-- Nama Wali -->
            <div>
                <label for="nama_wali" class="block text-xs font-semibold text-slate-700 mb-1">
                    Nama Ayah / Ibu / Wali Santri <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nama_wali" name="nama_wali" value="{{ old('nama_wali', Auth::user()->name) }}" required
                       placeholder="Nama lengkap wali santri"
                       class="w-full text-xs rounded-xl border border-slate-300 p-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nama_wali') border-rose-500 @enderror">
                @error('nama_wali')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No. HP / WhatsApp Wali -->
            <div>
                <label for="no_hp_wali" class="block text-xs font-semibold text-slate-700 mb-1">
                    Nomor WhatsApp / HP Aktif <span class="text-rose-500">*</span>
                </label>
                <input type="tel" id="no_hp_wali" name="no_hp_wali" value="{{ old('no_hp_wali') }}" required
                       placeholder="Contoh: 081234567890"
                       class="w-full text-xs font-mono rounded-xl border border-slate-300 p-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('no_hp_wali') border-rose-500 @enderror">
                <p class="text-[10px] text-slate-400 mt-0.5">Digunakan untuk informasi hasil seleksi dan verifikasi</p>
                @error('no_hp_wali')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- SECTION 3: Unggah Berkas Dokumen -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm space-y-3.5">
            <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center justify-center">3</span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Unggah Berkas Persyaratan</h3>
            </div>
            <p class="text-[11px] text-slate-500">Format yang diterima: <strong>PDF, JPG, JPEG, PNG</strong> (Maksimal 2 MB per berkas).</p>

            <!-- Upload File KK -->
            <div>
                <label for="file_kk" class="block text-xs font-semibold text-slate-700 mb-1">
                    Scan / Foto Kartu Keluarga (KK)
                </label>
                <input type="file" id="file_kk" name="file_kk" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 rounded-xl cursor-pointer">
                @error('file_kk')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload File Ijazah / SKL -->
            <div>
                <label for="file_ijazah" class="block text-xs font-semibold text-slate-700 mb-1">
                    Scan / Foto Ijazah Terakhir / Surat Keterangan Lulus (SKL)
                </label>
                <input type="file" id="file_ijazah" name="file_ijazah" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-slate-300 rounded-xl cursor-pointer">
                @error('file_ijazah')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-700/25 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Kirim Pendaftaran Santri Baru
            </button>
        </div>

    </form>

</div>
@endsection
