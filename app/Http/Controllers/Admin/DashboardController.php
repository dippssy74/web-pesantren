<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index(Request $request): View
    {
        // Mengambil data pendaftaran dari database
        $realRegistrations = Registration::latest()
            ->take(5)
            ->get();

        $totalPendaftar = Registration::count();

        // Data ringkasan dashboard
        $stats = [
            'total_santri' => 482,
            'pendaftar_baru' => $totalPendaftar > 0
                ? $totalPendaftar
                : 38,
            'saldo_kas' => 124500000,
            'transaksi_kopontren' => 18450000,
            'donasi_terkumpul' => 76200000,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'realRegistrations'
        ));
    }
}
