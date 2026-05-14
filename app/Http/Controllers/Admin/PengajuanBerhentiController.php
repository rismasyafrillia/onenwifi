<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\PengajuanBerhenti;
use Illuminate\Http\Request;

class PengajuanBerhentiController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanBerhenti::with('pelanggan')
            ->latest()
            ->get();

        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanBerhenti::with('pelanggan')
            ->findOrFail($id);

        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'catatan_admin' => 'nullable'
        ]);

        $pengajuan = PengajuanBerhenti::findOrFail($id);

        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        if ($request->status == 'disetujui') {

            Pelanggan::where('id', $pengajuan->pelanggan_id)
                ->update([
                    'status' => 'nonaktif'
                ]);
        }

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan berhasil diproses');
    }
}