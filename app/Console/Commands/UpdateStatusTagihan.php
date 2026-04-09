<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Log;

class UpdateStatusTagihan extends Command
{
    protected $signature = 'tagihan:update-status';
    protected $description = 'Update status tagihan menjadi menunggak';

    public function handle()
    {
        Log::info('Command update status tagihan jalan');

        Tagihan::where('status', 'belum bayar')
            ->where('jatuh_tempo', '<', now())
            ->update(['status' => 'menunggak']);

        $this->info('Status tagihan berhasil diupdate');
    }
}