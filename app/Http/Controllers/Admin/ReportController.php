<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\KasTransaction;
use App\Models\KoperasiTransaction;
use App\Models\Santri;
use App\Models\SppPayment;
use App\Models\Wallet;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;
        $bulan = $request->bulan ?? now()->month;

        // SPP Stats
        $sppStats = [];
        for ($b = 1; $b <= 12; $b++) {
            $sppStats[$b] = [
                'bulan' => $b,
                'terkumpul' => SppPayment::where('bulan', $b)->where('tahun', $tahun)->where('status', 'lunas')->sum('nominal'),
                'lunas'     => SppPayment::where('bulan', $b)->where('tahun', $tahun)->where('status', 'lunas')->count(),
                'belum'     => SppPayment::where('bulan', $b)->where('tahun', $tahun)->where('status', 'belum_bayar')->count(),
            ];
        }

        // Kas monthly
        $kasMonthly = [];
        for ($b = 1; $b <= 12; $b++) {
            $kasMonthly[$b] = [
                'pemasukan'   => KasTransaction::whereYear('tanggal', $tahun)->whereMonth('tanggal', $b)->pemasukan()->sum('nominal'),
                'pengeluaran' => KasTransaction::whereYear('tanggal', $tahun)->whereMonth('tanggal', $b)->pengeluaran()->sum('nominal'),
            ];
        }

        // Koperasi monthly
        $koperasiMonthly = [];
        for ($b = 1; $b <= 12; $b++) {
            $koperasiMonthly[$b] = KoperasiTransaction::whereYear('created_at', $tahun)->whereMonth('created_at', $b)->sum('total_nominal');
        }

        $summary = [
            'total_santri_aktif' => Santri::aktif()->count(),
            'total_saldo_ewallet' => Wallet::sum('saldo'),
            'saldo_kas' => KasTransaction::getSaldoKas(),
            'spp_tahun_ini' => SppPayment::where('tahun', $tahun)->where('status', 'lunas')->sum('nominal'),
            'donasi_tahun_ini' => Donation::whereYear('tanggal', $tahun)->sum('nominal'),
            'koperasi_tahun_ini' => KoperasiTransaction::whereYear('created_at', $tahun)->sum('total_nominal'),
            'pemasukan_kas_tahun_ini' => KasTransaction::whereYear('tanggal', $tahun)->pemasukan()->sum('nominal'),
            'pengeluaran_kas_tahun_ini' => KasTransaction::whereYear('tanggal', $tahun)->pengeluaran()->sum('nominal'),
        ];

        // Breakdown kas per kategori
        $kasPerKategori = KasTransaction::whereYear('tanggal', $tahun)
            ->selectRaw('kategori, type, SUM(nominal) as total')
            ->groupBy('kategori', 'type')
            ->get()
            ->groupBy('kategori');

        return view('admin.laporan.index', compact(
            'tahun', 'bulan', 'sppStats', 'kasMonthly', 'koperasiMonthly', 'summary', 'kasPerKategori'
        ));
    }
}
