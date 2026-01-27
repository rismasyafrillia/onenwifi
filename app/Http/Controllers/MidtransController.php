<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $notif = new \Midtrans\Notification();

        $orderId = $notif->order_id;
        $status  = $notif->transaction_status;

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) return response()->json(['msg' => 'not found']);

        if ($status == 'settlement') {

            DB::transaction(function () use ($pembayaran) {

                $pembayaran->update([
                    'status' => 'success',
                    'paid_at' => now()
                ]);

                $pembayaran->tagihan->update([
                    'status' => 'lunas'
                ]);
            });
        }

        return response()->json(['success' => true]);
    }
}