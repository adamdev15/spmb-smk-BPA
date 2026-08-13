<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramKeunggulan;
use App\Models\Jurusan;

class ProgramKeunggulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tkro = Jurusan::where('kode', 'TKRO')->first();
        $tjkt = Jurusan::where('kode', 'TJKT')->first();
        $akl = Jurusan::where('kode', 'AKL')->first();
        $tbsm = Jurusan::where('kode', 'TBSM')->first();

        $programs = [
            [
                'nama' => 'SMK Binaan Isuzu',
                'deskripsi' => 'Program pendidikan dan pelatihan kurikulum standar industri otomotif Isuzu Indonesia dengan jaminan magang dan kesempatan kerja.',
                'jurusan_id' => $tkro ? $tkro->id : null,
                'status_aktif' => true,
            ],
            [
                'nama' => 'Kelas Binaan Daihatsu',
                'deskripsi' => 'Pintar Bersama Daihatsu (PBD) memberikan sertifikasi keahlian standar pabrik Astra Daihatsu Motor.',
                'jurusan_id' => $tkro ? $tkro->id : null,
                'status_aktif' => true,
            ],
            [
                'nama' => 'Axioo Class Program',
                'deskripsi' => 'Program industri IT berskala nasional bersama Axioo Indonesia mencakup perakitan laptop, jaringan, dan sertifikasi IT internasional.',
                'jurusan_id' => $tjkt ? $tjkt->id : null,
                'status_aktif' => true,
            ],
            [
                'nama' => 'Astra Motor',
                'deskripsi' => 'Kerja sama kurikulum dan kelas industri teknik sepeda motor Honda standar Astra Motor.',
                'jurusan_id' => $tbsm ? $tbsm->id : null,
                'status_aktif' => true,
            ],
            [
                'nama' => 'Bank Jateng Syariah',
                'deskripsi' => 'Kemitraan transaksi keuangan, tempat pkl/magang, dan pembinaan kompetensi perbankan syariah.',
                'jurusan_id' => $akl ? $akl->id : null,
                'status_aktif' => true,
            ],
            [
                'nama' => 'Bahasa Jepang',
                'deskripsi' => 'Program unggulan penguasaan Bahasa Jepang & persiapan penempatan kerja (Tokutei Ginou / Internships) ke Jepang.',
                'jurusan_id' => null,
                'status_aktif' => true,
            ],
        ];

        foreach ($programs as $p) {
            ProgramKeunggulan::updateOrCreate(['nama' => $p['nama']], $p);
        }
    }
}
