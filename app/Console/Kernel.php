<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Tagihan;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {

            Log::info('Scheduler pengingat tagihan dijalankan');

            try {

                $today = Carbon::today();
                $day = $today->day;

                Log::info('Tanggal hari ini: ' . $day);

                // Hanya kirim di tanggal tertentu
                if (!in_array($day, [1, 10, 15, 20])) {
                    Log::info('Bukan tanggal pengingat, dilewati.');
                    return;
                }

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
                            . "Pengingat pembayaran tagihan bulan {$tagihan->periode}.\n"
                            . "Total: Rp " . number_format($tagihan->jumlah, 0, ',', '.')
                            . "\n\nMohon segera lakukan pembayaran.\n\n"
                            . "Terima kasih.";

                        WhatsAppService::send($pelanggan->no_hp, $message);

                        Log::info("Berhasil kirim ke {$pelanggan->nama}");

                    } catch (\Exception $e) {

                        Log::error("Gagal kirim ke {$pelanggan->nama}");
                        Log::error($e->getMessage());
                    }
                }

            } catch (\Exception $e) {

                Log::error('Error utama scheduler');
                Log::error($e->getMessage());
            }

        })->everyMinute(); // ⬅ ganti dari dailyAt('08:00')
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}