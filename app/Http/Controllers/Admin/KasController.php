<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasTransaction;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->endOfMonth()->toDateString();

        $transactions = KasTransaction::with('createdBy')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($request->search, fn($q) => $q->where('keterangan', 'like', "%{$request->search}%"))
            ->latest('tanggal')
            ->paginate(20)->withQueryString();

        $stats = [
            'saldo_kas'    => KasTransaction::getSaldoKas(),
            'pemasukan_bulan_ini' => KasTransaction::bulanIni()->pemasukan()->sum('nominal'),
            'pengeluaran_bulan_ini' => KasTransaction::bulanIni()->pengeluaran()->sum('nominal'),
            'pemasukan_periode' => KasTransaction::whereBetween('tanggal', [$startDate, $endDate])->pemasukan()->sum('nominal'),
            'pengeluaran_periode' => KasTransaction::whereBetween('tanggal', [$startDate, $endDate])->pengeluaran()->sum('nominal'),
        ];

        $kategoriList = KasTransaction::distinct()->pluck('kategori')->sort()->values();

        return view('admin.kas.index', compact('transactions', 'stats', 'startDate', 'endDate', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'      => 'required|in:pemasukan,pengeluaran',
            'kategori'  => 'required|string|max:100',
            'nominal'   => 'required|integer|min:1',
            'tanggal'   => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('kas', 'public');
        }

        $validated['created_by'] = auth()->id();
        KasTransaction::create($validated);

        return back()->with('success', 'Transaksi kas berhasil dicatat.');
    }

    public function destroy(KasTransaction $kasTransaction)
    {
        $kasTransaction->delete();
        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
