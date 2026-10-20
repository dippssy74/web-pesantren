<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\SppPayment;
use Illuminate\Http\Request;

class SppController extends Controller
{
    public function index(Request $request)
    {
        $santris = auth()->user()->santris()->with('sppPayments')->get();

        $tahun = $request->tahun ?? now()->year;

        $payments = SppPayment::whereIn('santri_id', auth()->user()->santris->pluck('id'))
            ->with('santri')
            ->where('tahun', $tahun)
            ->orderBy('bulan')
            ->get()
            ->groupBy('santri_id');

        $years = range(now()->year, now()->year - 3);

        return view('wali.spp.index', compact('santris', 'payments', 'tahun', 'years'));
    }
}
