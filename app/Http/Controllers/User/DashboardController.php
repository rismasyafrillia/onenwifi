<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Komplain;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Total tagihan user
        $totalTagihan = Tagihan::whereHas('pelanggan', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        // Tagihan belum bayar
        $tagihanBelumBayar = Tagihan::whereHas('pelanggan', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'belum bayar')->count();

        // 🔥 FIX KOMPLAIN (INI PENTING)
        $totalKomplain = Komplain::whereHas('pelanggan', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        return view('user.dashboard', compact(
            'totalTagihan',
            'tagihanBelumBayar',
            'totalKomplain'
        ));
    }
}
