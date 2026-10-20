<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with(['wali', 'wallet'])
            ->when($request->search, fn($q) => $q->where('nama_lengkap', 'like', "%{$request->search}%")
                ->orWhere('nis', 'like', "%{$request->search}%")
                ->orWhere('kelas', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->kelas, fn($q) => $q->where('kelas', $request->kelas))
            ->latest();

        $santris = $query->paginate(15)->withQueryString();
        $kelasList = Santri::distinct()->pluck('kelas')->sort()->values();

        return view('admin.santri.index', compact('santris', 'kelasList'));
    }

    public function create()
    {   
        $waliList = User::where('role', 'wali_santri')->orderBy('name')->get();
        return view('admin.santri.create', compact('waliList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki_laki,perempuan',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'nullable|string',
            'kelas'         => 'required|string|max:50',
            'kamar'         => 'nullable|string|max:50',
            'asrama'        => 'nullable|string|max:100',
            'tahun_masuk'   => 'required|digits:4|integer',
            'status'        => 'required|in:aktif,alumni,keluar',
            'wali_id'       => 'nullable|exists:users,id',
            'catatan'       => 'nullable|string',
        ]);

        $validated['nis'] = Santri::generateNis();

        $santri = Santri::create($validated);

        // Buat wallet otomatis untuk santri baru
        Wallet::create([
            'santri_id' => $santri->id,
            'saldo' => 0,
            'limit_harian' => 50000,
        ]);

        return redirect()->route('admin.santri.show', $santri)
            ->with('success', "Santri {$santri->nama_lengkap} berhasil ditambahkan dengan NIS: {$santri->nis}");
    }

    public function show(Santri $santri)
    {
        $santri->load(['wali', 'wallet.transactions' => fn($q) => $q->latest()->limit(10), 'sppPayments' => fn($q) => $q->latest()]);
        return view('admin.santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        $waliList = User::where('role', 'wali_santri')->orderBy('name')->get();
        return view('admin.santri.edit', compact('santri', 'waliList'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki_laki,perempuan',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'nullable|string',
            'kelas'         => 'required|string|max:50',
            'kamar'         => 'nullable|string|max:50',
            'asrama'        => 'nullable|string|max:100',
            'tahun_masuk'   => 'required|digits:4|integer',
            'status'        => 'required|in:aktif,alumni,keluar',
            'wali_id'       => 'nullable|exists:users,id',
            'catatan'       => 'nullable|string',
        ]);

        $santri->update($validated);

        return redirect()->route('admin.santri.show', $santri)
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $nama = $santri->nama_lengkap;
        $santri->delete();
        return redirect()->route('admin.santri.index')
            ->with('success', "Santri {$nama} berhasil dihapus.");
    }
}
