<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Tagihan;
use Carbon\Carbon;

Schedule::call(function () {

    Log::info('SCHEDULER TAGIHAN JALAN', [
        'waktu' => now()->toDateTimeString(),
    ]);

    Tagihan::where('status', 'belum bayar')
        ->where('jatuh_tempo', '<=', Carbon::yesterday())
        ->update([
            'status' => 'menunggak'
        ]);

})->everyMinute(); // sementara untuk testing
