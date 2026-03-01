<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK START ===');
        Log::info('Request Payload:', $request->all());

        $serverKey = config('services.midtrans.server_key');

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

        $pembayaran = Pembayaran::with('tagihan.pelanggan')
            ->where('order_id', $request->order_id)
            ->first();

        if (!$pembayaran) {
            Log::error('Order not found: ' . $request->order_id);
            return response()->json(['message' => 'Order not found'], 404);
        }

        Log::info('Order Found: ' . $pembayaran->order_id);

        $transactionStatus = $request->transaction_status;

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {

            Log::info('Payment SUCCESS for Order ID: ' . $request->order_id);

            if ($pembayaran->status === 'success') {
                Log::warning('ALREADY SUCCESS - SKIP WA');
                Log::info('=== MIDTRANS CALLBACK END ===');
                return response()->json(['message' => 'Already processed']);
            }

            $pembayaran->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            $tagihan = $pembayaran->tagihan;

            if ($tagihan) {
                $tagihan->update([
                    'status' => 'lunas',
                ]);

                Log::info('Tagihan updated to LUNAS');
            }

            $pelanggan = optional($tagihan)->pelanggan;

            Log::info('DEBUG WA DATA', [
                'pelanggan_id' => optional($pelanggan)->id,
                'no_hp' => optional($pelanggan)->no_hp
            ]);

            if ($pelanggan && $pelanggan->no_hp) {

                // Normalisasi nomor
                $phone = preg_replace('/[^0-9]/', '', $pelanggan->no_hp);
                $phone = preg_replace('/^0/', '62', $phone);

                $message = "Pembayaran berhasil

Tagihan bulan {$tagihan->periode}
sebesar Rp " . number_format($tagihan->nominal, 0, ',', '.') . "
telah diterima.

Terima kasih 🙏";

                $response = WhatsAppService::send($phone, $message);

                if (isset($response['status']) && $response['status'] == true) {

                    Log::info('WA SENT SUCCESS', [
                        'phone' => $phone,
                        'response' => $response
                    ]);

                } else {

                    Log::error('WA FAILED', [
                        'phone' => $phone,
                        'response' => $response
                    ]);
                }

            } else {
                Log::warning('WA NOT SENT - NO PHONE NUMBER');
            }
        }

        elseif ($transactionStatus == 'pending') {

            Log::info('Payment PENDING for Order ID: ' . $request->order_id);

            $pembayaran->update([
                'status' => 'pending',
            ]);
        }

        else {

            Log::warning('Payment FAILED for Order ID: ' . $request->order_id);
            Log::warning('Transaction Status: ' . $transactionStatus);

            $pembayaran->update([
                'status' => 'failed',
            ]);
        }

        Log::info('=== MIDTRANS CALLBACK END ===');

        return response()->json(['message' => 'Callback handled']);
    }
}