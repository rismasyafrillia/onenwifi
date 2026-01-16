<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index', [
            'totalTagihan' => Tagihan::count(),
            'lunas'        => Tagihan::where('status','lunas')->count(),
            'menunggak'    => Tagihan::whereIn('status',['belum bayar','menunggak'])->count(),
            'totalNominal' => Tagihan::where('status','lunas')->sum('nominal'),
        ]);
    }
}
