<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasTransaction;
use App\Models\Santri;
use App\Models\SppPayment;
use App\Models\SppRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SppController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $santris = Santri::aktif()
            ->with(['sppPayments' => fn($q) => $q->where('bulan', $bulan)->where('tahun', $tahun)])
            ->when($request->search, fn($q) => $q->where('nama_lengkap', 'like', "%{$request->search}%")
                ->orWhere('nis', 'like', "%{$request->search}%"))
            ->when($request->kelas, fn($q) => $q->where('kelas', $request->kelas))
            ->paginate(20)->withQueryString();

        $kelasList = Santri::aktif()->distinct()->pluck('kelas')->sort()->values();
        $sppRates = SppRate::active()->get();

        $stats = [
            'total_santri' => Santri::aktif()->count(),
            'sudah_bayar'  => SppPayment::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'lunas')->count(),
            'belum_bayar'  => SppPayment::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'belum_bayar')->count(),
            'total_terkumpul' => SppPayment::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'lunas')->sum('nominal'),
        ];

        return view('admin.spp.index', compact('santris', 'bulan', 'tahun', 'kelasList', 'sppRates', 'stats'));
    }

    public function generateTagihan(Request $request)
    {
        $validated = $request->validate([
            'bulan'  => 'required|integer|min:1|max:12',
            'tahun'  => 'required|digits:4',
            'nominal' => 'required|integer|min:1000',
        ]);

        $santris = Santri::aktif()->get();
        $count = 0;

        DB::transaction(function () use ($santris, $validated, &$count) {
            foreach ($santris as $santri) {
                SppPayment::firstOrCreate(
                    ['santri_id' => $santri->id, 'bulan' => $validated['bulan'], 'tahun' => $validated['tahun']],
                    ['nominal' => $validated['nominal'], 'status' => 'belum_bayar', 'created_by' => auth()->id()]
                ) && $count++;
            }
        });

        return back()->with('success', "Berhasil generate {$count} tagihan SPP untuk bulan " . $this->namaBulan($validated['bulan']) . " {$validated['tahun']}.");
    }

    public function bayar(Request $request, SppPayment $payment)
    {
        $validated = $request->validate([
            'metode_bayar' => 'required|in:tunai,transfer,ewallet',
            'keterangan'   => 'nullable|string',
        ]);

        DB::transaction(function () use ($payment, $validated) {
            $payment->update([
                'status'       => 'lunas',
                'paid_at'      => now(),
                'metode_bayar' => $validated['metode_bayar'],
                'keterangan'   => $validated['keterangan'],
            ]);

            // Catat ke kas
            KasTransaction::create([
                'type'          => 'pemasukan',
                'kategori'      => 'SPP',
                'nominal'       => $payment->nominal,
                'tanggal'       => today(),
                'keterangan'    => "SPP {$payment->santri->nama_lengkap} - " . $payment->bulan_nama . " {$payment->tahun}",
                'reference_type' => SppPayment::class,
                'reference_id'  => $payment->id,
                'created_by'    => auth()->id(),
            ]);
        });

        return back()->with('success', 'Pembayaran SPP berhasil dicatat.');
    }

    public function riwayat(Request $request)
    {
        $payments = SppPayment::with('santri')
            ->when($request->search, fn($q) => $q->whereHas('santri', fn($s) => $s->where('nama_lengkap', 'like', "%{$request->search}%")))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->tahun, fn($q) => $q->where('tahun', $request->tahun))
            ->latest()
            ->paginate(20)->withQueryString();

        return view('admin.spp.riwayat', compact('payments'));
    }

    private function namaBulan(int $bulan): string
    {
        $list = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        return $list[$bulan] ?? '';
    }
}
