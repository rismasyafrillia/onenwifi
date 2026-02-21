<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK START ===');
        Log::info('Request Payload:', $request->all());

        $serverKey = config('midtrans.server_key');

        $signatureKey = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        Log::info('Generated Signature: ' . $signatureKey);
        Log::info('Midtrans Signature: ' . $request->signature_key);

        if ($signatureKey !== $request->signature_key) {
            Log::warning('Invalid signature for Order ID: ' . $request->order_id);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = Pembayaran::where('order_id', $request->order_id)->first();

        if (!$pembayaran) {
            Log::error('Order not found: ' . $request->order_id);
            return response()->json(['message' => 'Order not found'], 404);
        }

        Log::info('Order Found: ' . $pembayaran->order_id);

        // STATUS TRANSAKSI MIDTRANS
        if (
            $request->transaction_status == 'settlement' ||
            $request->transaction_status == 'capture'
        ) {

            Log::info('Payment SUCCESS for Order ID: ' . $request->order_id);

            $pembayaran->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            $pembayaran->tagihan->update([
                'status' => 'lunas',
            ]);

            Log::info('Tagihan updated to LUNAS');
        }
        elseif ($request->transaction_status == 'pending') {

            Log::info('Payment PENDING for Order ID: ' . $request->order_id);

            $pembayaran->update([
                'status' => 'pending',
            ]);
        }
        else {

            Log::warning('Payment FAILED for Order ID: ' . $request->order_id);
            Log::warning('Transaction Status: ' . $request->transaction_status);

            $pembayaran->update([
                'status' => 'failed',
            ]);
        }

        Log::info('=== MIDTRANS CALLBACK END ===');

        return response()->json(['message' => 'Callback handled']);
    }
}
