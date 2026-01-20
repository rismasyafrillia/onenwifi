<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class KomplainController extends Controller
{
    public function index()
    {
        $komplains = Komplain::with('pelanggan')
            ->latest()
            ->get();

        return view('user.komplain.index', compact('komplains'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        return view('user.komplain.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'judul'        => 'required|max:100',
            'deskripsi'    => 'required'
        ]);

        Komplain::create([
            'pelanggan_id' => $request->pelanggan_id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'status'       => 'baru'
        ]);

        return redirect()
            ->route('user.komplain.index')
            ->with('success', 'Komplain berhasil dikirim');
    }
}
