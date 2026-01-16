<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TagihanController extends Controller
{

    public function index()
    {
        $tagihan = Tagihan::with(['pelanggan.paket'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tagihan.index', compact('tagihan'));
    }

    public function generateBulanan()
    {
        $now = Carbon::now();
        $periode = $now->translatedFormat('F Y');
        $jatuhTempo = $now->startOfMonth()->addDays(9);

        $pelangganAktif = Pelanggan::where('status', 'aktif')
            ->with('paket')
            ->get();

        foreach ($pelangganAktif as $p) {

            if (!$p->paket) continue;

            /**
             * 1️⃣ HITUNG TOTAL TUNGGAKAN
             */
            $totalTunggakan = Tagihan::where('pelanggan_id', $p->id)
                ->whereIn('status', ['belum bayar', 'menunggak'])
                ->whereDate('jatuh_tempo', '<', $now)
                ->sum('nominal');

            /**
             * 2️⃣ UPDATE TAGIHAN LAMA → MENUNGGAK
             */
            Tagihan::where('pelanggan_id', $p->id)
                ->where('status', 'belum bayar')
                ->whereDate('jatuh_tempo', '<', $now)
                ->update(['status' => 'menunggak']);

            /**
             * 3️⃣ CEK TAGIHAN BULAN INI
             */
            $sudahAda = Tagihan::where('pelanggan_id', $p->id)
                ->where('periode', $periode)
                ->exists();

            if ($sudahAda) continue;

            /**
             * 4️⃣ TAGIHAN BARU = PAKET + TUNGGAKAN
             */
            $nominalBaru = $p->paket->harga + $totalTunggakan;

            Tagihan::create([
                'pelanggan_id'   => $p->id,
                'periode'        => $periode,
                'nominal'        => $nominalBaru,
                'jatuh_tempo'    => $jatuhTempo,
                'status'         => 'belum bayar',
                // opsional kalau kolom ada
                // 'total_tunggakan' => $totalTunggakan
            ]);
        }

        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan bulanan berhasil digenerate dengan akumulasi tunggakan');
    }


    public function edit($id)
    {
        $tagihan = Tagihan::with('pelanggan')->findOrFail($id);

        // Tagihan lunas tidak bisa diedit
        if ($tagihan->status === 'lunas') {
            return redirect()->route('tagihan.index')
                ->with('error', 'Tagihan yang sudah lunas tidak dapat diubah');
        }

        return view('tagihan.edit', compact('tagihan'));
    }

    public function update(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return redirect()->route('tagihan.index')
                ->with('error', 'Tagihan lunas tidak dapat diubah');
        }

        $request->validate([
            'nominal'     => 'required|numeric|min:1000',
            'jatuh_tempo' => 'required|date',
            'status'      => 'required|in:belum bayar,menunggak,lunas'
        ]);

        $tagihan->update([
            'nominal'     => $request->nominal,
            'jatuh_tempo' => $request->jatuh_tempo,
            'status'      => $request->status
        ]);

        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return redirect()->route('tagihan.index')
                ->with('error', 'Tagihan lunas tidak dapat dibatalkan');
        }

        $tagihan->update([
            'status' => 'dibatalkan'
        ]);

        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan berhasil dibatalkan');
    }
}
