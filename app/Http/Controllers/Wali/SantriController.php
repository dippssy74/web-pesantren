<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index()
    {
        $santris = auth()->user()->santris()->with(['wallet', 'sppPayments' => fn($q) => $q->where('tahun', now()->year)])->get();
        return view('wali.santri.index', compact('santris'));
    }
}
