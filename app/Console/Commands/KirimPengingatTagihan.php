<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KirimPengingatTagihan extends Command
{
    protected $signature = 'tagihan:ingatkan';
    protected $description = 'Kirim pengingat tagihan ke pelanggan';

    public function handle()
    {
        Log::info('Scheduler tagihan:ingatkan dijalankan');

        try {

            $hariIni = Carbon::now()->day;

            Log::info('Hari ini tanggal: ' . $hariIni);

            $tagihans = Tagihan::with('pelanggan')
                ->where('status', 'belum_lunas')
                ->get();

            Log::info('Jumlah tagihan ditemukan: ' . $tagihans->count());

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

                try {

                    $message = "Halo {$pelanggan->nama},\n\n"
                        . "Tagihan bulan {$tagihan->periode}\n"
                        . "Total: Rp " . number_format($tagihan->jumlah, 0, ',', '.')
                        . "\n\nMohon segera lakukan pembayaran.\n\n"
                        . "Terima kasih.";

                    WhatsAppService::send($pelanggan->no_hp, $message);

                    Log::info("Berhasil kirim ke {$pelanggan->nama} - {$pelanggan->no_hp}");

                } catch (\Exception $e) {

                    Log::error("Gagal kirim ke {$pelanggan->nama}");
                    Log::error($e->getMessage());
                }
            }

            $this->info('Proses selesai.');

        } catch (\Exception $e) {

            Log::error('Error utama scheduler:');
            Log::error($e->getMessage());
        }
    }
}