<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function send($number, $message)
    {
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::error('FONNTE TOKEN NOT FOUND');
            return ['status' => false, 'reason' => 'Token missing'];
        }

        try {

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $number,
                'message' => $message,
            ]);

            $result = $response->json();

            Log::info('WA RESPONSE', [
                'number' => $number,
                'http_status' => $response->status(),
                'response' => $result
            ]);

            return $result;

        } catch (\Exception $e) {

            Log::error('WA ERROR: ' . $e->getMessage());

            return [
                'status' => false,
                'reason' => $e->getMessage()
            ];
        }
    }
}