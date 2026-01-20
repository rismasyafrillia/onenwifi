<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Ambil ID tagihan dari order_id
        // Format: TAGIHAN-{id}-{timestamp}
        $orderId = $request->order_id;
        $parts = explode('-', $orderId);
        $tagihanId = $parts[1] ?? null;

        if (!$tagihanId) {
            return response()->json(['error' => 'Invalid order id'], 400);
        }

        if ($request->transaction_status === 'settlement') {
            Tagihan::where('id', $tagihanId)
                ->update(['status' => 'lunas']);
        }

        return response()->json(['status' => 'ok']);
    }
}
