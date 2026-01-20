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
        $totalPelanggan = Pelanggan::count();

        $tagihanBulanIni = Tagihan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $tagihanMenunggak = Tagihan::whereIn('status', ['belum bayar','menunggak'])->count();

        $komplainBaru = Komplain::where('status', 'baru')->count();

        return view('admin.dashboard', [
            'totalPelanggan'   => Pelanggan::count(),
            'tagihanBulanIni'  => Tagihan::whereMonth('created_at', now()->month)->count(),
            'tagihanMenunggak' => Tagihan::where('status', 'menunggak')->count(),
            'komplainBaru'     => Komplain::where('status', 'baru')->count(),
        ]);
    }
}
