<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Komplain;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pelanggan::count();

        $tagihanBulanIni = Tagihan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $tagihanMenunggak = Tagihan::where('status', 'menunggak')->count();
        $tagihanLunas     = Tagihan::where('status', 'lunas')->count();

        $komplainBaru = Komplain::where('status', 'baru')->count();

        $totalPembayaran = Pembayaran::where('status', 'success')->sum('nominal');

        // (opsional) total pembayaran bulan ini
        $totalPembayaranBulanIni = Pembayaran::where('status', 'success')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('nominal');

        // GRAFIK MENUNGGAK vs LUNAS PER BULAN
        $bulanLabel = [];
        $dataMenunggak = [];
        $dataLunas = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulanLabel[] = Carbon::create()->month($i)->translatedFormat('F');

            $dataMenunggak[] = Tagihan::where('status', 'menunggak')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();

            $dataLunas[] = Tagihan::where('status', 'lunas')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        // GRAFIK MENUNGGAK PER DAERAH
        $daerahLabel = [];
        $daerahMenunggak = [];

        $daerahData = DB::table('tagihans')
            ->join('pelanggan', 'pelanggan.id', '=', 'tagihans.pelanggan_id')
            ->select('pelanggan.daerah', DB::raw('COUNT(tagihans.id) as total'))
            ->where('tagihans.status', 'menunggak')
            ->groupBy('pelanggan.daerah')
            ->orderByDesc('total')
            ->get();

        foreach ($daerahData as $row) {
            $daerahLabel[] = $row->daerah ?? 'Tidak Diketahui';
            $daerahMenunggak[] = $row->total;
        }

        // KIRIM KE VIEW
        return view('admin.dashboard', compact(
            'totalPelanggan',
            'tagihanBulanIni',
            'tagihanMenunggak',
            'tagihanLunas',
            'komplainBaru',
            'totalPembayaranBulanIni',
            'bulanLabel',
            'dataMenunggak',
            'dataLunas',
            'daerahLabel',
            'daerahMenunggak'
        ));
    }
}
