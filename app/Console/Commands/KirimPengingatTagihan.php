<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class KirimPengingatTagihan extends Command
{
    protected $signature = 'tagihan:ingatkan';
    protected $description = 'Kirim pengingat tagihan ke pelanggan';

    public function handle()
    {
        Log::info('Command kirim pengingat tagihan jalan');

        try {

            $tagihans = Tagihan::with('pelanggan')
                ->whereIn('status', ['belum bayar', 'menunggak'])
                ->get();

            Log::info('Jumlah tagihan: ' . $tagihans->count());

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
                        . "Pengingat pembayaran tagihan bulan {$tagihan->periode}.\n"
                        . "Total: Rp " . number_format($tagihan->nominal, 0, ',', '.')
                        . "\n\nMohon segera lakukan pembayaran.\n\n"
                        . "Terima kasih.";

                    $response = WhatsAppService::send($pelanggan->no_hp, $message);

                    if (isset($response['status']) && $response['status'] == true) {

                        Log::info("Berhasil kirim ke {$pelanggan->nama} - {$pelanggan->no_hp}");

                    } else {

                        Log::error("Gagal kirim ke {$pelanggan->nama}");
                        Log::error('Response: ' . json_encode($response));
                    }

                } catch (\Exception $e) {

                    Log::error("Error kirim ke {$pelanggan->nama}");
                    Log::error($e->getMessage());
                }

                sleep(1); // jeda biar tidak spam API
            }

            $this->info('Pengingat selesai dikirim');

        } catch (\Exception $e) {

            Log::error('Error utama kirim pengingat');
            Log::error($e->getMessage());
        }
    }
}