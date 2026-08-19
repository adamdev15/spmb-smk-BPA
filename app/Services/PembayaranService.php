<?php

namespace App\Services;

use App\Models\Casis;
use App\Models\Pembayaran;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\PaymentSuccessNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PembayaranService
{
    /**
     * Get or create a re-enrollment billing (tagihan daftar ulang) for candidate
     */
    public static function createOrGetTagihanDaftarUlang(Casis $casis, bool $sendNotification = true): Pembayaran
    {
        // Check for existing re-enrollment payment
        $existing = Pembayaran::where('casis_id', $casis->id)
            ->where('jenis_pembayaran', 'daftar_ulang')
            ->latest()
            ->first();

        // 1. If already settlement, return it
        if ($existing && $existing->isSettlement()) {
            return $existing;
        }

        // 2. Determine correct nominal
        $nominal = 1500000;
        if ($casis->jurusan) {
            // Check from new Master Data Biaya (sum of all with Jenis "Daftar Ulang" and "SPP")
            $biayaMaster = $casis->jurusan->biayas()->whereIn('jenis_biaya', ['Daftar Ulang', 'SPP'])->sum('nominal');
            
            if ($biayaMaster > 0) {
                $nominal = (float) $biayaMaster;
            } else if ($casis->jurusan->biaya_daftar_ulang > 0) {
                // Fallback to legacy static column
                $nominal = (float) $casis->jurusan->biaya_daftar_ulang;
            } else {
                $settingNominal = Setting::where('key', 'biaya_daftar_ulang_global')->value('value');
                if ($settingNominal && is_numeric($settingNominal)) {
                    $nominal = (float) $settingNominal;
                }
            }
        }

        // 3. Determine due date
        $dueDate = null;
        $settingJadwal = Setting::where('key', 'jadwal_daftar_ulang')->value('value');
        if ($settingJadwal) {
            try {
                $dueDate = Carbon::parse($settingJadwal)->format('Y-m-d');
            } catch (\Exception $e) {
                $dueDate = null;
            }
        }
        if (!$dueDate) {
            $days = (int) (Setting::where('key', 'jadwal_daftar_ulang_jatuh_tempo_hari')->value('value') ?: 7);
            $dueDate = Carbon::now()->addDays($days)->format('Y-m-d');
        }

        // 4. If pending payment exists, ensure nominal & due date are fresh
        if ($existing && $existing->isPending()) {
            $needsSave = false;
            if ((float) $existing->nominal !== (float) $nominal) {
                $existing->nominal = $nominal;
                $needsSave = true;
            }
            if (!$existing->tgl_jatuh_tempo && $dueDate) {
                $existing->tgl_jatuh_tempo = $dueDate;
                $needsSave = true;
            }
            if ($needsSave) {
                $existing->save();
            }
            return $existing;
        }

        // 5. If expired or failed payment exists, reuse the record with fresh order_id and pending status
        if ($existing && $existing->isExpired()) {
            $existing->update([
                'order_id' => 'PAY-BPA-' . $casis->id . '-' . time(),
                'nominal' => $nominal,
                'tgl_jatuh_tempo' => $dueDate,
                'transaction_status' => 'pending',
                'snap_token' => null,
            ]);

            if ($sendNotification && $casis->no_hp_siswa) {
                WhatsAppService::sendTagihanDaftarUlang($casis, $existing);
            }

            return $existing;
        }

        // 6. Create brand new payment record
        $orderId = 'PAY-BPA-' . $casis->id . '-' . time();
        $pembayaran = Pembayaran::create([
            'casis_id' => $casis->id,
            'order_id' => $orderId,
            'jenis_pembayaran' => 'daftar_ulang',
            'tipe_pembayaran' => 'online',
            'nominal' => $nominal,
            'tgl_jatuh_tempo' => $dueDate,
            'transaction_status' => 'pending',
        ]);

        if ($sendNotification && $casis->no_hp_siswa) {
            WhatsAppService::sendTagihanDaftarUlang($casis, $pembayaran);
        }

        return $pembayaran;
    }

    /**
     * Process Manual / Offline Payment by Admin
     */
    public static function processManualPayment(Casis $casis, array $data, $adminUser = null): Pembayaran
    {
        $pembayaran = self::createOrGetTagihanDaftarUlang($casis, false);

        $nominal = isset($data['nominal']) && is_numeric($data['nominal'])
            ? (float) $data['nominal']
            : $pembayaran->nominal;

        $paymentType = $data['payment_type'] ?? 'Tunai';
        $paidAt = isset($data['paid_at']) && !empty($data['paid_at'])
            ? Carbon::parse($data['paid_at'])
            : Carbon::now();

        $status = $data['transaction_status'] ?? 'settlement';
        $wasAlreadySettlement = $pembayaran->isSettlement();

        $pembayaran->update([
            'tipe_pembayaran' => 'offline',
            'payment_type' => $paymentType,
            'nominal' => $nominal,
            'transaction_status' => $status,
            'settlement_time' => $status === 'settlement' ? $paidAt : null,
            'user_id' => $adminUser ? $adminUser->id : null,
            'transaction_id' => $data['nomor_referensi'] ?? null,
            'catatan_admin' => $data['catatan_admin'] ?? 'Pembayaran Manual / Kasir Sekolah',
        ]);

        if ($status === 'settlement') {
            $casis->status_daftar_ulang = 'Sudah';
            $casis->tgl_daftar_ulang = $paidAt;
            $casis->save();

            // Send notification only if not previously settlement
            if (!$wasAlreadySettlement) {
                if ($casis->no_hp_siswa) {
                    WhatsAppService::sendPaymentSuccess($casis, $pembayaran);
                }
                WhatsAppService::sendPaymentNotificationToAdmin($casis, $pembayaran);

                // Send in-app notification to Admin & Petugas
                try {
                    $admins = User::whereIn('role', ['admin', 'petugas'])->get();
                    Notification::send($admins, new PaymentSuccessNotification($pembayaran, $casis));
                } catch (\Exception $e) {
                    Log::error('In-app notification error: ' . $e->getMessage());
                }
            }
        }

        return $pembayaran;
    }

    /**
     * Generate or reuse Snap Token for online checkout
     */
    public static function getOrCreateSnapToken(Casis $casis): array
    {
        $pembayaran = self::createOrGetTagihanDaftarUlang($casis, false);

        if ($pembayaran->isSettlement()) {
            return [
                'success' => false,
                'message' => 'Pembayaran daftar ulang Anda sudah lunas.',
                'pembayaran' => $pembayaran,
            ];
        }

        // If transaction was expired or failed, renew order_id
        if ($pembayaran->isExpired()) {
            $pembayaran->order_id = 'PAY-BPA-' . $casis->id . '-' . time();
            $pembayaran->transaction_status = 'pending';
            $pembayaran->snap_token = null;
            $pembayaran->save();
        }

        // Check if existing snap token is valid or generate a fresh one
        $jurusanNama = $casis->jurusan ? $casis->jurusan->nama : 'SPMB';
        $grossAmount = (int) $pembayaran->nominal;

        $params = [
            'transaction_details' => [
                'order_id' => $pembayaran->order_id,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $casis->nama_lengkap,
                'phone' => $casis->no_hp_siswa ?: '08123456789',
                'email' => $casis->nisn . '@spmb.sch.id',
            ],
            'item_details' => [
                [
                    'id' => 'DU-' . $casis->id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => substr('Daftar Ulang - ' . $jurusanNama, 0, 50),
                ]
            ],
        ];

        $snapToken = MidtransService::createSnapToken($params);

        // If snap token generation failed due to duplicate order_id on Midtrans, renew order_id and retry once
        if (!$snapToken) {
            $pembayaran->order_id = 'PAY-BPA-' . $casis->id . '-' . time();
            $pembayaran->save();
            $params['transaction_details']['order_id'] = $pembayaran->order_id;
            $snapToken = MidtransService::createSnapToken($params);
        }

        if ($snapToken) {
            $pembayaran->snap_token = $snapToken;
            $pembayaran->save();

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $pembayaran->order_id,
                'nominal' => $pembayaran->nominal,
                'pembayaran' => $pembayaran,
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal menghubungkan ke Midtrans Payment Gateway. Pastikan server key Midtrans sudah benar.',
            'pembayaran' => $pembayaran,
        ];
    }

    /**
     * Handle Midtrans Webhook Callback
     */
    public static function handleWebhookNotification(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if (!$orderId) {
            return ['status' => 'error', 'message' => 'Order ID missing'];
        }

        $serverKey = config('services.midtrans.server_key')
            ?: Setting::where('key', 'midtrans_server_key')->value('value');

        if ($serverKey && $signatureKey) {
            $valid = MidtransService::verifySignature($orderId, (string)$statusCode, (string)$grossAmount, $serverKey, $signatureKey);
            if (!$valid) {
                Log::warning('Midtrans Webhook: Invalid Signature', ['order_id' => $orderId]);
                return ['status' => 'invalid_signature'];
            }
        }

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();
        if (!$pembayaran) {
            Log::warning('Midtrans Webhook: Pembayaran not found', ['order_id' => $orderId]);
            return ['status' => 'not_found'];
        }

        // Idempotency: If already settlement, do not process duplicates
        if ($pembayaran->isSettlement()) {
            Log::info('Midtrans Webhook: Pembayaran already settlement', ['order_id' => $orderId]);
            return ['status' => 'already_settlement'];
        }

        $pembayaran->payment_type = $paymentType ?: $pembayaran->payment_type;
        $pembayaran->transaction_id = $transactionId ?: $pembayaran->transaction_id;
        $pembayaran->raw_response = json_encode($payload);

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $pembayaran->transaction_status = 'settlement';
            $pembayaran->settlement_time = Carbon::now();
            $pembayaran->save();

            $casis = $pembayaran->casis;
            if ($casis) {
                $casis->status_daftar_ulang = 'Sudah';
                $casis->tgl_daftar_ulang = Carbon::now();
                $casis->save();

                // Send WhatsApp payment confirmation
                WhatsAppService::sendPaymentSuccess($casis, $pembayaran);
                WhatsAppService::sendPaymentNotificationToAdmin($casis, $pembayaran);

                // Send in-app notification to Admin & Petugas
                try {
                    $admins = User::whereIn('role', ['admin', 'petugas'])->get();
                    Notification::send($admins, new PaymentSuccessNotification($pembayaran, $casis));
                } catch (\Exception $e) {
                    Log::error('In-app notification error: ' . $e->getMessage());
                }
            }
        } elseif (in_array($transactionStatus, ['expire', 'expired'])) {
            $pembayaran->transaction_status = 'expire';
            $pembayaran->save();
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'failed'])) {
            $pembayaran->transaction_status = 'failed';
            $pembayaran->save();
        } else {
            $pembayaran->transaction_status = 'pending';
            $pembayaran->save();
        }

        return ['status' => 'ok', 'transaction_status' => $pembayaran->transaction_status];
    }
}