<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;
use App\Http\Controllers\Wali\RegistrationController as WaliRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'wali_santri') {
        return redirect()->route('wali.dashboard');
    }

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Admin: Santri ────────────────────────────────────────────────────────────
Route::prefix('admin/santri')->name('admin.santri.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SantriController::class, 'index'])->name('index');
    Route::get('/tambah', [\App\Http\Controllers\Admin\SantriController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\SantriController::class, 'store'])->name('store');
    Route::get('/{santri}', [\App\Http\Controllers\Admin\SantriController::class, 'show'])->name('show');
    Route::get('/{santri}/edit', [\App\Http\Controllers\Admin\SantriController::class, 'edit'])->name('edit');
    Route::patch('/{santri}', [\App\Http\Controllers\Admin\SantriController::class, 'update'])->name('update');
    Route::delete('/{santri}', [\App\Http\Controllers\Admin\SantriController::class, 'destroy'])->name('destroy');
});

// ─── Admin: SPP ───────────────────────────────────────────────────────────────
Route::prefix('admin/spp')->name('admin.spp.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SppController::class, 'index'])->name('index');
    Route::post('/generate', [\App\Http\Controllers\Admin\SppController::class, 'generateTagihan'])->name('generate');
    Route::post('/{payment}/bayar', [\App\Http\Controllers\Admin\SppController::class, 'bayar'])->name('bayar');
    Route::get('/riwayat', [\App\Http\Controllers\Admin\SppController::class, 'riwayat'])->name('riwayat');
});

// ─── Admin: Kas ───────────────────────────────────────────────────────────────
Route::prefix('admin/kas')->name('admin.kas.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\KasController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\Admin\KasController::class, 'store'])->name('store');
    Route::delete('/{kasTransaction}', [\App\Http\Controllers\Admin\KasController::class, 'destroy'])->name('destroy');
});

// ─── Admin: Wallet ────────────────────────────────────────────────────────────
Route::prefix('admin/wallet')->name('admin.wallet.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\WalletController::class, 'index'])->name('index');
    Route::post('/{wallet}/topup', [\App\Http\Controllers\Admin\WalletController::class, 'topup'])->name('topup');
    Route::get('/transactions', [\App\Http\Controllers\Admin\WalletController::class, 'transactions'])->name('transactions');
});

// ─── Admin: Koperasi ──────────────────────────────────────────────────────────
Route::prefix('admin/koperasi')->name('admin.koperasi.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\KoperasiController::class, 'index'])->name('index');
    Route::get('/kasir', [\App\Http\Controllers\Admin\KoperasiController::class, 'kasir'])->name('kasir');
    Route::post('/kasir/proses', [\App\Http\Controllers\Admin\KoperasiController::class, 'processKasir'])->name('kasir.process');
    Route::get('/produk', [\App\Http\Controllers\Admin\KoperasiController::class, 'products'])->name('products');
    Route::get('/produk/tambah', [\App\Http\Controllers\Admin\KoperasiController::class, 'createProduct'])->name('products.create');
    Route::post('/produk', [\App\Http\Controllers\Admin\KoperasiController::class, 'storeProduct'])->name('products.store');
    Route::patch('/produk/{product}', [\App\Http\Controllers\Admin\KoperasiController::class, 'updateProduct'])->name('products.update');
    Route::delete('/produk/{product}', [\App\Http\Controllers\Admin\KoperasiController::class, 'destroyProduct'])->name('products.destroy');
});

// ─── Admin: Donasi ────────────────────────────────────────────────────────────
Route::prefix('admin/donasi')->name('admin.donasi.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DonationController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\Admin\DonationController::class, 'store'])->name('store');
    Route::delete('/{donation}', [\App\Http\Controllers\Admin\DonationController::class, 'destroy'])->name('destroy');
});

// ─── Admin: Laporan ───────────────────────────────────────────────────────────
Route::prefix('admin/laporan')->name('admin.laporan.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
});

// ─── Wali: Santri ─────────────────────────────────────────────────────────────
Route::prefix('wali/santri')->name('wali.santri.')->middleware(['auth', 'verified', 'role:wali_santri'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Wali\SantriController::class, 'index'])->name('index');
});

// ─── Wali: SPP ────────────────────────────────────────────────────────────────
Route::prefix('wali/spp')->name('wali.spp.')->middleware(['auth', 'verified', 'role:wali_santri'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Wali\SppController::class, 'index'])->name('index');
});

// ─── Wali: Wallet ─────────────────────────────────────────────────────────────
Route::prefix('wali/wallet')->name('wali.wallet.')->middleware(['auth', 'verified', 'role:wali_santri'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Wali\WalletController::class, 'index'])->name('index');
    Route::post('/topup', [\App\Http\Controllers\Wali\WalletController::class, 'topup'])->name('topup');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Portal Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // PSB Online: Manajemen Pendaftaran
        Route::get('/psb', [AdminRegistrationController::class, 'index'])
            ->name('psb.index');

        Route::get('/psb/{registration}', [AdminRegistrationController::class, 'show'])
            ->name('psb.show');

        Route::patch('/psb/{registration}/status', [AdminRegistrationController::class, 'updateStatus'])
            ->name('psb.update-status');

        Route::get('/psb/{registration}/preview/{type}', [AdminRegistrationController::class, 'previewDocument'])
            ->name('psb.preview');
    });

/*
|--------------------------------------------------------------------------
| Portal Wali Santri Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:wali_santri'])
    ->prefix('wali')
    ->name('wali.')
    ->group(function () {
        Route::get('/dashboard', [WaliDashboardController::class, 'index'])
            ->name('dashboard');

        // PSB Online: Pendaftaran Santri Baru
        Route::get('/pendaftaran', [WaliRegistrationController::class, 'create'])
            ->name('pendaftaran.index');

        Route::get('/pendaftaran/baru', [WaliRegistrationController::class, 'create'])
            ->name('pendaftaran.create');

        Route::post('/pendaftaran', [WaliRegistrationController::class, 'store'])
            ->name('pendaftaran.store');

        Route::get('/pendaftaran/riwayat', [WaliRegistrationController::class, 'index'])
            ->name('pendaftaran.riwayat');

        Route::get('/pendaftaran/{registration}', [WaliRegistrationController::class, 'show'])
            ->name('pendaftaran.show');
    });
