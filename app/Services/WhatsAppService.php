<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Models\Casis;
use App\Models\Pembayaran;
use Carbon\Carbon;

class WhatsAppService
{
    /**
     * Send WhatsApp WABA Template Message via Bablast.id Official API
     *
     * @param string      $target       Phone number (e.g. 08123456789 or 628123456789)
     * @param string      $templateName Approved Meta template name
     * @param array       $parameters   Ordered values for {{1}}, {{2}}, ... in the template body
     * @param string      $language     Language code, default 'id'
     * @param string|null $headerImage  Public URL of image for HEADER (required if template has IMAGE header)
     * @return bool
     */
    public static function sendTemplate(string $target, string $templateName, array $parameters = [], string $language = 'id', ?string $headerImage = null): bool
    {
        $status = Setting::where('key', 'wa_status')->value('value');
        if ($status === '0' || $status === 'false') {
            Log::info('WhatsAppService: WA notification is disabled via settings. Message not sent to ' . $target);
            return false;
        }

        $apiToken = Setting::where('key', 'bablast_api_token')->value('value')
            ?: config('services.bablast.token');

        if (empty($apiToken)) {
            Log::warning('WhatsAppService: Bablast API token is empty. Message not sent.', ['target' => $target]);
            return false;
        }

        // Normalize Indonesian phone number to international format (without +)
        $target = preg_replace('/[^0-9]/', '', $target);
        if (str_starts_with($target, '0')) {
            $target = '62' . substr($target, 1);
        }

        try {
            $components = [];

            // Jika ada Header Image, format sesuai standar WhatsApp Cloud API
            if ($headerImage) {
                $components[] = [
                    'type' => 'header',
                    'parameters' => [
                        [
                            'type' => 'image',
                            'image' => [
                                'link' => $headerImage
                            ]
                        ]
                    ]
                ];
            }

            // Jika ada parameter body, format ke struktur parameters text
            if (!empty($parameters)) {
                $bodyParams = [];
                foreach ($parameters as $param) {
                    $bodyParams[] = [
                        'type' => 'text',
                        'text' => (string) $param
                    ];
                }
                $components[] = [
                    'type' => 'body',
                    'parameters' => $bodyParams
                ];
            }

            $payload = [
                'phone'         => $target,
                'template_name' => $templateName,
                'language'      => $language,
            ];

            if (!empty($components)) {
                $payload['components'] = $components;
            } else {
                // Fallback untuk template sederhana jika tidak punya header
                $payload['parameters'] = $parameters;
            }

            Log::info('WhatsAppService: Sending WABA template', [
                'template'     => $templateName,
                'target'       => $target,
                'params'       => $parameters,
                'header_image' => $headerImage ?? '(none)',
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post('https://api.bablast.id/waba/send-template', $payload);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['success']) && $responseData['success'] === true) {
                Log::info('WhatsApp (Bablast) template sent successfully', [
                    'template' => $templateName,
                    'target'   => $target,
                    'response' => $responseData,
                ]);
                return true;
            }

            Log::error('WhatsAppService (Bablast) error', [
                'template' => $templateName,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('WhatsAppService (Bablast) Exception: ' . $e->getMessage(), [
                'template' => $templateName,
                'target'   => $target,
            ]);
            return false;
        }
    }

    /**
     * Send Registration Notification to Applicant
     * Template: spmb_pendaftaran_berhasil
     * Params: {{1}}=no_pendaftaran, {{2}}=nama, {{3}}=jurusan,
     *         {{4}}=program_keunggulan, {{5}}=password, {{6}}=link_wa_group
     */
    public static function sendRegistrationSuccess($casis): bool
    {
        $jurusanNama = $casis->jurusan ? $casis->jurusan->nama : '-';
        $programNama = $casis->programKeunggulan ? $casis->programKeunggulan->nama : '-';
        $waLink      = $casis->jurusan ? ($casis->jurusan->link_wa_group ?: '-') : '-';
        $headerImage = asset('images/logo-bpa.png');

        return self::sendTemplate(
            $casis->no_hp_siswa,
            'spmb_pendaftaran_berhasil',
            [
                $casis->no_pendaftaran,
                $casis->nama_lengkap,
                $jurusanNama,
                $programNama,
                $casis->nisn,
                $waLink,
            ],
            'id',
            $headerImage
        );
    }

    /**
     * Send Reminder Notification to Applicant (berkas belum lengkap)
     * Template: spmb_pengingat_berkas
     * Params: {{1}}=nama
     */
    public static function sendReminder($casis): bool
    {
        $headerImage = asset('images/logo-bpa.png');
        
        return self::sendTemplate(
            $casis->no_hp_siswa,
            'spmb_pengingat_berkas',
            [$casis->nama_lengkap],
            'id',
            $headerImage
        );
    }

    /**
     * Send Daftar Ulang Reminder Notification to Applicant
     * Template: spmb_pengingat_daftar_ulang
     * Params: {{1}}=nama
     */
    public static function sendDaftarUlangReminder($casis): bool
    {
        $headerImage = asset('images/logo-bpa.png');
        
        return self::sendTemplate(
            $casis->no_hp_siswa,
            'spmb_pengingat_daftar_ulang',
            [$casis->nama_lengkap],
            'id',
            $headerImage
        );
    }

    /**
     * Send Automatic Re-Enrollment Invoice/Billing Notification to Applicant
     * Template: spmb_tagihan_daftar_ulang
     * Params: {{1}}=nama, {{2}}=no_pendaftaran, {{3}}=jurusan, {{4}}=program,
     *         {{5}}=nominal, {{6}}=jatuh_tempo, {{7}}=link_dashboard
     */
    public static function sendTagihanDaftarUlang(Casis $casis, Pembayaran $pembayaran): bool
    {
        $jurusanNama   = $casis->jurusan ? $casis->jurusan->nama : '-';
        $programNama   = $casis->programKeunggulan ? $casis->programKeunggulan->nama : '-';
        $nominal       = number_format($pembayaran->nominal, 0, ',', '.');
        $jatuhTempo    = $pembayaran->tgl_jatuh_tempo
            ? Carbon::parse($pembayaran->tgl_jatuh_tempo)->translatedFormat('d F Y')
            : 'Sesuai Jadwal';
        $linkDashboard = url('/login-siswa');
        $headerImage   = asset('images/logo-bpa.png');

        return self::sendTemplate(
            $casis->no_hp_siswa,
            'spmb_tagihan_daftar_ulang',
            [
                $casis->nama_lengkap,
                $casis->no_pendaftaran,
                $jurusanNama,
                $programNama,
                $nominal,
                $jatuhTempo,
                $linkDashboard,
            ],
            'en',
            $headerImage
        );
    }

    /**
     * Send Payment Success (Lunas) Notification to Applicant
     * Template: spmb_pembayaran_sukses
     * Params: {{1}}=nama, {{2}}=no_pembayaran, {{3}}=nominal, {{4}}=metode, {{5}}=tanggal_bayar
     */
    public static function sendPaymentSuccess($casis, $pembayaran): bool
    {
        $metode = $pembayaran->tipe_pembayaran === 'offline'
            ? 'Manual / Offline (' . ($pembayaran->payment_type ?: 'Kasir') . ')'
            : 'Online (' . ($pembayaran->payment_type ? str_replace('_', ' ', strtoupper($pembayaran->payment_type)) : 'Midtrans') . ')';

        $tanggalBayar = $pembayaran->settlement_time
            ? Carbon::parse($pembayaran->settlement_time)->translatedFormat('d F Y H:i')
            : Carbon::now()->translatedFormat('d F Y H:i');

        $nominal = number_format($pembayaran->nominal, 0, ',', '.');
        $headerImage = asset('images/logo-bpa.png');

        return self::sendTemplate(
            $casis->no_hp_siswa,
            'spmb_pembayaran_sukses',
            [
                $casis->nama_lengkap,
                $pembayaran->order_id,
                $nominal,
                $metode,
                $tanggalBayar,
            ],
            'id',
            $headerImage
        );
    }

    /**
     * Send Payment Success Notification to Admin
     * Template: spmb_notif_admin_pembayaran
     * Params: {{1}}=nama, {{2}}=no_pendaftaran, {{3}}=jurusan,
     *         {{4}}=no_order, {{5}}=nominal, {{6}}=metode, {{7}}=waktu_lunas
     */
    public static function sendPaymentNotificationToAdmin($casis, $pembayaran): bool
    {
        $adminNumber = Setting::where('key', 'wa_center')->value('value');

        if (empty($adminNumber)) {
            Log::warning('WhatsAppService: wa_center is not configured. Admin notification not sent.');
            return false;
        }

        $metode = $pembayaran->tipe_pembayaran === 'offline'
            ? 'Manual / Offline (' . ($pembayaran->payment_type ?: 'Kasir') . ')'
            : 'Online (' . ($pembayaran->payment_type ? str_replace('_', ' ', strtoupper($pembayaran->payment_type)) : 'Midtrans') . ')';

        $tanggalBayar = $pembayaran->settlement_time
            ? Carbon::parse($pembayaran->settlement_time)->translatedFormat('d F Y H:i')
            : Carbon::now()->translatedFormat('d F Y H:i');

        $nominal = number_format($pembayaran->nominal, 0, ',', '.');
        $jurusan = $casis->jurusan ? $casis->jurusan->kode : '-';
        $headerImage = asset('images/logo-bpa.png');

        return self::sendTemplate(
            $adminNumber,
            'spmb_notif_admin_pembayaran',
            [
                $casis->nama_lengkap,
                $casis->no_pendaftaran,
                $jurusan,
                $pembayaran->order_id,
                $nominal,
                $metode,
                $tanggalBayar,
            ],
            'id',
            $headerImage
        );
    }
}