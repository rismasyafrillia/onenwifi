<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Komplain;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->mode ?? 'semua';

        $periode = $request->periode ?? now()->format('m-Y');

        $listPeriode = Tagihan::select('periode')
            ->distinct()
            ->orderByDesc('periode')
            ->pluck('periode');

        // QUERY DASAR
        $tagihanQuery = Tagihan::query();

        if ($mode == 'periode') {
            $tagihanQuery->where('periode', $periode);
        }

        // CARD DASHBOARD
        $totalPelanggan = Pelanggan::where('status', 'aktif')->count();

        // "Tagihan Bulan Ini" = yang belum bayar
        $tagihanBulanIni = (clone $tagihanQuery)
            ->where('status', 'belum bayar')
            ->count();

        $tagihanMenunggak = (clone $tagihanQuery)
            ->where('status', 'menunggak')
            ->count();

        $tagihanLunas = (clone $tagihanQuery)
            ->where('status', 'lunas')
            ->count();

        $komplainBaru = Komplain::where('status', 'baru')->count();

        $totalPembayaranBulanIni = Pembayaran::where('status', 'success')
            ->when($mode == 'periode', function ($q) use ($periode) {
                $q->whereHas('tagihan', function ($qq) use ($periode) {
                    $qq->where('periode', $periode);
                });
            })
            ->sum('nominal');

        // =========================
        // GRAFIK PER BULAN
        // =========================

        $bulanLabel = [];
        $dataMenunggak = [];
        $dataLunas = [];

        $tahun = now()->year;

        for ($i = 1; $i <= 12; $i++) {

            $bulan = str_pad($i, 2, '0', STR_PAD_LEFT) . '-' . $tahun;

            $bulanLabel[] = Carbon::create()->month($i)
                ->translatedFormat('F');

            $dataMenunggak[] = Tagihan::where('periode', $bulan)
                ->where('status', 'menunggak')
                ->count();

            $dataLunas[] = Tagihan::where('periode', $bulan)
                ->where('status', 'lunas')
                ->count();
        }

        // =========================
        // MENUNGGAK PER DAERAH
        // =========================

        $daerahLabel = [];
        $daerahMenunggak = [];

        $daerahData = DB::table('tagihans')
            ->join('pelanggan', 'pelanggan.id', '=', 'tagihans.pelanggan_id')
            ->select(
                'pelanggan.daerah',
                DB::raw('COUNT(tagihans.id) as total')
            )
            ->where('tagihans.status', 'menunggak')
            ->when($mode == 'periode', function ($q) use ($periode) {
                $q->where('tagihans.periode', $periode);
            })
            ->groupBy('pelanggan.daerah')
            ->orderByDesc('total')
            ->get();

        foreach ($daerahData as $row) {
            $daerahLabel[] = $row->daerah ?? 'Tidak Diketahui';
            $daerahMenunggak[] = $row->total;
        }

        return view('admin.dashboard', compact(
            'mode',
            'periode',
            'listPeriode',
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

    public function pembayaranBulanIni()
    {
        $pembayarans = Pembayaran::with(['tagihan.pelanggan'])
            ->where('status', 'success')
            ->latest('paid_at')
            ->get();

        return view('admin.pembayaran.bulan_ini',compact('pembayarans')
        );
    }
}