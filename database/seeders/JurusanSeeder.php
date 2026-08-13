<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            [
                'kode' => 'TKRO',
                'nama' => 'Teknik Kendaraan Ringan Otomotif',
                'deskripsi' => 'Program keahlian yang menyiapkan tenaga terampil di bidang mekanik otomotif kendaraan ringan, servis berkala, diagnosis mesin, dan teknologi otomotif terkini.',
                'kuota' => 36,
                'biaya_daftar_ulang' => 1500000,
                'link_wa_group' => 'https://chat.whatsapp.com/sample-tkro-smkbpa',
                'status_aktif' => true,
            ],
            [
                'kode' => 'TJKT',
                'nama' => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'deskripsi' => 'Menguasai infrastruktur jaringan komputer, instalasi jaringan fiber optic, administrasi server, cyber security, dan pengoperasian perangkat telekomunikasi modern.',
                'kuota' => 36,
                'biaya_daftar_ulang' => 1500000,
                'link_wa_group' => 'https://chat.whatsapp.com/sample-tjkt-smkbpa',
                'status_aktif' => true,
            ],
            [
                'kode' => 'AKL',
                'nama' => 'Akuntansi dan Keuangan Lembaga',
                'deskripsi' => 'Membentuk tenaga ahli di bidang pembukuan keuangan, perpajakan, sistem akuntansi komputerisasi, perbankan syariah, dan manajemen keuangan perusahaaan.',
                'kuota' => 36,
                'biaya_daftar_ulang' => 1500000,
                'link_wa_group' => 'https://chat.whatsapp.com/sample-akl-smkbpa',
                'status_aktif' => true,
            ],
            [
                'kode' => 'TBSM',
                'nama' => 'Teknik dan Bisnis Sepeda Motor',
                'deskripsi' => 'Keahlian teknis dan wirausaha di bidang servis tune-up sepeda motor, sistem injeksi fuel, kelistrikan sepeda motor, dan manajemen bengkel resmi.',
                'kuota' => 36,
                'biaya_daftar_ulang' => 1500000,
                'link_wa_group' => 'https://chat.whatsapp.com/sample-tbsm-smkbpa',
                'status_aktif' => true,
            ],
        ];

        foreach ($jurusans as $j) {
            Jurusan::updateOrCreate(['kode' => $j['kode']], $j);
        }
    }
}
