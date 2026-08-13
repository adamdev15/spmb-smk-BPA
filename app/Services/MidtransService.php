<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    /**
     * Get Midtrans Snap Token
     */
    public static function createSnapToken(array $params): ?string
    {
        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production');

        $url = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        if (empty($serverKey)) {
            Log::warning('MidtransService: Server key is not set.');
            return null;
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->post($url, $params);

            if ($response->successful()) {
                $body = $response->json();
                return $body['token'] ?? null;
            }

            Log::error('Midtrans Snap Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify Midtrans Signature Key for Webhook / Notification Callback
     */
    public static function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $serverKey, string $inputSignature): bool
    {
        $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($computedSignature, $inputSignature);
    }
}
