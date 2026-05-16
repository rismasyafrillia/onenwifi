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

            // Ambil semua tagihan belum bayar / menunggak
            $tagihans = Tagihan::with('pelanggan')
                ->whereIn('status', ['belum bayar', 'menunggak'])
                ->get()
                ->groupBy('pelanggan_id');

            Log::info('Jumlah pelanggan ditagih: ' . $tagihans->count());

            foreach ($tagihans as $pelangganId => $listTagihan) {

                $pelanggan = $listTagihan->first()->pelanggan;

                if (!$pelanggan) {
                    Log::warning("Pelanggan tidak ditemukan");
                    continue;
                }

                if (!$pelanggan->no_hp) {
                    Log::warning("Pelanggan {$pelanggan->nama} tidak punya no_hp");
                    continue;
                }

                try {

                    $message = "Halo {$pelanggan->nama},\n\n";
                    $message .= "Berikut tagihan Anda yang belum dibayar:\n\n";

                    $total = 0;

                    foreach ($listTagihan as $tagihan) {

                        $message .= "- {$tagihan->periode} : Rp "
                            . number_format($tagihan->nominal, 0, ',', '.')
                            . "\n";

                        $total += $tagihan->nominal;
                    }

                    $message .= "\nTotal Tagihan: Rp "
                        . number_format($total, 0, ',', '.');

                    $message .= "\n\nMohon segera lakukan pembayaran.";
                    $message .= "\n\nTerima kasih.";

                    $response = WhatsAppService::send(
                        $pelanggan->no_hp,
                        $message
                    );

                    if (isset($response['status']) && $response['status'] == true) {

                        Log::info("Berhasil kirim ke {$pelanggan->nama}");

                    } else {

                        Log::error("Gagal kirim ke {$pelanggan->nama}");
                        Log::error('Response: ' . json_encode($response));
                    }

                } catch (\Exception $e) {

                    Log::error("Error kirim ke {$pelanggan->nama}");
                    Log::error($e->getMessage());
                }

                sleep(1);
            }

            $this->info('Pengingat selesai dikirim');

        } catch (\Exception $e) {

            Log::error('Error utama kirim pengingat');
            Log::error($e->getMessage());
        }
    }
}