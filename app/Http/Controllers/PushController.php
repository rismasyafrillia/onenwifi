<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushController extends Controller
{
    // Kirim public key ke JS
    public function vapidPublicKey()
    {
        return env('VAPID_PUBLIC_KEY');
    }

    // Simpan subscription dari browser
    public function subscribe(Request $request)
    {
        file_put_contents(
            storage_path('app/subscription.json'),
            json_encode($request->all())
        );

        return response()->json(['success' => true]);
    }

    // Tes kirim notifikasi
    public function send()
    {
        $subscriptionData = json_decode(
            file_get_contents(storage_path('app/subscription.json')),
            true
        );

        $subscription = Subscription::create($subscriptionData);

        $auth = [
            'VAPID' => [
                'subject' => env('VAPID_SUBJECT'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        $webPush->sendOneNotification(
            $subscription,
            json_encode([
                'title' => 'OneN WiFi',
                'body'  => 'Tagihan baru tersedia'
            ])
        );

        return "Notifikasi terkirim";
    }
}
