<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransCallbackController;
use Illuminate\Support\Facades\Artisan;
// use App\Http\Controllers\MidtransNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Route ini digunakan untuk kebutuhan server-to-server
| seperti callback payment gateway Midtrans
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);
// Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle']);
// Route::get('/ping', function () {
//     return response()->json([
//         'status' => 'ok',
//         'time' => now()
//     ]);
// });

Route::get('/cron/{token}', function ($token) {

    if ($token !== env('CRON_TOKEN')) {
        abort(403);
    }

    Artisan::call('tagihan:generate');
    Artisan::call('tagihan:update-status');
    Artisan::call('tagihan:ingatkan');

    return 'Cron berhasil dijalankan';
});