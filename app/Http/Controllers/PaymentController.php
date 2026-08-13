<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Casis;
use App\Models\Pembayaran;
use App\Services\MidtransService;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Create Midtrans Payment Transaction for Re-enrollment
     */
    public function createPayment(Request $request)
    {
        $casisId = session('casis_id');
        if (!$casisId) {
            return response()->json(['success' => false, 'message' => 'Sesi login telah berakhir.'], 401);
        }

        $casis = Casis::with('jurusan')->findOrFail($casisId);

        if ($casis->status_kelulusan !== 'Lulus') {
            return response()->json(['success' => false, 'message' => 'Pembayaran daftar ulang hanya untuk siswa yang LULUS.'], 400);
        }

        if ($casis->status_daftar_ulang === 'Sudah') {
            return response()->json(['success' => false, 'message' => 'Anda sudah menyelesaikan daftar ulang.'], 400);
        }

        // Check for existing pending payment
        $existingPayment = Pembayaran::where('casis_id', $casis->id)
            ->where('transaction_status', 'pending')
            ->first();
            
        if ($existingPayment && $existingPayment->snap_token) {
            return response()->json([
                'success' => true,
                'snap_token' => $existingPayment->snap_token,
                'order_id' => $existingPayment->order_id
            ]);
        }

        // Determine nominal from Jurusan or global settings
        $nominal = ($casis->jurusan && $casis->jurusan->biaya_daftar_ulang > 0)
            ? $casis->jurusan->biaya_daftar_ulang
            : (double)(\App\Models\Setting::where('key', 'biaya_daftar_ulang_global')->value('value') ?: 1500000);

        $orderId = 'PAY-BPA-' . $casis->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$nominal,
            ],
            'customer_details' => [
                'first_name' => $casis->nama_lengkap,
                'email' => $casis->nisn . '@smkbhaktiprajaadiwerna.sch.id',
                'phone' => $casis->no_hp_siswa,
            ],
            'item_details' => [
                [
                    'id' => 'DU-' . ($casis->jurusan ? $casis->jurusan->kode : 'GENERAL'),
                    'price' => (int)$nominal,
                    'quantity' => 1,
                    'name' => 'Biaya Daftar Ulang SPMB (' . ($casis->jurusan ? $casis->jurusan->kode : 'SMK BPA') . ')',
                ]
            ]
        ];

        $snapToken = MidtransService::createSnapToken($params);

        if (!$snapToken) {
            return response()->json(['success' => false, 'message' => 'Gagal menghubungkan ke Gateway Midtrans.'], 500);
        }

        $pembayaran = Pembayaran::create([
            'casis_id' => $casis->id,
            'order_id' => $orderId,
            'tipe_pembayaran' => 'online',
            'nominal' => $nominal,
            'snap_token' => $snapToken,
            'transaction_status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'order_id' => $orderId
        ]);
    }

    /**
     * Webhook Notification Callback from Midtrans Server
     */
    public function notificationCallback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');
        $paymentType = $request->input('payment_type');

        if (!MidtransService::verifySignature($orderId, $statusCode, $grossAmount, $serverKey, $signatureKey)) {
            Log::warning("Midtrans Webhook: Invalid Signature Key for Order ID $orderId");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();
        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $pembayaran->payment_type = $paymentType;

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $pembayaran->transaction_status = 'settlement';
            $pembayaran->settlement_time = Carbon::now();
            $pembayaran->save();

            // Update candidate re-enrollment status and decrease quota
            $casis = $pembayaran->casis;
            if ($casis) {
                $casis->status_daftar_ulang = 'Sudah';
                $casis->tgl_daftar_ulang = Carbon::now();
                $casis->save();

                // Send WA Notification
                \App\Services\WhatsAppService::sendPaymentSuccess($casis, $pembayaran);

                // Send DB Notification to Admin/Petugas
                $admins = \App\Models\User::whereIn('role', ['admin', 'petugas'])->get();
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PaymentSuccessNotification($pembayaran, $casis));
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pembayaran->transaction_status = 'failed';
            $pembayaran->save();
        } else {
            $pembayaran->transaction_status = 'pending';
            $pembayaran->save();
        }

        return response()->json(['status' => 'ok']);
    }
}
