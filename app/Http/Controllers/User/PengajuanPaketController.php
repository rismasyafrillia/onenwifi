<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\PengajuanPaket;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AdminNotification;

class PengajuanPaketController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        $pengajuans = PengajuanPaket::with([
                'paketLama',
                'paketBaru'
            ])
            ->where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get();

        return view('user.pengajuan_paket.index', compact('pengajuans'));
    }

    public function create()
    {
        $user = auth()->user();

        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        $pakets = Paket::where('status', 'aktif')
            ->where('id', '!=', $pelanggan->paket_id)
            ->get();

        return view('user.pengajuan_paket.create', compact(
            'pelanggan',
            'pakets'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paket_baru_id' => 'required',
            'alasan' => 'required'
        ]);

        $user = auth()->user();

        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        PengajuanPaket::create([
            'pelanggan_id' => $pelanggan->id,
            'paket_lama_id' => $pelanggan->paket_id,
            'paket_baru_id' => $request->paket_baru_id,
            'alasan' => $request->alasan,
            'status' => 'menunggu'
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AdminNotification(
                'Perubahan Paket',
                $pelanggan->nama . ' mengajukan perubahan paket'
            ));
        }

        return redirect()
            ->route('user.pengajuan-paket.index')
            ->with('success', 'Pengajuan berhasil dikirim');
    }
}