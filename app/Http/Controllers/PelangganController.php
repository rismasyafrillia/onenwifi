<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Paket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $pakets = Paket::where('status', 'aktif')->get();
        return view('pelanggan.create', compact('pakets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'alamat'   => 'required',
            'paket_id' => 'required|exists:pakets,id'
        ]);

        // 🔐 Username dibuat oleh sistem
        $username = strtolower(str_replace(' ', '', $request->nama)) . rand(100,999);

        // 🔐 Buat akun user (password default)
        $user = User::create([
            'name'     => $request->nama,
            'email'    => $username . '@onenwifi.test',
            'password' => Hash::make('12345678'),
        ]);

        // 🧾 Simpan data pelanggan
        Pelanggan::create([
            'user_id'  => $user->id,
            'nama'     => $request->nama,
            'alamat'   => $request->alamat,
            'no_hp'    => $request->no_hp,
            'paket_id' => $request->paket_id,
            'status'   => 'aktif'
        ]);

        return redirect()->route('pelanggan.index')
            ->with('success', "Pelanggan berhasil ditambahkan. Username: $username");
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pakets = Paket::where('status', 'aktif')->get();

        return view('pelanggan.edit', compact('pelanggan', 'pakets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:100',
            'alamat' => 'required',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());

        return redirect('/pelanggan')->with('success', 'Pelanggan berhasil diubah');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // hapus akun user terlebih dahulu
        if ($pelanggan->user) {
            $pelanggan->user->delete();
        }

        // hapus data pelanggan
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan dan akun login berhasil dihapus');
    }
}
