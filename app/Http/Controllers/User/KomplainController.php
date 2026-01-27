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
            'judul'     => 'required|max:100',
            'deskripsi' => 'required'
        ]);

        $user = auth()->user();

        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return back()->with('error', 'Data pelanggan tidak ditemukan');
        }

        Komplain::create([
            'pelanggan_id' => $pelanggan->id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'status'       => 'baru'
        ]);

        return redirect()
            ->route('user.komplain.index')
            ->with('success', 'Komplain berhasil dikirim');
    }

    public function show($id)
    {
        $user = auth()->user();

        $komplain = Komplain::where('id', $id)
            ->whereHas('pelanggan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        return view('user.komplain.show', compact('komplain'));
    }
}
