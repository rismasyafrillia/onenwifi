<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Carbon\Carbon;

class TagihanUserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Silakan login sebagai user');
        }

        $tagihans = Tagihan::whereHas('pelanggan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('periode', 'desc')
            ->get();

        return view('user.tagihan.index', compact('tagihans'));
    }

    public function show($id)
    {
        $user = auth()->user();

        $tagihan = Tagihan::where('id', $id)
            ->whereHas('pelanggan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        return view('user.tagihan.show', compact('tagihan'));
    }

    public function bayar($id)
    {
        $user = auth()->user();

        $tagihan = Tagihan::where('id', $id)
            ->whereHas('pelanggan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan sudah lunas');
        }

        $periodeTagihan = Carbon::createFromFormat('m-Y', $tagihan->periode);
        $bulanIni = Carbon::now()->startOfMonth();

        if ($periodeTagihan->lt($bulanIni)) {
            return back()->with(
                'error',
                'Tagihan bulan sebelumnya tidak dapat dibayar. Silakan hubungi admin.'
            );
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'ORDER-' . $tagihan->id . '-' . time();

        Pembayaran::create([
            'user_id'      => $user->id,
            'pelanggan_id' => $tagihan->pelanggan_id,
            'tagihan_id'   => $tagihan->id,
            'order_id'     => $orderId,
            'nominal'      => $tagihan->nominal,
            'metode'       => 'qris',
            'status'       => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $tagihan->nominal,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('user.tagihan.show', compact('tagihan', 'snapToken'));
    }

    public function riwayat()
    {
        $pembayarans = Pembayaran::with('tagihan')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.pembayaran.riwayat', compact('pembayarans'));
    }
}