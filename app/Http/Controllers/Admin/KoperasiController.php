<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasTransaction;
use App\Models\KoperasiProduct;
use App\Models\KoperasiTransaction;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KoperasiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = KoperasiTransaction::with(['santri', 'items.product', 'createdBy'])
            ->when($request->search, fn($q) => $q->where('no_transaksi', 'like', "%{$request->search}%")
                ->orWhereHas('santri', fn($s) => $s->where('nama_lengkap', 'like', "%{$request->search}%")))
            ->when($request->metode, fn($q) => $q->where('metode_bayar', $request->metode))
            ->latest()->paginate(15)->withQueryString();

        $stats = [
            'penjualan_hari_ini' => KoperasiTransaction::whereDate('created_at', today())->sum('total_nominal'),
            'transaksi_hari_ini' => KoperasiTransaction::whereDate('created_at', today())->count(),
            'penjualan_bulan_ini' => KoperasiTransaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_nominal'),
            'stok_rendah' => KoperasiProduct::stokRendah()->count(),
        ];

        return view('admin.koperasi.index', compact('transaksi', 'stats'));
    }

    // ── Products CRUD ────────────────────────────────────────────────────────

    public function products(Request $request)
    {
        $products = KoperasiProduct::when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%")
                ->orWhere('kode_produk', 'like', "%{$request->search}%"))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->latest()->paginate(20)->withQueryString();

        $kategoriList = KoperasiProduct::distinct()->pluck('kategori')->sort()->values();

        return view('admin.koperasi.products.index', compact('products', 'kategoriList'));
    }

    public function createProduct()
    {
        return view('admin.koperasi.products.create');
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'kategori'      => 'required|string|max:100',
            'harga_beli'    => 'required|integer|min:0',
            'harga_jual'    => 'required|integer|min:1',
            'stok'          => 'required|integer|min:0',
            'stok_minimum'  => 'required|integer|min:0',
            'satuan'        => 'required|string|max:20',
            'foto'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('koperasi/products', 'public');
        }

        $validated['kode_produk'] = KoperasiProduct::generateKodeProduk();
        KoperasiProduct::create($validated);

        return redirect()->route('admin.koperasi.products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, KoperasiProduct $product)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'harga_beli'   => 'required|integer|min:0',
            'harga_jual'   => 'required|integer|min:1',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'satuan'       => 'required|string|max:20',
            'is_active'    => 'boolean',
            'foto'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('koperasi/products', 'public');
        }

        $product->update($validated);
        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct(KoperasiProduct $product)
    {
        $product->update(['is_active' => false]);
        return back()->with('success', 'Produk berhasil dinonaktifkan.');
    }

    // ── Kasir (POS) ──────────────────────────────────────────────────────────

    public function kasir()
    {
        $products = KoperasiProduct::active()->where('stok', '>', 0)->orderBy('nama')->get();
        $santris  = Santri::aktif()->with('wallet')->orderBy('nama_lengkap')->get();
        return view('admin.koperasi.kasir', compact('products', 'santris'));
    }

    public function processKasir(Request $request)
    {
        $validated = $request->validate([
            'santri_id'   => 'nullable|exists:santris,id',
            'metode_bayar' => 'required|in:ewallet,tunai',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:koperasi_products,id',
            'items.*.qty'        => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $totalNominal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = KoperasiProduct::lockForUpdate()->findOrFail($item['product_id']);
                if ($product->stok < $item['qty']) {
                    throw new \Exception("Stok {$product->nama} tidak mencukupi.");
                }
                $subtotal = $product->harga_jual * $item['qty'];
                $totalNominal += $subtotal;
                $itemsData[] = [
                    'koperasi_product_id' => $product->id,
                    'qty' => $item['qty'],
                    'harga_satuan' => $product->harga_jual,
                    'subtotal' => $subtotal,
                ];
                $product->decrement('stok', $item['qty']);
            }

            $walletTrx = null;
            if ($validated['metode_bayar'] === 'ewallet' && $validated['santri_id']) {
                $santri = Santri::findOrFail($validated['santri_id']);
                $wallet = $santri->wallet;
                $walletTrx = $wallet->debit(
                    $totalNominal,
                    'Belanja Koperasi',
                    auth()->id(),
                    null,
                    KoperasiTransaction::class
                );
            }

            $trx = KoperasiTransaction::create([
                'no_transaksi' => KoperasiTransaction::generateNoTransaksi(),
                'santri_id'    => $validated['santri_id'],
                'total_nominal' => $totalNominal,
                'metode_bayar' => $validated['metode_bayar'],
                'wallet_transaction_id' => $walletTrx?->id,
                'created_by'   => auth()->id(),
            ]);

            $trx->items()->createMany($itemsData);

            // Catat ke kas
            KasTransaction::create([
                'type'     => 'pemasukan',
                'kategori' => 'Koperasi',
                'nominal'  => $totalNominal,
                'tanggal'  => today(),
                'keterangan' => "Penjualan Koperasi #{$trx->no_transaksi}",
                'reference_type' => KoperasiTransaction::class,
                'reference_id'   => $trx->id,
                'created_by' => auth()->id(),
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil diproses.']);
    }
}
