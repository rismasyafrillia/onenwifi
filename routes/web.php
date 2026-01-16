<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PelangganController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::get('/pelanggan/create', [PelangganController::class, 'create']);
Route::post('/pelanggan', [PelangganController::class, 'store']);

Route::resource('tagihan', TagihanController::class);
Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
Route::post('/tagihan/generate', [TagihanController::class, 'generateBulanan'])->name('tagihan.generate');
Route::get('/tagihan/{id}/edit', [TagihanController::class, 'edit'])->name('tagihan.edit');
Route::put('/tagihan/{id}', [TagihanController::class, 'update'])->name('tagihan.update');
Route::delete('/tagihan/{id}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');

Route::post('/tagihan/generate', 
    [TagihanController::class, 'generateBulanan']
)->name('tagihan.generate');

Route::resource('pelanggan', PelangganController::class);
