<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Paket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with('paket')->get();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $pakets = Paket::where('status', 'aktif')->get();
        return view('admin.pelanggan.create', compact('pakets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'alamat'   => 'required',
            'daerah' => 'required',
            'paket_id' => 'required|exists:pakets,id',
            'status'   => 'required|in:aktif,nonaktif'
        ]);

        $username = strtolower(str_replace(' ', '', $request->nama)) . rand(100,999);

        $user = User::create([
            'name'     => $request->nama,
            'email'    => $username . '@onenwifi.test',
            'password' => Hash::make('12345678'),
            'role'     => 'user',
        ]);

        Pelanggan::create([
            'user_id'           => $user->id,
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'daerah'            => $request->daerah,
            'no_hp'             => $request->no_hp,
            'paket_id'          => $request->paket_id,
            'status'            => 'aktif',
            'status_pemasangan' => 'terpasang',
            'bayar_awal'        => $request->bayar_awal,
            'tanggal_aktif'     => now()
        ]);

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pakets = Paket::where('status', 'aktif')->get();

        return view('admin.pelanggan.edit', compact('pelanggan', 'pakets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required',
            'alamat' => 'required',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        Pelanggan::findOrFail($id)->update($request->only([
            'nama', 'alamat', 'no_hp', 'paket_id', 'status'
        ]));

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil diubah');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if ($pelanggan->user) {
            $pelanggan->user->delete();
        }

        $pelanggan->delete();

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan dan akun login berhasil dihapus');
    }
    
    public function setTerpasang($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update([
            'status_pemasangan' => 'terpasang',
            'tanggal_aktif'     => now(),
        ]);

        return redirect()
            ->route('admin.pelanggan.index')
            ->with('success', 'Status pemasangan berhasil diaktifkan');
    }
}
