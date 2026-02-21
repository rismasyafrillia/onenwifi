<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Tagihan;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Kirim pengingat setiap hari jam 08:00
        $schedule->call(function () {

            $today = Carbon::today();

            // Ambil tagihan yang belum lunas dan jatuh tempo hari ini
            $tagihans = Tagihan::where('status', 'belum_lunas')
                ->whereDate('jatuh_tempo', $today)
                ->get();

            foreach ($tagihans as $tagihan) {

                $pelanggan = $tagihan->pelanggan; // pastikan ada relasi

                if ($pelanggan && $pelanggan->no_hp) {

                    $message = "Halo {$pelanggan->nama},\n\n"
                        . "Tagihan bulan {$tagihan->periode} sebesar Rp "
                        . number_format($tagihan->jumlah, 0, ',', '.')
                        . " jatuh tempo hari ini.\n\n"
                        . "Segera lakukan pembayaran agar layanan tidak terblokir.\n\n"
                        . "Terima kasih.";

                    WhatsAppService::send($pelanggan->no_hp, $message);
                }
            }

        })->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
