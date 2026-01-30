<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Carbon\Carbon;

class GenerateTagihanBulanan extends Command
{
    protected $signature = 'tagihan:generate';
    protected $description = 'Generate tagihan bulanan pelanggan';

    public function handle()
    {
        $now = Carbon::now();
        $periode = $now->format('m-Y');
        $jatuhTempo = $now->copy()->startOfMonth()->addDays(19);

        $pelanggans = Pelanggan::where('status','aktif')
            ->whereNotNull('paket_id')
            ->whereDate('mulai_tagihan', '<=', $now) // 🔑 penting
            ->with('paket')
            ->get();

        foreach ($pelanggans as $p) {

            if (Tagihan::where('pelanggan_id', $p->id)
                ->where('periode', $periode)
                ->exists()) {
                continue;
            }

            $tunggakan = Tagihan::where('pelanggan_id', $p->id)
                ->whereIn('status',['belum bayar','menunggak'])
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
    }
}
