<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| UPDATE STATUS TAGIHAN (HARIAN)
|--------------------------------------------------------------------------
*/
Schedule::call(function () {

    Log::info('Scheduler update status tagihan jalan');

    Tagihan::where('status', 'belum bayar')
        ->where('jatuh_tempo', '<', now())
        ->update(['status' => 'menunggak']);

})->dailyAt('00:10');


/*
|--------------------------------------------------------------------------
| GENERATE TAGIHAN BULANAN (AWAL BULAN)
|--------------------------------------------------------------------------
*/
Schedule::call(function () {

    Log::info('Scheduler generate tagihan bulanan jalan');

    $now = Carbon::now();
    $periode = $now->format('m-Y');
    $jatuhTempo = $now->copy()->startOfMonth()->addDays(19);

    $pelanggans = Pelanggan::where('status', 'aktif')
        ->whereNotNull('paket_id')
        ->whereDate('created_at', '<', $now->startOfMonth()) // 🔑 pelanggan baru TIDAK ikut
        ->with('paket')
        ->get();

    foreach ($pelanggans as $p) {

        if (Tagihan::where('pelanggan_id', $p->id)
            ->where('periode', $periode)
            ->exists()) {
            continue;
        }

        $tunggakan = Tagihan::where('pelanggan_id', $p->id)
            ->whereIn('status', ['belum bayar', 'menunggak'])
            ->sum('nominal');

        Tagihan::create([
            'pelanggan_id' => $p->id,
            'periode'      => $periode,
            'nominal'      => ($p->paket->harga ?? 0) + $tunggakan,
            'jatuh_tempo'  => $jatuhTempo,
            'status'       => 'belum bayar'
        ]);
    }

})->monthlyOn(1, '00:05');
