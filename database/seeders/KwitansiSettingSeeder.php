<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class KwitansiSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'name' => 'Nama Panitia SPMB',
                'key' => 'kwitansi_nama_panitia',
                'value' => 'Panitia Penerimaan Siswa Baru',
                'type' => 'text',
            ],
            [
                'name' => 'Tanda Tangan Panitia',
                'key' => 'kwitansi_ttd_panitia',
                'value' => '',
                'type' => 'file',
            ],
            [
                'name' => 'Stempel Panitia',
                'key' => 'kwitansi_stempel_panitia',
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
