<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanBerhenti;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;

class PengajuanBerhentiController extends Controller
{
    public function index()
    {
        $pengajuans = PengajuanBerhenti::with('pelanggan')
            ->latest()
            ->get();

        return view('admin.pengajuan.index', compact('pengajuans'));
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
            'status' => 'required|in:disetujui,ditolak',
            'catatan_admin' => 'nullable'
        ]);

        $pengajuan = PengajuanBerhenti::with('pelanggan')
            ->findOrFail($id);

        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        // jika disetujui → nonaktifkan pelanggan
        if ($request->status == 'disetujui') {

            $pengajuan->pelanggan->update([
                'status' => 'nonaktif'
            ]);
        }

        // kirim WhatsApp
        $nama = $pengajuan->pelanggan->nama;
        $status = ucfirst($request->status);

        $pesan = "Halo $nama,\n\n"
            . "Pengajuan berhenti langganan Anda telah divalidasi admin.\n"
            . "Status pengajuan: *$status*.\n\n";

        if ($request->catatan_admin) {
            $pesan .= "Catatan Admin:\n"
                . $request->catatan_admin . "\n\n";
        }

        $pesan .= "Terima kasih.";

        WhatsAppService::send(
            $pengajuan->pelanggan->no_hp,
            $pesan
        );

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan berhasil diperbarui');
    }
}