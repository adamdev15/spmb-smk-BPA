<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class RegionController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = Provinsi::orderBy('nama_provinsi', 'asc')->get();
        return response()->json($provinsi);
    }

    public function getKabupaten($id_provinsi)
    {
        $kabupaten = Kabupaten::where('kode_prov', $id_provinsi)
            ->orderBy('nama_kabkota', 'asc')
            ->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan($id_kabupaten)
    {
        $kecamatan = Kecamatan::where('kode_kabkota', $id_kabupaten)
            ->orderBy('nama_kec', 'asc')
            ->get();
        return response()->json($kecamatan);
    }

    public function getKelurahan($id_kecamatan)
    {
        $kelurahan = Kelurahan::where('kode_kec', $id_kecamatan)
            ->orderBy('nama_desa_kel', 'asc')
            ->get();
        return response()->json($kelurahan);
    }
}
