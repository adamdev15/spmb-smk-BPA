<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class MasterJurusanController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::withCount('casis')->get();
        return view('admin.jurusan.index', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:master_jurusan,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1',
            'biaya_daftar_ulang' => 'required|numeric|min:0',
            'link_wa_group' => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('jurusan', 'public');
        }

        Jurusan::create($validated);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Program Keahlian (Jurusan) berhasil ditambahkan.']);
        }
        return back()->with('success', 'Program Keahlian (Jurusan) berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|unique:master_jurusan,kode,' . $id,
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1',
            'biaya_daftar_ulang' => 'required|numeric|min:0',
            'link_wa_group' => 'nullable|url',
            'status_aktif' => 'required|boolean'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('jurusan', 'public');
        }

        $jurusan->update($validated);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Program Keahlian berhasil diperbarui.']);
        }
        return back()->with('success', 'Program Keahlian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Program Keahlian berhasil dihapus.']);
        }
        return back()->with('success', 'Program Keahlian berhasil dihapus.');
    }
}
