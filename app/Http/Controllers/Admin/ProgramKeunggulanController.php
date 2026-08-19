<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramKeunggulan;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class ProgramKeunggulanController extends Controller
{
    public function index()
    {
        $programs = ProgramKeunggulan::with('jurusan')->paginate(10);
        $jurusans = Jurusan::where('status_aktif', true)->get();
        return view('admin.program_keunggulan.index', compact('programs', 'jurusans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jurusan_id' => 'nullable|exists:master_jurusan,id',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('program_keunggulan', 'public');
        }

        ProgramKeunggulan::create($validated);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Program Keunggulan berhasil ditambahkan.']);
        }
        return back()->with('success', 'Program Keunggulan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $program = ProgramKeunggulan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jurusan_id' => 'nullable|exists:master_jurusan,id',
            'status_aktif' => 'required|boolean'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('program_keunggulan', 'public');
        }

        $program->update($validated);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Program Keunggulan berhasil diperbarui.']);
        }
        return back()->with('success', 'Program Keunggulan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $program = ProgramKeunggulan::findOrFail($id);
        $program->delete();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Program Keunggulan berhasil dihapus.']);
        }
        return back()->with('success', 'Program Keunggulan berhasil dihapus.');
    }
}
