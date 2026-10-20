<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $wallets = Wallet::with(['santri'])
            ->when($request->search, fn($q) => $q->whereHas('santri', fn($s) => $s->where('nama_lengkap', 'like', "%{$request->search}%")
                ->orWhere('nis', 'like', "%{$request->search}%")))
            ->paginate(20)->withQueryString();

        $stats = [
            'total_saldo'    => Wallet::sum('saldo'),
            'total_wallet'   => Wallet::count(),
            'topup_hari_ini' => WalletTransaction::where('type', 'topup')->whereDate('created_at', today())->sum('nominal'),
            'debit_hari_ini' => WalletTransaction::where('type', 'debit')->whereDate('created_at', today())->sum('nominal'),
        ];

        return view('admin.wallet.index', compact('wallets', 'stats'));
    }

    public function topup(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'nominal'    => 'required|integer|min:1000|max:10000000',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $keterangan = $validated['keterangan'] ?? 'Top Up oleh Admin';
        $wallet->topup($validated['nominal'], $keterangan, auth()->id());

        return back()->with('success', "Berhasil top up Rp " . number_format($validated['nominal'], 0, ',', '.') . " ke wallet {$wallet->santri->nama_lengkap}.");
    }

    public function transactions(Request $request)
    {
        $transactions = WalletTransaction::with(['wallet.santri', 'createdBy'])
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, fn($q) => $q->whereHas('wallet.santri', fn($s) => $s->where('nama_lengkap', 'like', "%{$request->search}%")))
            ->latest()
            ->paginate(20)->withQueryString();

        return view('admin.wallet.transactions', compact('transactions'));
    }
}
