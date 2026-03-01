<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan   = $request->bulan;
        $tahun   = $request->tahun;
        $status  = $request->status;
        $nama    = $request->nama;

        $query = Tagihan::with('pelanggan');

        // FILTER BULAN
        if ($bulan) {
            $query->whereMonth('created_at', $bulan);
        }

        // FILTER TAHUN
        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        // FILTER STATUS
        if ($status && $status != 'semua') {
            $query->where('status', $status);
        }

        // FILTER NAMA PELANGGAN
        if ($nama) {
            $query->whereHas('pelanggan', function($q) use ($nama){
                $q->where('nama', 'like', "%$nama%");
            });
        }

        $tagihans = (clone $query)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTagihan = (clone $query)->count();
        $lunas        = (clone $query)->where('status','lunas')->count();
        $menunggak    = (clone $query)->whereIn('status',['belum bayar','menunggak'])->count();
        $totalNominal = (clone $query)->where('status','lunas')->sum('nominal');

        return view('admin.laporan.index', compact(
            'totalTagihan',
            'lunas',
            'menunggak',
            'totalNominal',
            'tagihans'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan   = $request->bulan;
        $tahun   = $request->tahun;
        $status  = $request->status;
        $nama    = $request->nama;

        $query = Tagihan::with('pelanggan');

        if ($bulan) {
            $query->whereMonth('created_at', $bulan);
        }

        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        if ($status && $status != 'semua') {
            $query->where('status', $status);
        }

        if ($nama) {
            $query->whereHas('pelanggan', function($q) use ($nama){
                $q->where('nama', 'like', "%$nama%");
            });
        }

        $tagihans = $query->orderBy('created_at', 'desc')->get();

        $totalTagihan = $tagihans->count();
        $lunas        = $tagihans->where('status','lunas')->count();
        $menunggak    = $tagihans->whereIn('status',['belum bayar','menunggak'])->count();
        $totalNominal = $tagihans->where('status','lunas')->sum('nominal');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'tagihans',
            'totalTagihan',
            'lunas',
            'menunggak',
            'totalNominal',
            'bulan',
            'tahun',
            'status',
            'nama'
        ))->setPaper('A4', 'portrait');

        return $pdf->download('laporan-pembayaran.pdf');
    }
}
