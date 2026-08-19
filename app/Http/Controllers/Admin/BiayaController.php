<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biaya;
use App\Models\Jurusan;
use App\Models\Setting;

class BiayaController extends Controller
{
    public function index()
    {
        $biayas = Biaya::with('jurusans')->latest()->paginate(10);
        $jurusans = Jurusan::where('status_aktif', true)->get();
        
        $jenisBiayaSetting = Setting::where('key', 'jenis_biaya_options')->first();
        $jenisBiayaOptions = $jenisBiayaSetting ? json_decode($jenisBiayaSetting->value, true) : ['Daftar Ulang', 'SPP', 'Seragam'];

        return view('admin.biaya.index', compact('biayas', 'jurusans', 'jenisBiayaOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_biaya' => 'required|string|max:255',
            'jenis_biaya' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'jurusans' => 'required|array',
            'jurusans.*' => 'exists:master_jurusan,id'
        ]);

        $biaya = Biaya::create([
            'nama_biaya' => $request->nama_biaya,
            'jenis_biaya' => $request->jenis_biaya,
            'nominal' => $request->nominal,
        ]);

        $biaya->jurusans()->sync($request->jurusans);

        return redirect()->route('admin.biaya.index')->with('success', 'Biaya berhasil ditambahkan.');
    }

    public function update(Request $request, Biaya $biaya)
    {
        $request->validate([
            'nama_biaya' => 'required|string|max:255',
            'jenis_biaya' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'jurusans' => 'required|array',
            'jurusans.*' => 'exists:master_jurusan,id'
        ]);

        $biaya->update([
            'nama_biaya' => $request->nama_biaya,
            'jenis_biaya' => $request->jenis_biaya,
            'nominal' => $request->nominal,
        ]);

        $biaya->jurusans()->sync($request->jurusans);

        return redirect()->route('admin.biaya.index')->with('success', 'Biaya berhasil diperbarui.');
    }

    public function destroy(Biaya $biaya)
    {
        $biaya->delete();
        return redirect()->route('admin.biaya.index')->with('success', 'Biaya berhasil dihapus.');
    }

    public function storeJenis(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
        ]);

        $jenisBiayaSetting = Setting::firstOrCreate(
            ['key' => 'jenis_biaya_options'],
            ['name' => 'Opsi Jenis Biaya', 'type' => 'text', 'value' => json_encode(['Daftar Ulang', 'SPP', 'Seragam'])]
        );

        $options = json_decode($jenisBiayaSetting->value, true) ?? [];
        if (!in_array($request->nama_jenis, $options)) {
            $options[] = $request->nama_jenis;
            $jenisBiayaSetting->value = json_encode($options);
            $jenisBiayaSetting->save();
        }

        return redirect()->route('admin.biaya.index')->with('success', 'Jenis Biaya baru berhasil ditambahkan.');
    }
}
