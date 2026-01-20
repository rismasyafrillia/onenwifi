<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        Tagihan::expired()->update([
            'status' => 'menunggak'
        ]);

        $tagihan = Tagihan::with('pelanggan.paket')
            ->latest()
            ->get();

        return view('admin.tagihan.index', compact('tagihan'));
    }

    public function generateBulanan()
    {
        $now = Carbon::now();

        // Format periode yang konsisten untuk DB
        $periode = $now->format('m-Y'); // Contoh: '01-2026'

        // Jatuh tempo 9 hari setelah awal bulan
        $jatuhTempo = $now->copy()->startOfMonth()->addDays(19);

        // Ambil semua pelanggan aktif yang punya paket
        $pelanggans = Pelanggan::where('status', 'aktif')
            ->whereNotNull('paket_id')
            ->with('paket')
            ->get();

        foreach ($pelanggans as $p) {

            // Pastikan paket ada dan harga valid
            $hargaPaket = $p->paket->harga ?? 0;

            // Hitung tunggakan dari tagihan sebelumnya yang belum lunas
            $tunggakan = Tagihan::where('pelanggan_id', $p->id)
            ->whereIn('status', ['belum bayar', 'menunggak'])
            ->where('jatuh_tempo', '<', Carbon::today())
            ->sum('nominal');

            // Update status tagihan lama yang lewat jatuh tempo
            Tagihan::where('pelanggan_id', $p->id)
            ->where('status', 'belum bayar')
            ->where('jatuh_tempo', '<', Carbon::today())
            ->update(['status' => 'menunggak']);

            // Skip jika tagihan bulan ini sudah ada
            if (Tagihan::where('pelanggan_id', $p->id)
                ->where('periode', $periode)
                ->exists()
            ) {
                continue;
            }

            // Buat tagihan baru
            Tagihan::create([
                'pelanggan_id' => $p->id,
                'periode'      => $periode,
                'nominal'      => $hargaPaket + $tunggakan,
                'jatuh_tempo'  => $jatuhTempo,
                'status'       => 'belum bayar',
            ]);
        }

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Tagihan bulanan berhasil digenerate!');
    }

    public function edit($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return redirect()->route('admin.tagihan.index')
                ->with('error','Tagihan lunas tidak dapat diubah');
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

        Tagihan::findOrFail($id)->update($request->all());

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success','Tagihan berhasil diperbarui');
    }
}
