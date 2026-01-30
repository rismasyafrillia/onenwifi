<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function index()
    {
        Tagihan::prosesOtomatis();

        $tagihan = Tagihan::with('pelanggan.paket')
            ->orderBy('jatuh_tempo', 'desc')
            ->get();

        return view('admin.tagihan.index', compact('tagihan'));
    }

    public function generateBulanan()
    {
        Tagihan::prosesOtomatis();

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Tagihan bulan ini berhasil digenerate');
    }

    public function edit($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return redirect()
                ->route('admin.tagihan.index')
                ->with('error', 'Tagihan lunas tidak dapat diubah');
        }

        return view('admin.tagihan.edit', compact('tagihan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nominal'     => 'required|numeric|min:1000',
            'jatuh_tempo' => 'required|date',
            'status'      => 'required|in:belum bayar,menunggak,lunas'
        ]);

        Tagihan::findOrFail($id)->update([
            'nominal'     => $request->nominal,
            'jatuh_tempo' => $request->jatuh_tempo,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil diperbarui');
    }
}
