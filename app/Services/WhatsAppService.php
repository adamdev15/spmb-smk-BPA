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
     * Send WhatsApp message via Fonnte API
     *
     * @param string $target Phone number (e.g. 08123456789 or 628123456789)
     * @param string $message Text content
     * @return bool
     */
    public static function sendMessage(string $target, string $message): bool
    {
        $status = Setting::where('key', 'fonnte_status')->value('value');
        if ($status === '0' || $status === 'false') {
            Log::info('WhatsAppService: Fonnte is disabled via settings. Message not sent to ' . $target);
            return false;
        }

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
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] === true) {
                    Log::info('WhatsApp message sent successfully to ' . $target);
                    return true;
                } else {
                    Log::error('WhatsAppService error: ' . $response->body());
                    return false;
                }
            }

            Log::error('WhatsAppService HTTP error: ' . $response->status() . ' - ' . $response->body());
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
     * Send Automatic Re-Enrollment Invoice/Billing Notification to Applicant
     */
    public static function sendTagihanDaftarUlang(Casis $casis, Pembayaran $pembayaran): bool
    {
        $template = Setting::where('key', 'wa_pesan_tagihan_daftar_ulang')->value('value')
            ?: "Selamat [NAMA]!\n\nPendaftaran Anda dengan No. Pendaftaran: [NOMOR_DAFTAR] telah Dinyatakan DITERIMA / LULUS VERIFIKASI di SMK Bhakti Praja Adiwerna.\n\nRincian Tagihan Daftar Ulang:\n- Jenis: Daftar Ulang Siswa Baru\n- Jurusan: [JURUSAN]\n- Program: [PROGRAM_KEUNGGULAN]\n- Nominal: Rp [NOMINAL]\n- Jatuh Tempo: [JATUH_TEMPO]\n\nSilakan login ke dashboard siswa untuk melakukan pembayaran online (QRIS/VA/E-Wallet) atau datang langsung ke loket pendaftaran sekolah:\n[LINK_DASHBOARD]\n\nTerima kasih.";

        $jurusanNama = $casis->jurusan ? $casis->jurusan->nama : '-';
        $programNama = $casis->programKeunggulan ? $casis->programKeunggulan->nama : '-';
        $nominal = number_format($pembayaran->nominal, 0, ',', '.');
        $jatuhTempo = $pembayaran->tgl_jatuh_tempo
            ? Carbon::parse($pembayaran->tgl_jatuh_tempo)->translatedFormat('d F Y')
            : 'Sesuai Jadwal';
        $linkDashboard = url('/login-siswa');

        $message = str_replace(
            ['[NAMA]', '[NOMOR_DAFTAR]', '[JURUSAN]', '[PROGRAM_KEUNGGULAN]', '[NOMINAL]', '[JATUH_TEMPO]', '[LINK_DASHBOARD]'],
            [$casis->nama_lengkap, $casis->no_pendaftaran, $jurusanNama, $programNama, $nominal, $jatuhTempo, $linkDashboard],
            $template
        );

        return self::sendMessage($casis->no_hp_siswa, $message);
    }

    /**
     * Send Payment Success (Lunas) Notification to Applicant
     */
    public static function sendPaymentSuccess($casis, $pembayaran): bool
    {
        $template = Setting::where('key', 'wa_pesan_pembayaran_sukses')->value('value')
            ?: "Halo [NAMA],\n\nPembayaran Daftar Ulang Anda telah BERHASIL kami terima (LUNAS).\n\nRincian Pembayaran:\n- No. Pembayaran: [NOMOR_PEMBAYARAN]\n- Nominal: Rp [NOMINAL]\n- Metode: [METODE]\n- Status: LUNAS\n- Tanggal: [TANGGAL_BAYAR]\n\nStatus Pendaftaran Anda saat ini: SUDAH DAFTAR ULANG.\nSilakan simpan pesan ini sebagai bukti pembayaran yang sah.\n\nTerima kasih,\nPanitia SPMB SMK Bhakti Praja Adiwerna";

        $metode = $pembayaran->tipe_pembayaran === 'offline'
            ? 'Manual / Offline (' . ($pembayaran->payment_type ?: 'Kasir') . ')'
            : 'Online (' . ($pembayaran->payment_type ? str_replace('_', ' ', strtoupper($pembayaran->payment_type)) : 'Midtrans') . ')';

        $tanggalBayar = $pembayaran->settlement_time
            ? Carbon::parse($pembayaran->settlement_time)->translatedFormat('d F Y H:i')
            : Carbon::now()->translatedFormat('d F Y H:i');

        $nominal = number_format($pembayaran->nominal, 0, ',', '.');

        $message = str_replace(
            ['[NAMA]', '[NOMOR_PEMBAYARAN]', '[NOMINAL]', '[METODE]', '[TANGGAL_BAYAR]'],
            [$casis->nama_lengkap, $pembayaran->order_id, $nominal, $metode, $tanggalBayar],
            $template
        );

        return self::sendMessage($casis->no_hp_siswa, $message);
    }

    /**
     * Send Payment Success Notification to Admin
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

        $message = "⚠️ *PEMBERITAHUAN PEMBAYARAN MASUK* ⚠️\n\n"
                 . "Telah diterima pembayaran Daftar Ulang dari siswa:\n\n"
                 . "Nama: *{$casis->nama_lengkap}*\n"
                 . "No. Daftar: {$casis->no_pendaftaran}\n"
                 . "Jurusan: {$jurusan}\n\n"
                 . "*Rincian Transaksi:*\n"
                 . "- No. Order: {$pembayaran->order_id}\n"
                 . "- Nominal: Rp {$nominal}\n"
                 . "- Metode: {$metode}\n"
                 . "- Waktu Lunas: {$tanggalBayar}\n\n"
                 . "Silakan verifikasi atau cetak kwitansi di Dashboard Admin jika diperlukan.";

        return self::sendMessage($adminNumber, $message);
    }
}