<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use Illuminate\Http\Request;

class KomplainController extends Controller
{
    public function index()
    {
        $komplains = Komplain::with('pelanggan')
            ->orderByRaw("
                CASE 
                    WHEN status = 'baru' THEN 1
                    WHEN status = 'diproses' THEN 2
                    WHEN status = 'selesai' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.komplain.index', compact('komplains'));
    }

    public function show($id)
    {
        $komplain = Komplain::with('pelanggan')->findOrFail($id);
        return view('admin.komplain.show', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggapan_admin' => 'required',
            'status'          => 'required|in:diproses,selesai'
        ]);

        Komplain::findOrFail($id)->update([
            'tanggapan_admin' => $request->tanggapan_admin,
            'status'          => $request->status
        ]);

        return redirect()
            ->route('admin.komplain.index')
            ->with('success', 'Komplain berhasil ditanggapi');
    }
}
