<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Display a listing of registrations for current wali santri.
     */
    public function index(Request $request): View
    {
        $registrations = Registration::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('wali.pendaftaran.index', compact('registrations'));
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create(): View
    {
        return view('wali.pendaftaran.create');
    }

    /**
     * Store a newly created registration in storage.
     */
    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $fileKkPath = null;
        if ($request->hasFile('file_kk')) {
            $fileKkPath = $request->file('file_kk')->store('berkas', 'public');
        }

        $fileIjazahPath = null;
        if ($request->hasFile('file_ijazah')) {
            $fileIjazahPath = $request->file('file_ijazah')->store('berkas', 'public');
        }

        $registration = Registration::create([
            'user_id' => $request->user()->id,
            'no_pendaftaran' => Registration::generateNoPendaftaran(),
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'tempat_tanggal_lahir' => $validated['tempat_tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'nama_wali' => $validated['nama_wali'],
            'no_hp_wali' => $validated['no_hp_wali'],
            'status' => 'menunggu_verifikasi',
            'file_kk' => $fileKkPath,
            'file_ijazah' => $fileIjazahPath,
        ]);

        return redirect()
            ->route('wali.dashboard')
            ->with('success', "Alhamdulillah! Pendaftaran calon santri {$registration->nama_lengkap} berhasil dikirim dengan Nomor Registrasi: {$registration->no_pendaftaran}.");
    }

    /**
     * Display the specified registration.
     */
    public function show(Registration $registration, Request $request): View
    {
        // Pastikan hanya pemilik pendaftaran yang bisa melihat
        if ($registration->user_id !== $request->user()->id) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk melihat pendaftaran ini.');
        }

        return view('wali.pendaftaran.show', compact('registration'));
    }
}
