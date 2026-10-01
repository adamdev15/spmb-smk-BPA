<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle Meta Webhook verification (GET request).
     * Meta akan mengirim GET request saat pertama kali setup webhook.
     */
    public function verify(Request $request)
    {
        $verifyToken = config('services.bablast.webhook_verify_token');

        $mode      = $request->query('hub_mode');
        $token     = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('WhatsApp Webhook verified successfully.');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('WhatsApp Webhook verification failed.', [
            'mode'  => $mode,
            'token' => $token,
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming webhook events from Meta (POST request).
     * Digunakan untuk menerima status pesan, balasan, dsb.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Gunakan json_encode agar array yang dalam (nested) tidak terpotong saat di-log
        Log::info('WhatsApp Webhook received: ' . json_encode($payload));

        // Proses hanya event dari WhatsApp
        if (($payload['object'] ?? '') !== 'whatsapp_business_account') {
            return response()->json(['status' => 'ignored'], 200);
        }

        foreach ($payload['entry'] ?? [] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                $value = $change['value'] ?? [];

                // --- Status Update (delivered, read, failed, etc.) ---
                foreach ($value['statuses'] ?? [] as $status) {
                    $messageId  = $status['id']       ?? '-';
                    $msgStatus  = $status['status']   ?? '-';
                    $recipient  = $status['recipient_id'] ?? '-';
                    $timestamp  = $status['timestamp'] ?? '-';

                    Log::info("WA Message Status: [{$msgStatus}] to {$recipient} | msg_id: {$messageId}");

                    // Optional: simpan log ke DB atau update status kirim notif
                }

                // --- Incoming Messages (jika ada yang balas) ---
                foreach ($value['messages'] ?? [] as $message) {
                    $from = $message['from'] ?? '-';
                    $type = $message['type'] ?? 'unknown';
                    $text = $message['text']['body'] ?? null;

                    Log::info("WA Incoming message from {$from} [{$type}]: " . ($text ?? '[non-text]'));

                    // Optional: bisa tambah auto-reply atau trigger aksi lain di sini
                }
            }
        }

        // Meta mengharuskan response 200 segera
        return response()->json(['status' => 'ok'], 200);
    }
}
