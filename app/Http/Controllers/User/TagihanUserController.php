<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Carbon\Carbon;
use Midtrans\Notification;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

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

        // $periodeTagihan = Carbon::createFromFormat('m-Y', $tagihan->periode);
        // $bulanIni = Carbon::now()->startOfMonth();

        // if ($periodeTagihan->lt($bulanIni)) {
        //     return back()->with(
        //         'error',
        //         'Tagihan bulan sebelumnya tidak dapat dibayar. Silakan hubungi admin.'
        //     );
        // }

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
            'status'       => 'belum bayar',
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
            'callbacks' => [
                'finish' => url('/user/riwayat'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('user.tagihan.show', compact('tagihan', 'snapToken'));

    }

    // public function notification(Request $request)
    // {
    //     Config::$serverKey = config('midtrans.server_key');
    //     Config::$isProduction = config('midtrans.is_production');

    //     $notif = new Notification();

    //     $transaction = $notif->transaction_status;
    //     $orderId = $notif->order_id;

    //     $pembayaran = Pembayaran::where('order_id', $orderId)->first();

    //     if (!$pembayaran) {
    //         return response()->json(['message' => 'Not found'], 404);
    //     }

    //     if ($transaction == 'settlement') {

    //         $pembayaran->update([
    //             'status' => 'success',
    //             'paid_at' => now(),
    //         ]);

    //         $tagihan = Tagihan::with('pelanggan')
    //             ->find($pembayaran->tagihan_id);

    //         $tagihan->update(['status' => 'lunas']);

    //         // KIRIM WA
    //         $pelanggan = $tagihan->pelanggan;

    //         if ($pelanggan && $pelanggan->no_hp) {

    //             $message = "Pembayaran berhasil

    // Tagihan bulan {$tagihan->periode} sebesar Rp "
    //                 . number_format($tagihan->nominal, 0, ',', '.')
    //                 . " telah diterima.

    // Terima kasih.";

    //             WhatsAppService::send($pelanggan->no_hp, $message);
    //         }
    //     }

    //     return response()->json(['message' => 'OK']);
    // }

    public function riwayat()
    {
        $user = auth()->user();

        $pembayarans = Pembayaran::with('tagihan')
            ->where('user_id', $user->id)
            ->where('status', 'success')
            ->latest()
            ->get();

        return view('user.pembayaran.index', compact('pembayarans'));
    }

    public function detail($id)
    {
        $pembayaran = Pembayaran::with('tagihan')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('user.pembayaran.show', compact('pembayaran'));
    }

    public function cetak($id)
{
    $pembayaran = Pembayaran::with('tagihan','user')
        ->where('user_id', auth()->id())
        ->findOrFail($id);

    $invoice = 'INV-'.date('Y').'-'.$pembayaran->id;

    $verification = hash('sha256', 
        $pembayaran->order_id.
        $pembayaran->nominal.
        $pembayaran->paid_at
    );

    $pdf = Pdf::loadView('user.pembayaran.pdf', compact(
        'pembayaran',
        'invoice',
        'verification'
    ))
    ->setPaper('A4','portrait');

    return $pdf->download('Struk-'.$pembayaran->order_id.'.pdf');
}
}