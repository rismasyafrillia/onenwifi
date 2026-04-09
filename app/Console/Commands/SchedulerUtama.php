<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SchedulerUtama extends Command
{
    protected $signature = 'schedulertagihan:run';
    protected $description = 'Jalankan semua proses tagihan (manual / cron)';

    public function handle()
    {
        Log::info("Scheduler manual dijalankan");

        // 1. Generate tagihan
        Log::info('Menjalankan generate tagihan');
        Artisan::call('tagihan:generate');

        // 2. Update status
        Log::info('Menjalankan update status');
        Artisan::call('tagihan:update-status');

        // 3. Kirim pengingat
        Log::info('Menjalankan kirim pengingat');
        Artisan::call('tagihan:ingatkan');

        $this->info('Semua proses selesai');
    }
}