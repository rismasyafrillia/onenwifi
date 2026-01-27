<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\KomplainController as AdminKomplainController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\KomplainController as UserKomplainController;
use App\Http\Controllers\User\TagihanUserController;

use App\Http\Controllers\MidtransController;

/*
|--------------------------------------------------------------------------
| PUBLIC (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');
/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
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
        Route::get('laporan/export/pdf', [LaporanController::class, 'exportPdf'])
            ->name('laporan.export.pdf');

        // KOMPLAIN
        Route::get('komplain', [AdminKomplainController::class, 'index'])->name('komplain.index');
        Route::get('komplain/{id}', [AdminKomplainController::class, 'show'])->name('komplain.show');
        Route::put('komplain/{id}', [AdminKomplainController::class, 'update'])->name('komplain.update');
    });

/*
|--------------------------------------------------------------------------
| USER AREA
|--------------------------------------------------------------------------
*/
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        // KOMPLAIN
        Route::get('komplain', [UserKomplainController::class, 'index'])->name('komplain.index');
        Route::get('komplain/create', [UserKomplainController::class, 'create'])->name('komplain.create');
        Route::post('komplain', [UserKomplainController::class, 'store'])->name('komplain.store');
        Route::get('komplain/{id}', [UserKomplainController::class, 'show'])
             ->name('komplain.show');

        // TAGIHAN
        Route::get('tagihan', [TagihanUserController::class, 'index'])->name('tagihan.index');
        Route::get('tagihan/{id}', [TagihanUserController::class, 'show'])->name('tagihan.show');
        Route::post('tagihan/{id}/bayar', [TagihanUserController::class, 'bayar'])->name('tagihan.bayar');
        Route::get('pembayaran/riwayat', [TagihanUserController::class, 'riwayat'])
             ->name('pembayaran.riwayat');

        Route::get('profile', [UserDashboardController::class, 'profile'])
             ->name('profile');
    });
