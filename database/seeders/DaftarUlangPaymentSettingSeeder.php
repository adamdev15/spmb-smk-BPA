<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class DaftarUlangPaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'wa_pesan_tagihan_daftar_ulang',
                'name' => 'Template Pesan WA Tagihan Daftar Ulang',
                'value' => "Selamat [NAMA]!\n\nPendaftaran Anda dengan No. Pendaftaran: [NOMOR_DAFTAR] telah Dinyatakan DITERIMA / LULUS VERIFIKASI di SMK Bhakti Praja Adiwerna.\n\nRincian Tagihan Daftar Ulang:\n- Jenis: Daftar Ulang Siswa Baru\n- Jurusan: [JURUSAN]\n- Program: [PROGRAM_KEUNGGULAN]\n- Nominal: Rp [NOMINAL]\n- Jatuh Tempo: [JATUH_TEMPO]\n\nSilakan login ke dashboard siswa untuk melakukan pembayaran online (QRIS/VA/E-Wallet) atau datang langsung ke loket pendaftaran sekolah:\n[LINK_DASHBOARD]\n\nTerima kasih.",
                'type' => 'longtext',
            ],
            [
                'key' => 'wa_pesan_pembayaran_sukses',
                'name' => 'Template Pesan WA Pembayaran Berhasil (Lunas)',
                'value' => "Halo [NAMA],\n\nPembayaran Daftar Ulang Anda telah BERHASIL kami terima (LUNAS).\n\nRincian Pembayaran:\n- No. Pembayaran: [NOMOR_PEMBAYARAN]\n- Nominal: Rp [NOMINAL]\n- Metode: [METODE]\n- Status: LUNAS\n- Tanggal: [TANGGAL_BAYAR]\n\nStatus Pendaftaran Anda saat ini: SUDAH DAFTAR ULANG.\nSilakan simpan pesan ini sebagai bukti pembayaran yang sah.\n\nTerima kasih,\nPanitia SPMB SMK Bhakti Praja Adiwerna",
                'type' => 'longtext',
            ],
            [
                'key' => 'jadwal_daftar_ulang_jatuh_tempo_hari',
                'name' => 'Batas Jatuh Tempo Tagihan (Jumlah Hari)',
                'value' => '7',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'name' => $setting['name'],
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                ]
            );
        }
    }
}