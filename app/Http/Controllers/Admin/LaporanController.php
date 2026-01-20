<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = Tagihan::query();

        // FILTER BULAN
        if ($bulan) {
            $query->whereMonth('created_at', $bulan);
        }

        // FILTER TAHUN
        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        // DATA TABEL (INI YANG KEMARIN HILANG)
        $tagihans = (clone $query)
            ->orderBy('created_at', 'desc')
            ->get();

        // STATISTIK
        $totalTagihan = (clone $query)->count();
        $lunas        = (clone $query)->where('status','lunas')->count();
        $menunggak    = (clone $query)->whereIn('status',['belum bayar','menunggak'])->count();
        $totalNominal = (clone $query)->where('status','lunas')->sum('nominal');

        // GRAFIK TREN MENUNGGAK PER BULAN
        $bulanLabel = [];
        $bulanData  = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulanLabel[] = date('F', mktime(0,0,0,$i,1));

            $bulanData[] = Tagihan::whereMonth('created_at', $i)
                ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
                ->whereIn('status',['belum bayar','menunggak'])
                ->count();
        }

        return view('admin.laporan.index', compact(
            'totalTagihan',
            'lunas',
            'menunggak',
            'totalNominal',
            'bulanLabel',
            'bulanData',
            'tagihans'
        ));
    }
}
