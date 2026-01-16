<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KomplainController;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Komplain;

/*
|--------------------------------------------------------------------------
| HOME / DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('admin.beranda', [
        'totalPelanggan'   => Pelanggan::count(),
        'tagihanBulanIni'  => Tagihan::whereMonth('created_at', now()->month)->count(),
        'tagihanMenunggak' => Tagihan::where('status', 'menunggak')->count(),
        'komplainBaru'     => Komplain::where('status', 'baru')->count(),
    ]);
})->name('beranda');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // ================= PELANGGAN =================
    Route::resource('pelanggan', PelangganController::class);

    // ================= TAGIHAN =================
    Route::get('tagihan', [TagihanController::class, 'index'])
        ->name('tagihan.index');

    Route::post('tagihan/generate', [TagihanController::class, 'generateBulanan'])
    ->name('tagihan.generate');

    Route::get('tagihan/{id}/edit', [TagihanController::class, 'edit'])
        ->name('tagihan.edit');

    Route::put('tagihan/{id}', [TagihanController::class, 'update'])
        ->name('tagihan.update');

    // ================= LAPORAN =================
    Route::get('laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    // ================= KOMPLAIN =================
    Route::get('komplain', [KomplainController::class, 'adminIndex'])
        ->name('komplain.index');

    Route::get('komplain/{id}', [KomplainController::class, 'adminShow'])
        ->name('komplain.show');

    Route::put('komplain/{id}', [KomplainController::class, 'adminUpdate'])
        ->name('komplain.update');
});

/*
|--------------------------------------------------------------------------
| USER / PELANGGAN (TANPA AUTH)
|--------------------------------------------------------------------------
*/
Route::get('/komplain', [KomplainController::class, 'index'])
    ->name('komplain.index');

Route::get('/komplain/create', [KomplainController::class, 'create'])
    ->name('komplain.create');

Route::post('/komplain', [KomplainController::class, 'store'])
    ->name('komplain.store');
