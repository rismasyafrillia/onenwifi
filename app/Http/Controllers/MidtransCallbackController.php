<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = config('midtrans.server_key');

        $signatureKey = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = Pembayaran::where('order_id', $request->order_id)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // STATUS TRANSAKSI MIDTRANS
        if (
            $request->transaction_status == 'settlement' ||
            $request->transaction_status == 'capture'
        ) {
            $pembayaran->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            // UPDATE TAGIHAN JADI LUNAS
            $pembayaran->tagihan->update([
                'status' => 'lunas',
            ]);
        }
        elseif ($request->transaction_status == 'pending') {
            $pembayaran->update([
                'status' => 'pending',
            ]);
        }
        else {
            $pembayaran->update([
                'status' => 'failed',
            ]);
        }

        return response()->json(['message' => 'Callback handled']);
    }
}
