<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $santris = auth()->user()->santris()->with(['wallet.transactions' => fn($q) => $q->latest()->limit(20)])->get();

        return view('wali.wallet.index', compact('santris'));
    }

    /**
     * Initiates a Midtrans payment for wallet topup.
     * (Placeholder - Midtrans integration to be completed in Phase 9)
     */
    public function topup(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'nominal'   => 'required|integer|min:10000|max:5000000',
        ]);

        // Verify this santri belongs to the authenticated wali
        $santri = auth()->user()->santris()->findOrFail($validated['santri_id']);

        // TODO: Implement Midtrans payment gateway integration
        // For now, redirect back with info message
        return back()->with('info', 'Fitur top-up via payment gateway sedang dalam pengembangan. Silakan hubungi admin untuk top-up saldo.');
    }
}
