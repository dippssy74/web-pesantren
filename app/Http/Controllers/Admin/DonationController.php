<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\KasTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::with('createdBy')
            ->when($request->search, fn($q) => $q->where('nama_donatur', 'like', "%{$request->search}%")
                ->orWhere('no_donasi', 'like', "%{$request->search}%"))
            ->when($request->jenis, fn($q) => $q->where('jenis', $request->jenis))
            ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
            ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
            ->latest('tanggal')
            ->paginate(20)->withQueryString();

        $stats = [
            'total_bulan_ini' => Donation::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('nominal'),
            'total_tahun_ini' => Donation::whereYear('tanggal', now()->year)->sum('nominal'),
            'count_bulan_ini' => Donation::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count(),
        ];

        return view('admin.donasi.index', compact('donations', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_donatur'   => 'nullable|string|max:255',
            'no_hp'          => 'nullable|string|max:20',
            'jenis'          => 'required|in:infaq,sedekah,wakaf,zakat,lainnya',
            'nominal'        => 'required|integer|min:1',
            'tanggal'        => 'required|date',
            'metode'         => 'required|in:tunai,transfer,ewallet',
            'keterangan'     => 'nullable|string',
            'bukti_transfer' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('bukti_transfer')) {
                $validated['bukti_transfer'] = $request->file('bukti_transfer')->store('donasi', 'public');
            }

            $validated['nama_donatur'] = $validated['nama_donatur'] ?? 'Hamba Allah';
            $validated['no_donasi'] = Donation::generateNoDonasi();
            $validated['created_by'] = auth()->id();

            $donation = Donation::create($validated);

            // Catat ke kas
            KasTransaction::create([
                'type'     => 'pemasukan',
                'kategori' => ucfirst($validated['jenis']),
                'nominal'  => $validated['nominal'],
                'tanggal'  => $validated['tanggal'],
                'keterangan' => "Donasi dari {$validated['nama_donatur']} - {$donation->jenis_label}",
                'reference_type' => Donation::class,
                'reference_id'   => $donation->id,
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Donasi berhasil dicatat.');
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();
        return back()->with('success', 'Data donasi berhasil dihapus.');
    }
}
