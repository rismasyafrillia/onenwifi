<?php

namespace App\Http\Controllers;

use App\Models\Komplain;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class KomplainController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | KOMPLAIN PELANGGAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        // sementara tampilkan semua komplain pelanggan
        // (nanti difilter by auth)
        $komplains = Komplain::with('pelanggan')
            ->latest()
            ->get();

        return view('user.komplain.index', compact('komplains'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all(); // pilih pelanggan manual
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
            ->route('komplain.index')
            ->with('success', 'Komplain berhasil dikirim');
    }

    /*
    |--------------------------------------------------------------------------
    | KOMPLAIN ADMIN
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        $komplains = Komplain::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.komplain.index', compact('komplains'));
    }

    public function adminShow($id)
    {
        $komplain = Komplain::with('pelanggan')->findOrFail($id);

        return view('admin.komplain.show', compact('komplain'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'tanggapan_admin' => 'required',
            'status'          => 'required|in:diproses,selesai'
        ]);

        $komplain = Komplain::findOrFail($id);

        $komplain->update([
            'tanggapan_admin' => $request->tanggapan_admin,
            'status'          => $request->status
        ]);

        return redirect()
            ->route('admin.komplain.index')
            ->with('success', 'Komplain berhasil ditanggapi');
    }
}
