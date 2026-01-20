<?php

use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\KomplainController as AdminKomplainController;

use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\KomplainController as UserKomplainController;
use App\Http\Controllers\User\TagihanUserController;

use App\Http\Controllers\MidtransController;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Komplain;

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
Route::prefix('admin')->name('admin.')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    // PELANGGAN
    Route::resource('pelanggan', PelangganController::class);

    // TAGIHAN
    Route::get('tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('tagihan/generate', [TagihanController::class, 'generateBulanan'])->name('tagihan.generate');
    Route::get('tagihan/{id}/edit', [TagihanController::class, 'edit'])->name('tagihan.edit');
    Route::put('tagihan/{id}', [TagihanController::class, 'update'])->name('tagihan.update');

    // LAPORAN
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // KOMPLAIN ADMIN
    Route::get('komplain', [AdminKomplainController::class, 'index'])->name('komplain.index');
    Route::get('komplain/{id}', [AdminKomplainController::class, 'show'])->name('komplain.show');
    Route::put('komplain/{id}', [AdminKomplainController::class, 'update'])->name('komplain.update');
});


Route::prefix('user')->name('user.')->group(function () {

    Route::get('/dashboard', [UserDashboard::class, 'index'])
    ->name('dashboard');

    // KOMPLAIN USER
    Route::get('komplain', [UserKomplainController::class, 'index'])->name('komplain.index');
    Route::get('komplain/create', [UserKomplainController::class, 'create'])->name('komplain.create');
    Route::post('komplain', [UserKomplainController::class, 'store'])->name('komplain.store');

    // TAGIHAN USER
    Route::get('tagihan', [TagihanUserController::class, 'index'])
        ->name('tagihan.index');

    Route::get('tagihan/{id}', [TagihanUserController::class, 'show'])
        ->name('tagihan.show');

    Route::post('tagihan/{id}/bayar', [TagihanUserController::class, 'bayar'])
        ->name('tagihan.bayar');

    Route::get('profile', function () {
        return view('user.profile');
    })->name('profile');
});
