<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    private $tables = [
        'cita' => 'master_cita',
        'hobi' => 'master_hobi',
        'pekerjaan' => 'master_pekerjaan',
        'orientasi' => 'master_orientasi_ortu',
        'ketrampilan' => 'master_ketrampilan',
    ];

    private $labels = [
        'cita' => 'Cita-cita',
        'hobi' => 'Hobi',
        'pekerjaan' => 'Pekerjaan',
        'orientasi' => 'Orientasi Murid',
        'ketrampilan' => 'Keterampilan',
    ];

    public function index()
    {
        $types = [
    'cita' => [
        'label' => 'Cita-cita',
        'count' => DB::table('master_cita')->count()
    ],
    'hobi' => [
        'label' => 'Hobi',
        'count' => DB::table('master_hobi')->count()
    ],
    'pekerjaan' => [
        'label' => 'Pekerjaan',
        'count' => DB::table('master_pekerjaan')->count()
    ],
    'orientasi' => [
        'label' => 'Orientasi Murid',
        'count' => DB::table('master_orientasi_ortu')->count()
    ],
    'ketrampilan' => [
        'label' => 'Keterampilan',
        'count' => DB::table('master_ketrampilan')->count()
    ],
];

        return view('admin.master-data.list', compact('types'));
    }

    public function show($type)
    {
        if (!isset($this->tables[$type])) {
            abort(404);
        }

        $table = $this->tables[$type];
        $label = $this->labels[$type];
        $data = DB::table($table)->orderBy('id', 'desc')->get();

        return view('admin.master-data.index', compact('type', 'label', 'data', 'table'));
    }

    public function store(Request $request, $type)
    {
        if (!isset($this->tables[$type])) {
            return response()->json(['success' => false, 'message' => 'Tipe master data tidak valid'], 404);
        }

        $table = $this->tables[$type];
        $rules = ['nama' => 'required|string|max:255'];

        // Khusus untuk ketrampilan, tambahkan validasi jk
        if ($type === 'ketrampilan') {
            $rules['jk'] = 'required|in:L,P';
        }

        // Khusus untuk pekerjaan, tambahkan validasi target
        if ($type === 'pekerjaan') {
            $rules['target'] = 'required|in:ayah,ibu,wali,semua';
        }

        $validated = $request->validate($rules);

        $data = ['nama' => $validated['nama']];

        if ($type === 'ketrampilan' && isset($validated['jk'])) {
            $data['jk'] = $validated['jk'];
        }

        if ($type === 'pekerjaan' && isset($validated['target'])) {
            $data['target'] = $validated['target'];
        }

        $id = DB::table($table)->insertGetId([
            ...$data,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $item = DB::table($table)->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $item
        ]);
    }

    public function update(Request $request, $type, $id)
    {
        if (!isset($this->tables[$type])) {
            return response()->json(['success' => false, 'message' => 'Tipe master data tidak valid'], 404);
        }

        $table = $this->tables[$type];
        $rules = ['nama' => 'required|string|max:255'];

        // Khusus untuk ketrampilan, tambahkan validasi jk
        if ($type === 'ketrampilan') {
            $rules['jk'] = 'required|in:L,P';
        }

        // Khusus untuk pekerjaan, tambahkan validasi target
        if ($type === 'pekerjaan') {
            $rules['target'] = 'required|in:ayah,ibu,wali,semua';
        }

        $validated = $request->validate($rules);

        $data = ['nama' => $validated['nama']];

        if ($type === 'ketrampilan' && isset($validated['jk'])) {
            $data['jk'] = $validated['jk'];
        }

        if ($type === 'pekerjaan' && isset($validated['target'])) {
            $data['target'] = $validated['target'];
        }

        DB::table($table)->where('id', $id)->update([
            ...$data,
            'updated_at' => now(),
        ]);

        $item = DB::table($table)->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $item
        ]);
    }

    public function destroy($type, $id)
    {
        if (!isset($this->tables[$type])) {
            return response()->json(['success' => false, 'message' => 'Tipe master data tidak valid'], 404);
        }

        $table = $this->tables[$type];
        DB::table($table)->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}

