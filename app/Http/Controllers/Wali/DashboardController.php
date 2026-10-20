<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Wali Santri Dashboard.
     */
    public function index(Request $request): View
    {
        $santri = [
            'nama' => 'Ahmad Faqih Az-Zuhri',
            'nis' => 'SNTR-2026-0042',
            'kelas' => 'Kelas 2 Aliyah (Tahfidz)',
            'kamar' => 'Asrama Al-Farabi Lt. 2 / No. 12',
            'status' => 'Aktif',
            'saldo_wallet' => 285000,
            'limit_harian' => 35000,
            'terpakai_hari_ini' => 12000,
        ];

        $tagihanSpp = [
            'bulan' => 'Oktober 2026',
            'nominal' => 650000,
            'status' => 'Belum Lunas',
            'jatuh_tempo' => '25 Okt 2026',
        ];

        $riwayatJajan = [
            [
                'toko' => 'Kantin Utama',
                'item' => 'Nasi Ayam + Teh Hangat',
                'nominal' => 12000,
                'waktu' => 'Hari Ini, 12:15 WIB',
                'tipe' => 'debit',
            ],
            [
                'toko' => 'Top Up E-Wallet',
                'item' => 'Top up via Transfer Bank (Virtual Account)',
                'nominal' => 150000,
                'waktu' => 'Kemarin, 08:30 WIB',
                'tipe' => 'kredit',
            ],
            [
                'toko' => 'Kopontren Mart',
                'item' => 'Buku Tulis, Kitab Nahwu & Pensil',
                'nominal' => 28000,
                'waktu' => '17 Okt 2026',
                'tipe' => 'debit',
            ],
        ];

        // Mengambil pendaftaran milik wali santri yang sedang login
        $registrations = Registration::where(
            'user_id',
            $request->user()->id
        )->latest()->get();

        $latestRegistration = $registrations->first();

        return view('wali.dashboard', compact(
            'santri',
            'tagihanSpp',
            'riwayatJajan',
            'registrations',
            'latestRegistration'
        ));
    }
}
