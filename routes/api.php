<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransCallbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Route ini digunakan untuk kebutuhan server-to-server
| seperti callback payment gateway Midtrans
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);

// Route::get('/ping', function () {
//     return response()->json([
//         'status' => 'ok',
//         'time' => now()
//     ]);
// });
