<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\KomplainController as AdminKomplainController;
use App\Http\Controllers\Admin\PengajuanBerhentiController as AdminPengajuanController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\KomplainController as UserKomplainController;
use App\Http\Controllers\User\TagihanUserController;
use App\Http\Controllers\User\PengajuanBerhentiController as UserPengajuanController;

use App\Http\Controllers\PushController;
// use App\Http\Controllers\MidtransNotificationController;
// use App\Http\Controllers\MidtransCallbackController;

Route::get('/', function () {
    return redirect()->route('login');
});
// Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle']);
// Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);
        
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/vapid-public-key', [PushController::class, 'vapidPublicKey']);
Route::post('/subscribe', [PushController::class, 'subscribe']);
Route::get('/send-push', [PushController::class, 'send']);

//admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/pembayaran/bulan-ini', [App\Http\Controllers\Admin\DashboardController::class, 'pembayaranBulanIni'])->name('pembayaran.bulanIni');

        Route::resource('pelanggan', PelangganController::class);
        Route::put('pelanggan/{id}/terpasang', [PelangganController::class, 'setTerpasang'])->name('pelanggan.terpasang');

        Route::get('tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
        Route::post('tagihan/generate', [TagihanController::class, 'generateBulanan'])->name('tagihan.generate');
        Route::get('tagihan/{id}/edit', [TagihanController::class, 'edit'])->name('tagihan.edit');
        Route::put('tagihan/{id}', [TagihanController::class, 'update'])->name('tagihan.update');
        Route::post('tagihan/{id}/bayar-cash', [TagihanController::class, 'bayarCash'])->name('tagihan.bayarCash');

        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');

        Route::get('komplain', [AdminKomplainController::class, 'index'])->name('komplain.index');
        Route::get('komplain/{id}', [AdminKomplainController::class, 'show'])->name('komplain.show');
        Route::put('komplain/{id}', [AdminKomplainController::class, 'update'])->name('komplain.update');

        Route::get('/pengajuan-berhenti', [PengajuanBerhentiController::class, 'index'])
        ->name('pengajuan.index');
        Route::get('/pengajuan-berhenti/{id}', [PengajuanBerhentiController::class, 'show'])
        ->name('pengajuan.show');
        Route::put('/pengajuan-berhenti/{id}', [PengajuanBerhentiController::class, 'update'])
        ->name('pengajuan.update');

        Route::get('/pengajuan-berhenti', [AdminPengajuanController::class, 'index'])
            ->name('pengajuan.index');
        Route::get('/pengajuan-berhenti/{id}', [AdminPengajuanController::class, 'show'])
            ->name('pengajuan.show');
        Route::put('/pengajuan-berhenti/{id}', [AdminPengajuanController::class, 'update'])
            ->name('pengajuan.update');

        Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    })->name('logout');
    });

//user
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('komplain', [UserKomplainController::class, 'index'])->name('komplain.index');
        Route::get('komplain/create', [UserKomplainController::class, 'create'])->name('komplain.create');
        Route::post('komplain', [UserKomplainController::class, 'store'])->name('komplain.store');
        Route::get('komplain/{id}', [UserKomplainController::class, 'show'])
             ->name('komplain.show');

        Route::get('/pengajuan-berhenti', [PengajuanBerhentiController::class, 'index'])
        ->name('pengajuan.index');
        Route::get('/pengajuan-berhenti/create', [PengajuanBerhentiController::class, 'create'])
            ->name('pengajuan.create');
        Route::post('/pengajuan-berhenti', [PengajuanBerhentiController::class, 'store'])
            ->name('pengajuan.store');
        
        Route::get('tagihan', [TagihanUserController::class, 'index'])->name('tagihan.index');
        Route::get('tagihan/{id}', [TagihanUserController::class, 'show'])->name('tagihan.show');
        Route::post('tagihan/{id}/bayar', [TagihanUserController::class, 'bayar'])->name('tagihan.bayar');
        Route::get('riwayat', [TagihanUserController::class, 'riwayat'])->name('riwayat.index');
        Route::get('riwayat/{id}', [TagihanUserController::class, 'detail'])->name('riwayat.show');
        Route::get('riwayat/{id}/cetak',[TagihanUserController::class, 'cetak'])->name('riwayat.cetak');

        Route::get('/pengajuan-berhenti', [UserPengajuanController::class, 'index'])
            ->name('pengajuan.index');
        Route::get('/pengajuan-berhenti/create', [UserPengajuanController::class, 'create'])
            ->name('pengajuan.create');
        Route::post('/pengajuan-berhenti', [UserPengajuanController::class, 'store'])
            ->name('pengajuan.store');

        Route::get('profile', [UserDashboardController::class, 'profile'])
             ->name('profile');
    });
