<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Carbon\Carbon;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        Tagihan::prosesOtomatis();

        $tagihan = Tagihan::with('pelanggan.paket')
            ->orderBy('jatuh_tempo', 'desc')
            ->get();

        $listPeriode = Tagihan::select('periode')
        ->distinct()
        ->orderBy('periode', 'desc')
        ->pluck('periode');

        $query = Tagihan::with('pelanggan.paket')
            ->orderBy('periode', 'desc')
            ->orderBy('jatuh_tempo', 'asc');

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        $tagihan = $query->get()->groupBy('periode');

        return view('admin.tagihan.index', compact('tagihan', 'listPeriode'));
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

public function bayarCash($id)
    {
        $tagihan = Tagihan::with('pelanggan')->findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan sudah lunas');
        }

        $periodeBayar = Carbon::createFromFormat('m-Y', $tagihan->periode)->startOfMonth();

        // ambil semua bulan <= bulan ini
        $tagihanDibayar = Tagihan::where('pelanggan_id', $tagihan->pelanggan_id)
            ->whereIn('status', ['belum bayar', 'menunggak'])
            ->get()
            ->filter(function ($t) use ($periodeBayar) {
                return Carbon::createFromFormat('m-Y', $t->periode)
                    ->startOfMonth()
                    ->lte($periodeBayar);
            });

        if ($tagihanDibayar->isEmpty()) {
            return back()->with('error', 'Tidak ada tagihan');
        }

        foreach ($tagihanDibayar as $t) {
            Pembayaran::create([
                'user_id'      => $tagihan->pelanggan->user_id,
                'pelanggan_id' => $t->pelanggan_id,
                'tagihan_id'   => $t->id,
                'order_id'     => 'CASH-' . $t->id . '-' . time(),
                'nominal'      => $t->nominal,
                'metode'       => 'cash',
                'status'       => 'success',
                'paid_at'      => now(),
            ]);

            $t->update(['status' => 'lunas']);
        }

        return back()->with(
            'success',
            'Semua tagihan sampai periode ' . $tagihan->periode . ' berhasil dilunasi'
        );
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
