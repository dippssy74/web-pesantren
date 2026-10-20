<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RegistrationController extends Controller
{
    /**
     * Display a listing of registrations with filters & search.
     */
    public function index(Request $request): View
    {
        $query = Registration::with('user')->latest();

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Pencarian nama, nomor pendaftaran, NIK, nama wali
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nama_wali', 'like', "%{$search}%");
            });
        }

        $registrations = $query->paginate(10)->withQueryString();

        // Ringkasan statistik PSB
        $counts = [
            'total' => Registration::count(),
            'menunggu' => Registration::where('status', 'menunggu_verifikasi')->count(),
            'lulus' => Registration::where('status', 'lulus_berkas')->count(),
            'ditolak' => Registration::where('status', 'ditolak')->count(),
        ];

        return view('admin.psb.index', compact('registrations', 'counts'));
    }

    /**
     * Display the specified registration detail.
     */
    public function show(Registration $registration): View
    {
        return view('admin.psb.show', compact('registration'));
    }

    /**
     * Update registration status (Quick Action: Verifikasi/Lulus/Tolak).
     */
    public function updateStatus(Request $request, Registration $registration): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu_verifikasi,lulus_berkas,ditolak'],
        ]);

        $registration->update([
            'status' => $validated['status'],
        ]);

        $statusLabels = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lulus_berkas' => 'Lulus Berkas',
            'ditolak' => 'Ditolak',
        ];

        $label = $statusLabels[$validated['status']] ?? $validated['status'];

        return back()->with('success', "Status pendaftaran {$registration->no_pendaftaran} ({$registration->nama_lengkap}) berhasil diubah menjadi: {$label}.");
    }

    /**
     * Preview or download uploaded document (KK or Ijazah).
     */
    public function previewDocument(Registration $registration, string $type)
    {
        $filePath = match ($type) {
            'kk' => $registration->file_kk,
            'ijazah' => $registration->file_ijazah,
            default => null,
        };

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Dokumen berkas tidak ditemukan atau belum diunggah.');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
        ]);
    }
}
