<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Biaya;
use App\Models\Jurusan;

class MasterBiayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jurusans = Jurusan::all()->keyBy('nama');
        
        // Asumsi nama jurusan berdasarkan JurusanSeeder:
        // 1. Teknik Kendaraan Ringan Otomotif
        // 2. Teknik Jaringan Komputer dan Telekomunikasi
        // 3. Akuntansi dan Keuangan Lembaga
        // 4. Teknik dan Bisnis Sepeda Motor
        
        $tkroId = $jurusans->firstWhere('nama', 'Teknik Kendaraan Ringan Otomotif')?->id;
        $tjktId = $jurusans->firstWhere('nama', 'Teknik Jaringan Komputer dan Telekomunikasi')?->id;
        $aklId  = $jurusans->firstWhere('nama', 'Akuntansi dan Keuangan Lembaga')?->id;
        $tbsmId = $jurusans->firstWhere('nama', 'Teknik dan Bisnis Sepeda Motor')?->id;

        $allJurusan = array_filter([$tkroId, $tjktId, $aklId, $tbsmId]);
        $teknikJurusan = array_filter([$tkroId, $tjktId, $tbsmId]);
        
        $biayas = [
            // Uraian Kegiatan
            [
                'nama_biaya' => 'Iuran Dana Pendidikan (SPP) per bulan',
                'jenis_biaya' => 'SPP',
                'nominal' => 140000,
                'jurusans' => $teknikJurusan
            ],
            [
                'nama_biaya' => 'Iuran Dana Pendidikan (SPP) per bulan',
                'jenis_biaya' => 'SPP',
                'nominal' => 120000,
                'jurusans' => array_filter([$aklId])
            ],
            [
                'nama_biaya' => 'Iuran Kegiatan Osis',
                'jenis_biaya' => 'Daftar Ulang',
                'nominal' => 275000,
                'jurusans' => $allJurusan
            ],
            [
                'nama_biaya' => 'Proses Peningkatan Mutu',
                'jenis_biaya' => 'Daftar Ulang',
                'nominal' => 220000,
                'jurusans' => $allJurusan
            ],
            [
                'nama_biaya' => 'Pengadaan Atribut (Osis & Pramuka)',
                'jenis_biaya' => 'Daftar Ulang',
                'nominal' => 115000,
                'jurusans' => $allJurusan
            ],
            [
                'nama_biaya' => 'Asuransi',
                'jenis_biaya' => 'Daftar Ulang',
                'nominal' => 50000,
                'jurusans' => $allJurusan
            ],

            // Rincian Biaya Seragam
            [
                'nama_biaya' => 'Seragam Kaos Olahraga',
                'jenis_biaya' => 'Seragam',
                'nominal' => 135000,
                'jurusans' => $allJurusan
            ],
            [
                'nama_biaya' => 'Bahan Kejuruan',
                'jenis_biaya' => 'Seragam',
                'nominal' => 150000,
                'jurusans' => $teknikJurusan // TKRO, TBSM, TKJ (TJKT)
            ],
            [
                'nama_biaya' => 'Bahan Kejuruan',
                'jenis_biaya' => 'Seragam',
                'nominal' => 146000,
                'jurusans' => array_filter([$aklId]) // Akuntansi
            ],
            [
                'nama_biaya' => 'Baju Werpak',
                'jenis_biaya' => 'Seragam',
                'nominal' => 190000,
                'jurusans' => array_filter([$tkroId, $tbsmId]) // TKRO & TBSM
            ],
            [
                'nama_biaya' => 'Baju Werpak',
                'jenis_biaya' => 'Seragam',
                'nominal' => 160000,
                'jurusans' => array_filter([$tjktId, $aklId]) // TKJ & Akuntansi
            ],
        ];

        // Ensure Biaya table is cleared to avoid duplicates if re-run
        // Biaya::truncate(); // Uncomment if you want to wipe before seeding

        foreach ($biayas as $data) {
            $jurusans = $data['jurusans'];
            unset($data['jurusans']);

            $biaya = Biaya::create($data);
            if (!empty($jurusans)) {
                $biaya->jurusans()->sync($jurusans);
            }
        }
    }
}
