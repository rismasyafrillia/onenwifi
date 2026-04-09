<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateTagihanBulanan extends Command
{
    protected $signature = 'tagihan:generate';
    protected $description = 'Generate tagihan bulanan pelanggan';

    public function handle()
    {
        Log::info('Command generate tagihan bulanan jalan');

        try {

            $now = Carbon::now();
            $periode = $now->format('m-Y');
            $jatuhTempo = $now->copy()->startOfMonth()->addDays(19);

            $pelanggans = Pelanggan::where('status', 'aktif')
                ->whereNotNull('paket_id')
                ->whereDate('mulai_tagihan', '<=', $now)
                ->with('paket')
                ->get();

            Log::info('Jumlah pelanggan: ' . $pelanggans->count());

            foreach ($pelanggans as $p) {

                // Cegah double tagihan
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

            $this->info('Tagihan bulanan berhasil digenerate');

        } catch (\Exception $e) {

            Log::error('Error generate tagihan');
            Log::error($e->getMessage());
        }
    }
}