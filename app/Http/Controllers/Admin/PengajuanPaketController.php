<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPaket;
use Illuminate\Http\Request;

class PengajuanPaketController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanPaket::with([
                'pelanggan',
                'paketLama',
                'paketBaru'
            ])
            ->latest()
            ->get();

        return view('admin.pengajuan_paket.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanPaket::with([
                'pelanggan',
                'paketLama',
                'paketBaru'
            ])
            ->findOrFail($id);

        return view('admin.pengajuan_paket.show', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_admin' => 'nullable'
        ]);

        $pengajuan = PengajuanPaket::findOrFail($id);

        // hanya bisa awal bulan
        if (now()->day > 5 && $request->status == 'disetujui') {

            return back()->with(
                'error',
                'Perubahan paket hanya bisa diproses tanggal 1 - 5 setiap bulan'
            );
        }

        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        // jika disetujui
        if ($request->status == 'disetujui') {

            $pengajuan->pelanggan->update([
                'paket_id' => $pengajuan->paket_baru_id
            ]);
        }

        return redirect()
            ->route('admin.pengajuan-paket.index')
            ->with('success', 'Pengajuan berhasil diproses');
    }
}