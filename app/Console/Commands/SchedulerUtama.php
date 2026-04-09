<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SchedulerUtama extends Command
{
    protected $signature = 'schedulertagihan:run';
    protected $description = 'Scheduler utama semua proses';

    public function handle()
    {
        $now = Carbon::now();

        $day = $now->day;
        $hour = $now->hour;
        $minute = $now->minute;

        Log::info("Scheduler utama jalan: {$now}");

        // =========================
        // 1. GENERATE TAGIHAN
        // =========================
        if ($day == 1 && $hour == 8 && $minute <= 00) {
            Log::info('Menjalankan generate tagihan');
            Artisan::call('tagihan:generate');
        }

        // =========================
        // 2. UPDATE STATUS
        // =========================
        if ($hour == 8 && $minute <= 05) {
            Log::info('Menjalankan update status');
            Artisan::call('tagihan:update-status');
        }

        // =========================
        // 3. KIRIM PENGINGAT
        // =========================
        if (in_array($day, [1, 9, 15, 20]) && $hour == 8 && $minute <= 10) {
            Log::info('Menjalankan kirim pengingat');
            Artisan::call('tagihan:ingatkan');
        }

        $this->info('Scheduler selesai');
    }
}