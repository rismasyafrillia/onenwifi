<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Carbon\Carbon;
use App\Services\WhatsAppService;

Schedule::call(function () {

    Log::info('Scheduler update status tagihan jalan');

    Tagihan::where('status', 'belum bayar')
        ->where('jatuh_tempo', '<', now())
        ->update(['status' => 'menunggak']);

})->dailyAt('00:10');


Schedule::call(function () {

    Log::info('Scheduler generate tagihan bulanan jalan');

    $now = Carbon::now();
    $periode = $now->format('m-Y');
    $jatuhTempo = $now->copy()->startOfMonth()->addDays(19);

    $pelanggans = Pelanggan::where('status', 'aktif')
        ->whereNotNull('paket_id')
        ->whereDate('created_at', '<', $now->startOfMonth())
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

Schedule::call(function () {

    Log::info('Scheduler pengingat tagihan jalan');

    try {

        $today = Carbon::today();
        $day = $today->day;

        // Hanya kirim di tanggal tertentu
        if (!in_array($day, [1, 10, 15, 20])) {
            Log::info('Bukan tanggal pengingat, dilewati.');
            return;
        }

        $tagihans = Tagihan::with('pelanggan')
            ->whereIn('status', ['belum bayar', 'menunggak'])
            ->get();

        Log::info('Jumlah tagihan akan dikirim: ' . $tagihans->count());

        foreach ($tagihans as $tagihan) {

            $pelanggan = $tagihan->pelanggan;

            if (!$pelanggan) {
                Log::warning("Tagihan ID {$tagihan->id} tidak punya pelanggan");
                continue;
            }

            if (!$pelanggan->no_hp) {
                Log::warning("Pelanggan {$pelanggan->nama} tidak punya no_hp");
                continue;
            }

            $message = "Halo {$pelanggan->nama},\n\n"
                . "Pengingat pembayaran tagihan bulan {$tagihan->periode}.\n"
                . "Total: Rp " . number_format($tagihan->nominal, 0, ',', '.')
                . "\n\nMohon segera lakukan pembayaran.\n\n"
                . "Terima kasih.";

            $response = WhatsAppService::send($pelanggan->no_hp, $message);

            if (isset($response['status']) && $response['status'] == true) {

                Log::info("Berhasil kirim ke {$pelanggan->nama}");

            } else {

                Log::error("Gagal kirim ke {$pelanggan->nama}");
                Log::error('Response: ' . json_encode($response));
            }

            sleep(1);
        }

    } catch (\Exception $e) {

        Log::error('Error utama scheduler pengingat');
        Log::error($e->getMessage());
    }

})->dailyAt('08:00');