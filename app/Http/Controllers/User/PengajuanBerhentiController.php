<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\PengajuanBerhenti;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AdminNotification;

class PengajuanBerhentiController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::where('user_id', auth()->id())->first();

        $pengajuan = PengajuanBerhenti::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get();

        return view('user.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        return view('user.pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'alasan' => 'required'
        ]);

        $pelanggan = Pelanggan::where('user_id', auth()->id())->first();

        PengajuanBerhenti::create([
            'pelanggan_id' => $pelanggan->id,
            'alasan'       => $request->alasan,
            'status'       => 'menunggu'
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AdminNotification(
                'Pengajuan Berhenti',
                $pelanggan->nama . ' mengajukan berhenti berlangganan'
            ));
        }

        return redirect()
            ->route('user.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dikirim');
    }
}