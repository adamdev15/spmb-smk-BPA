<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalSpmb;

class JadwalSpmbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = [
            [
                'tahap_nama' => 'Pendaftaran Online & Offline',
                'tgl_mulai' => '2026-02-14',
                'tgl_selesai' => '2026-03-27',
                'jam_mulai' => '00:01:00',
                'jam_selesai' => '11:00:00',
                'keterangan' => 'Pengisian formulir pendaftaran secara online di website atau secara offline di Sekretariat SPMB SMK Bhakti Praja Adiwerna.',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'tahap_nama' => 'Tes Seleksi & Wawancara',
                'tgl_mulai' => '2026-03-31',
                'tgl_selesai' => '2026-04-02',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '14:00:00',
                'keterangan' => 'Pelaksanaan Tes Psikotes serta Seleksi Fisik (pemeriksaan tindik, tato, dan buta warna) bagi calon siswa.',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'tahap_nama' => 'Pengumuman Hasil Seleksi',
                'tgl_mulai' => '2026-04-07',
                'tgl_selesai' => '2026-04-07',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '23:59:00',
                'keterangan' => 'Pengumuman hasil kelulusan dapat dilihat secara online melalui login akun calon siswa atau papan pengumuman sekolah.',
                'urutan' => 3,
                'is_active' => true,
            ],
            [
                'tahap_nama' => 'Daftar Ulang & Pembayaran',
                'tgl_mulai' => '2026-04-09',
                'tgl_selesai' => '2026-04-16',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '15:00:00',
                'keterangan' => 'Daftar ulang calon siswa yang dinyatakan lulus. Pembayaran dapat dilakukan via Midtrans online (QRIS/VA/Bank) atau kasir sekolah.',
                'urutan' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($jadwals as $j) {
            JadwalSpmb::updateOrCreate(['tahap_nama' => $j['tahap_nama']], $j);
        }
    }
}
