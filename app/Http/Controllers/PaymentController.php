<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Casis;
use App\Services\PembayaranService;

class PaymentController extends Controller
{
    /**
     * Create or retrieve Snap Token for student online checkout
     */
    public function createPayment(Request $request)
    {
        $casisId = session('casis_id');
        if (!$casisId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login telah berakhir. Silakan login kembali.'
            ], 401);
        }

        $casis = Casis::with(['jurusan', 'pembayaranTerakhir'])->find($casisId);
        if (!$casis) {
            return response()->json([
                'success' => false,
                'message' => 'Data calon siswa tidak ditemukan.'
            ], 404);
        }

        $result = PembayaranService::getOrCreateSnapToken($casis);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'snap_token' => $result['snap_token'],
            'order_id' => $result['order_id'],
            'nominal' => $result['nominal'],
        ]);
    }

    /**
     * Midtrans Webhook Notification Callback
     */
    public function notification(Request $request)
    {
        $payload = $request->all();
        $result = PembayaranService::handleWebhookNotification($payload);

        return response()->json($result);
    }
}