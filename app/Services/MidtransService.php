<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class MidtransService
{
    /**
     * Get Midtrans Server Key from config or database settings
     */
    public static function getServerKey(): ?string
    {
        return config('services.midtrans.server_key')
            ?: Setting::where('key', 'midtrans_server_key')->value('value');
    }

    /**
     * Check if Midtrans is in production mode
     */
    public static function isProduction(): bool
    {
        $dbVal = Setting::where('key', 'midtrans_is_production')->value('value');
        if ($dbVal !== null) {
            return $dbVal === '1' || $dbVal === 'true';
        }
        return (bool) config('services.midtrans.is_production', false);
    }

    /**
     * Get Client Key from config or database settings
     */
    public static function getClientKey(): ?string
    {
        return config('services.midtrans.client_key')
            ?: Setting::where('key', 'midtrans_client_key')->value('value');
    }

    /**
     * Get Midtrans Snap Token
     */
    public static function createSnapToken(array $params): ?string
    {
        $serverKey = self::getServerKey();
        $isProduction = self::isProduction();

        $url = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        if (empty($serverKey)) {
            Log::warning('MidtransService: Server key is not set in config or settings.');
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

            Log::error('Midtrans Snap Error: ' . $response->body(), ['params' => $params]);
            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify Midtrans Signature Key for Webhook / Notification Callback
     */
    public static function verifySignature(string $orderId, string $statusCode, string $grossAmount, ?string $serverKey, string $inputSignature): bool
    {
        $serverKey = $serverKey ?: self::getServerKey();
        if (empty($serverKey)) {
            return false;
        }

        $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($computedSignature, $inputSignature);
    }
}