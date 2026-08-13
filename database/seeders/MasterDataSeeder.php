<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ketrampilan
        $ketrampilan = [
            ['nama' => 'TBSM', 'jk' => 'L'],
            ['nama' => 'TITL', 'jk' => 'L'],
            ['nama' => 'REGULER', 'jk' => 'L'],
            ['nama' => 'TATABOGA', 'jk' => 'P'],
            ['nama' => 'TATABUSANA', 'jk' => 'P'],
            ['nama' => 'TKJ', 'jk' => 'P'],
            ['nama' => 'RESET', 'jk' => 'P'],
            ['nama' => 'REGULER', 'jk' => 'P'],
        ];
        foreach ($ketrampilan as $i) {
            DB::table('master_ketrampilan')->insert([
                'nama' => $i['nama'],
                'jk' => $i['jk'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Hobi
        $hobi = ['Membaca', 'Menulis', 'Olahraga', 'Seni', 'Traveling', 'Gaming', 'Lainnya'];
        foreach ($hobi as $i) {
            DB::table('master_hobi')->insert(['nama' => $i, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Cita-cita
        $cita = ['Guru', 'Dokter', 'Insinyur', 'TNI/Polri', 'Pengusaha', 'Lainnya'];
        foreach ($cita as $i) {
            DB::table('master_cita')->insert(['nama' => $i, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Orientasi Orang Tua
        $orientasi = ['Perguruan Tinggi', 'Kerja', 'mondok'];
        foreach ($orientasi as $i) {
            DB::table('master_orientasi_ortu')->insert(['nama' => $i, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Pendidikan
        $pendidikan = ['SD', 'SMP', 'SMA', 'S1', 'S2', 'S3', 'Tidak Sekolah'];
        foreach ($pendidikan as $i) {
            DB::table('master_pendidikan')->insert(['nama' => $i, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Penghasilan
        $penghasilan = ['< 1 Juta', '1 - 3 Juta', '3 - 5 Juta', '> 5 Juta'];
        foreach ($penghasilan as $i) {
            DB::table('master_penghasilan')->insert(['nama' => $i, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Pekerjaan (With Target)
        $pekerjaan = [
            ['nama' => 'PNS', 'target' => 'semua'],
            ['nama' => 'TNI/Polri', 'target' => 'semua'],
            ['nama' => 'Wiraswasta', 'target' => 'semua'],
            ['nama' => 'Petani', 'target' => 'semua'],
            ['nama' => 'Buruh', 'target' => 'semua'],
            ['nama' => 'Ibu Rumah Tangga', 'target' => 'ibu'],
            ['nama' => 'Pensiunan', 'target' => 'semua'],
            ['nama' => 'Tidak Bekerja', 'target' => 'semua'],
        ];
        foreach ($pekerjaan as $i) {
            DB::table('master_pekerjaan')->insert([
                'nama' => $i['nama'],
                'target' => $i['target'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}