<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use App\Services\WhatsAppService;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        Tagihan::updateStatusMenunggak();

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
        $periode = now()->format('m-Y');

        $tagihans = Tagihan::generateBulanan($periode);

        if (!$tagihans) {
            $tagihans = collect();
        }

        foreach ($tagihans as $tagihan) {

            $pelanggan = $tagihan->pelanggan;

            if ($pelanggan && $pelanggan->no_hp) {

                $message = "Halo {$pelanggan->nama},

    Tagihan bulan {$periode} sebesar Rp "
                    . number_format($tagihan->nominal, 0, ',', '.') . " sudah tersedia.

    Silakan lakukan pembayaran sebelum jatuh tempo.

    Terima kasih.";

                WhatsAppService::send($pelanggan->no_hp, $message);
            }
        }

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Tagihan bulan ' . $periode . ' berhasil digenerate');
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

        $periodeBayar = Carbon::createFromFormat('m-Y', $tagihan->periode)
            ->startOfMonth();

        // Ambil semua tagihan pelanggan yang belum lunas
        $tagihanDibayar = Tagihan::where('pelanggan_id', $tagihan->pelanggan_id)
            ->whereIn('status', ['belum bayar', 'menunggak'])
            ->get()
            ->filter(function ($t) use ($periodeBayar) {
                return Carbon::createFromFormat('m-Y', $t->periode)
                    ->startOfMonth()
                    ->lte($periodeBayar);
            });

        if ($tagihanDibayar->isEmpty()) {
            return back()->with('error', 'Tidak ada tagihan yang bisa dibayar');
        }

        $pelanggan = $tagihan->pelanggan;

        foreach ($tagihanDibayar as $t) {

            Pembayaran::create([
                'user_id'      => $pelanggan->user_id,
                'pelanggan_id' => $t->pelanggan_id,
                'tagihan_id'   => $t->id,
                'order_id'     => 'CASH-' . $t->id . '-' . time(),
                'nominal'      => $t->nominal,
                'metode'       => 'cash',
                'status'       => 'success',
                'paid_at'      => now(),
            ]);

            $t->update([
                'status' => 'lunas'
            ]);

            if ($pelanggan && $pelanggan->no_hp) {

                $message = "Pembayaran berhasil 

    Tagihan bulan {$t->periode} sebesar Rp "
                    . number_format($t->nominal, 0, ',', '.')
                    . " telah diterima melalui pembayaran CASH.

    Terima kasih telah melakukan pembayaran tepat waktu 🙏";

                WhatsAppService::send($pelanggan->no_hp, $message);
            }
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
