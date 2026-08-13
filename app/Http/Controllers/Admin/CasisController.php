<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Casis;
use App\Models\Jurusan;
use App\Models\ProgramKeunggulan;
use App\Models\Setting;
use App\Models\Pembayaran;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CasisController extends Controller
{
    public function apiSearch(Request $request)
    {
        $term = $request->q;
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $casis = Casis::where('nama_lengkap', 'like', "%{$term}%")
            ->orWhere('nisn', 'like', "%{$term}%")
            ->orWhere('no_pendaftaran', 'like', "%{$term}%")
            ->select('id', 'nama_lengkap', 'nisn', 'no_pendaftaran')
            ->orderBy('nama_lengkap')
            ->take(5)
            ->get();

        return response()->json($casis);
    }

    public function index(Request $request)
    {
        $query = Casis::with(['jurusan', 'programKeunggulan', 'spmbPeriod.tahunAjaran']);

        $tahunAjarans = \App\Models\TahunAjaran::with('spmbPeriods')->orderBy('nama', 'desc')->get();
        $activePeriod = \App\Models\SpmbPeriod::where('status', 'aktif')->first();

        $selectedTahunAjaranId = $request->input('tahun_ajaran_id');
        $selectedSpmbPeriodId = $request->input('spmb_period_id');

        if (!$selectedTahunAjaranId && !$selectedSpmbPeriodId && $activePeriod) {
            $selectedTahunAjaranId = $activePeriod->tahun_ajaran_id;
            $selectedSpmbPeriodId = $activePeriod->id;
        }

        if ($selectedSpmbPeriodId) {
            $query->where('spmb_period_id', $selectedSpmbPeriodId);
        } elseif ($selectedTahunAjaranId) {
            $query->whereHas('spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
            });
        }

        // Search Filter (Name/NISN/No Pendaftaran)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('sumber')) {
            $query->where('sumber_pendaftaran', $request->sumber);
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->filled('status_kelulusan')) {
            $query->where('status_kelulusan', $request->status_kelulusan);
        }

        if ($request->filled('status_daftar_ulang')) {
            $query->where('status_daftar_ulang', $request->status_daftar_ulang);
        }

        $casis = $query->latest()->paginate(15)->withQueryString();
        $jurusans = Jurusan::where('status_aktif', true)->get();

        return view('admin.casis.index', compact(
            'casis', 
            'jurusans', 
            'tahunAjarans', 
            'selectedTahunAjaranId', 
            'selectedSpmbPeriodId'
        ));
    }

    public function create()
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $programs = ProgramKeunggulan::where('status_aktif', true)->get();
        $ketrampilan = DB::table('master_ketrampilan')->get();
        $hobi = DB::table('master_hobi')->get();
        $cita = DB::table('master_cita')->get();
        $orientasi = DB::table('master_orientasi_ortu')->get();

        return view('admin.casis.form', compact('jurusans', 'programs', 'ketrampilan', 'hobi', 'cita', 'orientasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|unique:casis,nisn',
            'jurusan_id' => 'required|exists:master_jurusan,id',
            'jk' => 'required|in:L,P',
            'no_hp_siswa' => 'required|string',
            'sumber_pendaftaran' => 'required|in:online,offline',
        ]);

        $year = date('Y');
        $prefix = Setting::where('key', 'prefix_no_pendaftaran')->value('value') ?: "BPA-$year-";
        $lastCasis = Casis::where('no_pendaftaran', 'LIKE', "$prefix%")
            ->latest()
            ->first();

        $nextId = 1;
        if ($lastCasis) {
            $parts = explode('-', $lastCasis->no_pendaftaran);
            $lastSeq = (int)end($parts);
            $nextId = $lastSeq + 1;
        }
        $noPendaftaran = $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $data = $request->except(['_token', 'password']);
        $rawPassword = $request->filled('password') ? $request->password : $request->nisn;
        $data['password'] = Hash::make($rawPassword);
        $data['no_pendaftaran'] = $noPendaftaran;
        $data['status_pendaftaran'] = 'Submitted';
        $data['status_verifikasi'] = 'Diverifikasi'; // Admin manual entry is pre-verified

        $activePeriod = \App\Models\SpmbPeriod::where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->where('status', 'aktif')
            ->first();
        
        $data['spmb_period_id'] = $activePeriod ? $activePeriod->id : null;

        // Convert string values to uppercase except password
        foreach ($data as $key => $value) {
            if (is_string($value) && $key !== 'password') {
                $data[$key] = strtoupper($value);
            }
        }

        $casis = Casis::create($data);

        return redirect()->route('admin.casis.index')->with('success', 'Data Calon Siswa (' . $casis->sumber_pendaftaran . ') berhasil ditambahkan dengan Nomor Pendaftaran: ' . $noPendaftaran);
    }

    public function show(Casis $casis)
    {
        $casis->load('jurusan', 'programKeunggulan', 'berkas', 'pembayaran');
        return view('admin.casis.show', compact('casis'));
    }

    public function edit(Casis $casis)
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $programs = ProgramKeunggulan::where('status_aktif', true)->get();
        $ketrampilan = DB::table('master_ketrampilan')->get();
        $hobi = DB::table('master_hobi')->get();
        $cita = DB::table('master_cita')->get();
        $orientasi = DB::table('master_orientasi_ortu')->get();
        $pendidikan = DB::table('master_pendidikan')->get();
        $pekerjaan = DB::table('master_pekerjaan')->get();
        $penghasilan = DB::table('master_penghasilan')->get();

        return view('admin.casis.form', compact('casis', 'jurusans', 'programs', 'ketrampilan', 'hobi', 'cita', 'orientasi', 'pendidikan', 'pekerjaan', 'penghasilan'));
    }

    public function update(Request $request, Casis $casis)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => ['required', 'string', Rule::unique('casis')->ignore($casis->id)],
            'jurusan_id' => 'required|exists:master_jurusan,id',
            'jk' => 'required|in:L,P',
            'no_hp_siswa' => 'required|string',
        ]);

        $data = $request->except(['_token', '_method', 'password']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        foreach ($data as $key => $value) {
            if (is_string($value) && $key !== 'password') {
                $data[$key] = strtoupper($value);
            }
        }

        $casis->update($data);

        return redirect()->route('admin.casis.index')->with('success', 'Data Calon Siswa berhasil diperbarui.');
    }

    public function updateSelection(Request $request, $id)
    {
        $casis = Casis::findOrFail($id);

        $validated = $request->validate([
            'nilai_tpa' => 'nullable|numeric|min:0|max:100',
            'nilai_wawancara' => 'nullable|numeric|min:0|max:100',
            'hasil_psikotes' => 'required|in:Belum Tes,Lulus,Tidak Lulus',
            'catatan_psikotes' => 'nullable|string',
            'tes_tindik' => 'required|in:Belum Periksa,Memenuhi,Tidak Memenuhi',
            'tes_tato' => 'required|in:Belum Periksa,Memenuhi,Tidak Memenuhi',
            'tes_buta_warna' => 'required|in:Belum Periksa,Normal,Parsial,Total',
            'status_kelulusan' => 'required|in:Proses,Lulus,Tidak Lulus,Cadangan',
        ]);

        $casis->update($validated);

        // Send WhatsApp Notification if status is Lulus/Tidak Lulus/Cadangan
        if ($validated['status_kelulusan'] !== 'Proses') {
            WhatsAppService::sendRegistrationSuccess($casis);
        }

        return back()->with('success', 'Hasil seleksi tes psikotes & fisik calon siswa berhasil diperbarui.');
    }

    public function updateDaftarUlang(Request $request, $id)
    {
        $casis = Casis::findOrFail($id);

        $request->validate([
            'status_daftar_ulang' => 'required|in:Belum,Sudah',
            'nominal' => 'nullable|numeric',
            'catatan_admin' => 'nullable|string'
        ]);

        $casis->status_daftar_ulang = $request->status_daftar_ulang;
        $casis->tgl_daftar_ulang = ($request->status_daftar_ulang === 'Sudah') ? now() : null;
        $casis->save();

        if ($request->status_daftar_ulang === 'Sudah') {
            $nominal = $request->nominal ?: ($casis->jurusan ? $casis->jurusan->biaya_daftar_ulang : 1500000);
            Pembayaran::create([
                'casis_id' => $casis->id,
                'order_id' => 'OFFLINE-PAY-' . $casis->id . '-' . time(),
                'tipe_pembayaran' => 'offline',
                'nominal' => $nominal,
                'transaction_status' => 'settlement',
                'settlement_time' => now(),
                'catatan_admin' => $request->catatan_admin ?: 'Verifikasi Kasir Sekolah (Offline)'
            ]);
        }

        return back()->with('success', 'Status daftar ulang & pembayaran offline berhasil diperbarui.');
    }

    public function destroy(Casis $casis)
    {
        $casis->delete();
        return redirect()->route('admin.casis.index')->with('success', 'Data Calon Siswa berhasil dihapus.');
    }

    public function sendReminder($id)
    {
        $casis = Casis::findOrFail($id);
        
        $success = WhatsAppService::sendReminder($casis);

        if ($success) {
            return back()->with('success', 'Notifikasi pengingat WhatsApp berhasil dikirim ke ' . $casis->nama_lengkap);
        } else {
            return back()->with('error', 'Gagal mengirim notifikasi WhatsApp. Pastikan nomor valid atau API Fonnte aktif.');
        }
    }

    public function export(Request $request)
    {
        $query = Casis::with(['jurusan', 'programKeunggulan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sumber')) {
            $query->where('sumber_pendaftaran', $request->sumber);
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        $casis = $query->latest()->get();

        $filename = 'rekap_spmb_smk_bpa_' . date('Y-m-d_His') . '.xls';
        
        $headers = [
            "Content-type" => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($casis) {
            echo "<html><head><meta charset='utf-8'></head><body>";
            echo "<table border='1'>";
            
            $columns = [
                'No', 'No. Pendaftaran', 'Nama Lengkap', 'NISN', 'NIK', 'Jenis Kelamin', 
                'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'RT', 'RW', 'Kecamatan', 'Kab/Kota', 'No HP', 
                'Asal Sekolah', 'Alamat Sekolah', 'Jurusan', 'Program Keunggulan', 
                'Nama Ayah', 'Nama Ibu',
                'Status Verifikasi', 'Status Kelulusan', 'Daftar Ulang', 'Tgl Daftar Ulang', 'Tanggal Daftar'
            ];

            echo "<tr>";
            foreach($columns as $col) {
                echo "<th style='background-color: #16a34a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000; padding: 10px;'>" . $col . "</th>";
            }
            echo "</tr>";

            $no = 1;
            foreach ($casis as $item) {
                echo "<tr>";
                echo "<td style='border: 1px solid #000;'>" . $no++ . "</td>";
                echo "<td style='border: 1px solid #000;'>" . $item->no_pendaftaran . "</td>";
                echo "<td style='border: 1px solid #000;'>" . $item->nama_lengkap . "</td>";
                echo "<td style='border: 1px solid #000; mso-number-format:\"\\@\";'>" . $item->nisn . "</td>";
                echo "<td style='border: 1px solid #000; mso-number-format:\"\\@\";'>" . ($item->nik ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->jk == 'L' ? 'Laki-laki' : 'Perempuan') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->tempat_lahir ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->tgl_lahir ? date('d/m/Y', strtotime($item->tgl_lahir)) : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->agama ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->alamat_siswa ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->rt ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->rw ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->kecamatan ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->kab_kota ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000; mso-number-format:\"\\@\";'>" . ($item->no_hp_siswa ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->nama_sekolah ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->alamat_sekolah ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->jurusan ? $item->jurusan->nama : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->programKeunggulan ? $item->programKeunggulan->nama : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->nama_ayah ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->nama_ibu ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->status_verifikasi ?? 'Belum Diverifikasi') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->status_kelulusan ?? 'Proses') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->status_daftar_ulang ?? 'Belum') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->tgl_daftar_ulang ? date('d/m/Y H:i', strtotime($item->tgl_daftar_ulang)) : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->created_at ? $item->created_at->format('d/m/Y H:i') : '-') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</body></html>";
        };

        return response()->stream($callback, 200, $headers);
    }
}