<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class PengumumanSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'name' => 'Nomor Surat Pengumuman',
                'key' => 'pengumuman_nomor_surat',
                'value' => '700.a/SMK.BP/VI/2026',
                'type' => 'text',
            ],
            [
                'name' => 'Tanggal Pembekalan MPLS',
                'key' => 'pengumuman_tgl_mpls',
                'value' => '10 Juli 2026',
                'type' => 'text',
            ],
            [
                'name' => 'Tanggal Awal Masuk Sekolah',
                'key' => 'pengumuman_tgl_masuk',
                'value' => '13 Juli 2026',
                'type' => 'text',
            ],
            [
                'name' => 'Nama Kepala Sekolah',
                'key' => 'pengumuman_nama_kepsek',
                'value' => 'Drs. H. Fulan, M.Pd.',
                'type' => 'text',
            ],
            [
                'name' => 'NIP Kepala Sekolah',
                'key' => 'pengumuman_nip_kepsek',
                'value' => '19700101 199512 1 001',
                'type' => 'text',
            ],
            [
                'name' => 'Tanda Tangan Kepala Sekolah',
                'key' => 'pengumuman_ttd_kepsek',
                'value' => '',
                'type' => 'file',
            ],
            [
                'name' => 'Nama Ketua SPMB',
                'key' => 'pengumuman_nama_ketua',
                'value' => 'Fulanah, S.Pd.',
                'type' => 'text',
            ],
            [
                'name' => 'Tanda Tangan Ketua SPMB',
                'key' => 'pengumuman_ttd_ketua',
                'value' => '',
                'type' => 'file',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
