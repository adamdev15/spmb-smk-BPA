<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Branding & General Info
            [
                'key' => 'nama_sekolah',
                'name' => 'Nama Sekolah',
                'value' => 'SMK Bhakti Praja Adiwerna',
                'type' => 'text'
            ],
            [
                'key' => 'singkatan_sekolah',
                'name' => 'Singkatan Sekolah',
                'value' => 'SMK BPA',
                'type' => 'text'
            ],
            [
                'key' => 'tagline_sekolah',
                'name' => 'Tagline Sekolah',
                'value' => 'Mewujudkan Lulusan Berkarakter, Kompeten, dan Siap Kerja',
                'type' => 'text'
            ],
            [
                'key' => 'logo',
                'name' => 'Logo Sekolah',
                'value' => null,
                'type' => 'file'
            ],
            [
                'key' => 'landing_hero',
                'name' => 'Gambar Hero Landing Page',
                'value' => null,
                'type' => 'file'
            ],
            [
                'key' => 'deskripsi_hero',
                'name' => 'Deskripsi Hero',
                'value' => 'Selamat datang di SPMB SMK Bhakti Praja Adiwerna Tahun Ajaran 2026/2027. Sekolah Kejuruan Unggulan yang Didukung Program Binaan Industri Terkemuka.',
                'type' => 'longtext'
            ],
            [
                'key' => 'tahun_ajaran',
                'name' => 'Tahun Ajaran',
                'value' => '2026/2027',
                'type' => 'text'
            ],
            [
                'key' => 'brosur',
                'name' => 'Brosur SPMB (PDF/Image)',
                'value' => null,
                'type' => 'file'
            ],
            [
                'key' => 'alamat_sekolah',
                'name' => 'Alamat Sekolah',
                'value' => 'Jl. Singkil No. 24, Adiwerna, Kab. Tegal, Jawa Tengah',
                'type' => 'longtext'
            ],
            [
                'key' => 'telepon_sekolah',
                'name' => 'Telepon Sekolah',
                'value' => '(0283) 443210',
                'type' => 'text'
            ],
            [
                'key' => 'email_sekolah',
                'name' => 'Email Sekolah',
                'value' => 'spmb@smkbhaktiprajaadiwerna.sch.id',
                'type' => 'text'
            ],
            [
                'key' => 'wa_center',
                'name' => 'Nomor WA Center / Panitia',
                'value' => '085866918641',
                'type' => 'text'
            ],
            [
                'key' => 'contact_person',
                'name' => 'Contact Person (Bisa diisi banyak pisahkan baris baru)',
                'value' => "Wahyu Cahyo Nugroho, ST (08123456789)\nTitik Wijayanti, S.Pd (08987654321)",
                'type' => 'longtext'
            ],

            // Default Fee & Registration Prefix Settings

            [
                'key' => 'prefix_no_pendaftaran',
                'name' => 'Prefix Nomor Pendaftaran',
                'value' => 'BPA-2026-',
                'type' => 'text'
            ],

            // WhatsApp Fonnte Settings
            [
                'key' => 'fonnte_token',
                'name' => 'Fonnte Token (WhatsApp API)',
                'value' => env('FONNTE_TOKEN', ''),
                'type' => 'text'
            ],
            [
                'key' => 'template_pesan_pendaftaran',
                'name' => 'Template WA Pendaftaran Berhasil',
                'value' => "Pendaftaran SPMB SMK Bhakti Praja Adiwerna Berhasil!\n\nNomor Pendaftaran: [NOMOR_DAFTAR]\nNama: [NAMA]\nJurusan: [JURUSAN]\nProgram Keunggulan: [PROGRAM_KEUNGGULAN]\nPassword Login: [PASSWORD]\n\nSilakan cetak Kartu Bukti Pendaftaran dan ikuti tes seleksi sesuai jadwal.\nLink Grup WhatsApp Jurusan: [LINK_WA_GROUP]\n\nWebsite: " . config('app.url'),
                'type' => 'longtext'
            ],
            [
                'key' => 'wa_pesan_ingatkan',
                'name' => 'Template WA Pengingat Berkas/Verifikasi',
                'value' => "Halo [NAMA],\nKami dari Panitia SPMB SMK Bhakti Praja Adiwerna mengingatkan untuk segera melengkapi berkas pendaftaran Anda dan melakukan verifikasi data.",
                'type' => 'longtext'
            ],
            [
                'key' => 'wa_pesan_daftar_ulang',
                'name' => 'Template WA Pengingat Daftar Ulang',
                'value' => "Halo [NAMA],\nKami dari Panitia SPMB SMK Bhakti Praja Adiwerna mengingatkan untuk segera melakukan pembayaran Daftar Ulang karena Anda telah Dinyatakan Lulus Seleksi/Terverifikasi. Silakan hubungi panitia untuk informasi lebih lanjut.",
                'type' => 'longtext'
            ],

            // Midtrans Settings
            [
                'key' => 'midtrans_merchant_id',
                'name' => 'Midtrans Merchant ID',
                'value' => env('MIDTRANS_MERCHANT_ID', ''),
                'type' => 'text'
            ],
            [
                'key' => 'midtrans_server_key',
                'name' => 'Midtrans Server Key',
                'value' => env('MIDTRANS_SERVER_KEY', ''),
                'type' => 'text'
            ],
            [
                'key' => 'midtrans_client_key',
                'name' => 'Midtrans Client Key',
                'value' => env('MIDTRANS_CLIENT_KEY', ''),
                'type' => 'text'
            ],
            [
                'key' => 'midtrans_is_production',
                'name' => 'Gunakan Midtrans Production? (1 = Ya, 0 = Tidak)',
                'value' => env('MIDTRANS_IS_PRODUCTION', false) ? '1' : '0',
                'type' => 'text'
            ],

            // Requirements & Terms
            [
                'key' => 'ketentuan_spmb',
                'name' => 'Ketentuan & Persyaratan SPMB',
                'value' => '<ul><li>Calon siswa mengisi data pendaftaran online atau offline secara akurat.</li><li>Pas foto 3x4 berwarna wajib diunggah pada sistem online.</li><li>Mengikuti rangkaian tes seleksi (Psikotes & Seleksi Fisik: tindik, tato, buta warna).</li><li>Daftar ulang dilakukan setelah dinyatakan lulus seleksi.</li></ul>',
                'type' => 'longtext'
            ],
            [
                'key' => 'alur_spmb_gambar',
                'name' => 'Gambar Alur SPMB (Upload Gambar)',
                'value' => 'images/alur-pendaftaran.png',
                'type' => 'file'
            ],
            [
                'key' => 'alur_spmb_konten',
                'name' => 'Konten Teks Alur SPMB',
                'value' => '<ol class="list-decimal pl-4 space-y-2"><li>Calon peserta didik mengisi formulir pendaftaran online di website SPMB</li><li>Calon peserta didik login menggunakan NISN dan kata sandi/password yang telah dibuat sebelumnya</li><li>Calon peserta didik melengkapi biodata dan mencetak kartu pendaftaran</li><li>Calon peserta didik mengikuti tes seleksi sesuai jadwal yang ditentukan</li></ol>',
                'type' => 'longtext'
            ],

            // Social Media
            [
                'key' => 'sosmed_facebook',
                'name' => 'Link Facebook',
                'value' => 'https://facebook.com/',
                'type' => 'text'
            ],
            [
                'key' => 'sosmed_instagram',
                'name' => 'Link Instagram',
                'value' => 'https://instagram.com/',
                'type' => 'text'
            ],
            [
                'key' => 'sosmed_youtube',
                'name' => 'Link YouTube',
                'value' => 'https://youtube.com/',
                'type' => 'text'
            ],
            [
                'key' => 'sosmed_tiktok',
                'name' => 'Link TikTok',
                'value' => 'https://tiktok.com/',
                'type' => 'text'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}