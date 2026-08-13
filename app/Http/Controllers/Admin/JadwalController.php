<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\SpmbPeriod;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $periods = SpmbPeriod::with('tahunAjaran')->withCount('casis')->orderByDesc('tanggal_mulai')->get();
        
        $activePeriod = SpmbPeriod::with('tahunAjaran')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->where('status', 'aktif')
            ->first();

        return view('admin.jadwal.index', compact('tahunAjarans', 'periods', 'activePeriod'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'gelombang' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        SpmbPeriod::create($validated);
        return back()->with('success', 'Periode SPMB berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $period = SpmbPeriod::findOrFail($id);
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'gelombang' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $period->update($validated);
        return back()->with('success', 'Periode SPMB berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $period = SpmbPeriod::findOrFail($id);
        $period->delete();
        return back()->with('success', 'Periode SPMB berhasil dihapus.');
    }
    
    public function storeTahunAjaran(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:tahun_ajarans,nama',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        TahunAjaran::create($validated);
        return back()->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }
}
