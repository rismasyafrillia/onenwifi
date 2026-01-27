<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Pembayaran;

class TagihanUserController extends Controller
{
    // LIST TAGIHAN USER
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

    // DETAIL TAGIHAN
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

    // BAYAR (MIDTRANS QRIS)
    public function bayar($id)
    {
        $user = auth()->user();

        $tagihan = Tagihan::where('id', $id)
            ->whereHas('pelanggan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = false;
        Config::$isSanitized = true;

        $orderId = 'TAGIHAN-' . $tagihan->id . '-' . time();

        Pembayaran::create([
            'user_id'    => $user->id,
            'tagihan_id' => $tagihan->id,
            'order_id'   => $orderId,
            'nominal'    => $tagihan->nominal,
            'metode'     => 'midtrans',
            'status'     => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $tagihan->nominal,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'enabled_payments' => ['qris'],
        ];

        // $snapToken = Snap::getSnapToken($params);
        $snapToken = 'DUMMY-SNAP-TOKEN';

        return view('user.tagihan.bayar', compact('snapToken', 'tagihan'));
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