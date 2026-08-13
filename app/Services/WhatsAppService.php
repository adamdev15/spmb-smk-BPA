<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class WhatsAppService
{
    /**
     * Send WhatsApp message via Fonnte API
     *
     * @param string $target Phone number (e.g. 08123456789 or 628123456789)
     * @param string $message Text content
     * @return bool
     */
    public static function sendMessage(string $target, string $message): bool
    {
        $token = Setting::where('key', 'fonnte_token')->value('value') ?: config('services.fonnte.token');

        if (empty($token)) {
            Log::warning('WhatsAppService: Fonnte token is empty. Message not sent.', ['target' => $target]);
            return false;
        }

        // Format Indonesian phone numbers
        $target = preg_replace('/[^0-9]/', '', $target);
        if (str_starts_with($target, '0')) {
            $target = '62' . substr($target, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully to ' . $target);
                return true;
            }

            Log::error('WhatsAppService error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsAppService Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Registration Notification to Applicant
     */
    public static function sendRegistrationSuccess($casis): bool
    {
        $template = Setting::where('key', 'template_pesan_pendaftaran')->value('value')
            ?: "Pendaftaran SPMB SMK Bhakti Praja Adiwerna Berhasil!\n\nNomor Pendaftaran: [NOMOR_DAFTAR]\nNama: [NAMA]\nJurusan: [JURUSAN]\nProgram Keunggulan: [PROGRAM_KEUNGGULAN]\nPassword Login: [PASSWORD]\n\nSilakan cetak Kartu Bukti Pendaftaran dan ikuti tes seleksi sesuai jadwal.\nLink Grup WhatsApp Jurusan: [LINK_WA_GROUP]";

        $jurusanNama = $casis->jurusan ? $casis->jurusan->nama : '-';
        $programNama = $casis->programKeunggulan ? $casis->programKeunggulan->nama : '-';
        $waLink = $casis->jurusan ? ($casis->jurusan->link_wa_group ?: '-') : '-';

        $message = str_replace(
            ['[NOMOR_DAFTAR]', '[NAMA]', '[JURUSAN]', '[PROGRAM_KEUNGGULAN]', '[PASSWORD]', '[LINK_WA_GROUP]'],
            [$casis->no_pendaftaran, $casis->nama_lengkap, $jurusanNama, $programNama, $casis->nisn, $waLink],
            $template
        );

        return self::sendMessage($casis->no_hp_siswa, $message);
    }

    /**
     * Send Reminder Notification to Applicant
     */
    public static function sendReminder($casis): bool
    {
        $template = Setting::where('key', 'wa_pesan_ingatkan')->value('value')
            ?: "Halo [NAMA],\nKami dari Panitia SPMB SMK Bhakti Praja Adiwerna mengingatkan untuk segera melengkapi berkas pendaftaran Anda dan melakukan verifikasi data.";

        $message = str_replace(
            ['[NAMA]'],
            [$casis->nama_lengkap],
            $template
        );

        return self::sendMessage($casis->no_hp_siswa, $message);
    }

    /**
     * Send Daftar Ulang Reminder Notification to Applicant
     */
    public static function sendDaftarUlangReminder($casis): bool
    {
        $template = Setting::where('key', 'wa_pesan_daftar_ulang')->value('value')
            ?: "Halo [NAMA],\nKami dari Panitia SPMB SMK Bhakti Praja Adiwerna mengingatkan untuk segera melakukan pembayaran Daftar Ulang karena Anda telah Dinyatakan Lulus Seleksi/Terverifikasi. Silakan hubungi panitia untuk informasi lebih lanjut.";

        $message = str_replace(
            ['[NAMA]'],
            [$casis->nama_lengkap],
            $template
        );

        return self::sendMessage($casis->no_hp_siswa, $message);
    }

    /**
     * Send Payment Success Notification to Applicant
     */
    public static function sendPaymentSuccess($casis, $pembayaran): bool
    {
        $message = "Halo {$casis->nama_lengkap},\n\nTerima kasih, Pembayaran Daftar Ulang Anda sebesar Rp " . number_format($pembayaran->nominal, 0, ',', '.') . " telah berhasil diproses (LUNAS).\n\nStatus pendaftaran Anda saat ini adalah: SUDAH DAFTAR ULANG.\n\nSimpan pesan ini sebagai bukti pembayaran yang sah.\nSalam,\nPanitia SPMB SMK Bhakti Praja Adiwerna.";

        return self::sendMessage($casis->no_hp_siswa, $message);
    }
}
