<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Komplain;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // KARTU RINGKASAN
        $totalPelanggan = Pelanggan::count();

        $tagihanBulanIni = Tagihan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $tagihanMenunggak = Tagihan::where('status', 'menunggak')->count();

        $komplainBaru = Komplain::where('status', 'baru')->count();

        // DATA GRAFIK MENUNGGAK PER BULAN
        $bulanLabel = [];
        $bulanData = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulanLabel[] = Carbon::create()->month($i)->translatedFormat('F');

            $bulanData[] = Tagihan::where('status', 'menunggak')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        return view('admin.dashboard', compact(
            'totalPelanggan',
            'tagihanBulanIni',
            'tagihanMenunggak',
            'komplainBaru',
            'bulanLabel',
            'bulanData'
        ));
    }
}
