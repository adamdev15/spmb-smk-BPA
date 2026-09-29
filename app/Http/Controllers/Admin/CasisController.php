<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casis;
use App\Models\Jurusan;
use App\Models\ProgramKeunggulan;
use App\Models\SpmbPeriod;
use App\Models\TahunAjaran;
use App\Models\Pembayaran;
use App\Services\WhatsAppService;
use App\Services\PembayaranService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CasisController extends Controller
{
    public function index(Request $request)
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $tahunAjarans = TahunAjaran::with('spmbPeriods')->orderBy('nama', 'desc')->get();
        $activePeriod = SpmbPeriod::where('status', 'aktif')->first();

        $selectedTahunAjaranId = $request->input('tahun_ajaran_id');
        $selectedSpmbPeriodId = $request->input('spmb_period_id');

        if (!$selectedTahunAjaranId && !$selectedSpmbPeriodId && $activePeriod) {
            $selectedTahunAjaranId = $activePeriod->tahun_ajaran_id;
            $selectedSpmbPeriodId = $activePeriod->id;
        }

        $query = Casis::with(['jurusan', 'programKeunggulan', 'spmbPeriod.tahunAjaran', 'pembayaranTerakhir']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        // Sumber Pendaftaran Filter
        if ($request->filled('sumber')) {
            $query->where('sumber_pendaftaran', $request->sumber);
        }

        // Status Verifikasi Filter
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        // Status Kelulusan Filter
        if ($request->filled('kelulusan')) {
            $query->where('status_kelulusan', $request->kelulusan);
        }

        // Status Daftar Ulang Filter
        if ($request->filled('daftar_ulang')) {
            $query->where('status_daftar_ulang', $request->daftar_ulang);
        }

        // Jurusan Filter
        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        // Period / Academic Year Filter
        if ($selectedSpmbPeriodId) {
            $query->where('spmb_period_id', $selectedSpmbPeriodId);
        } elseif ($selectedTahunAjaranId) {
            $query->whereHas('spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
            });
        }

        $casis = $query->latest()->paginate(20)->withQueryString();

        return view('admin.casis.index', compact(
            'casis', 'jurusans', 'tahunAjarans', 'activePeriod', 
            'selectedTahunAjaranId', 'selectedSpmbPeriodId'
        ));
    }

    public function create()
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $programs = ProgramKeunggulan::where('status_aktif', true)->get();
        $periods = SpmbPeriod::with('tahunAjaran')->where('status', 'aktif')->get();

        $ketrampilan = DB::table('master_ketrampilan')->get();
        $hobi = DB::table('master_hobi')->get();
        $cita = DB::table('master_cita')->get();
        $pendidikan = DB::table('master_pendidikan')->get();
        $pekerjaan = DB::table('master_pekerjaan')->get();
        $penghasilan = DB::table('master_penghasilan')->get();
        $orientasi = DB::table('master_orientasi_ortu')->get();

        return view('admin.casis.form', compact(
            'jurusans', 'programs', 'periods', 'ketrampilan', 'hobi', 
            'cita', 'pendidikan', 'pekerjaan', 'penghasilan', 'orientasi'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|unique:casis,nisn',
            'nik' => 'nullable|string|size:16|unique:casis,nik',
            'no_kk' => 'nullable|string',
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'agama' => 'required|string',
            'no_hp_siswa' => 'nullable|string',
            'alamat_siswa' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
            'id_provinsi' => 'nullable|string',
            'id_kabupaten' => 'nullable|string',
            'id_kecamatan' => 'nullable|string',
            'id_kelurahan' => 'nullable|string',
            'nama_sekolah' => 'nullable|string',
            'alamat_sekolah' => 'nullable|string',
            'jurusan_id' => 'required|exists:master_jurusan,id',
            'program_keunggulan_id' => 'nullable|exists:program_keunggulan,id',
            'spmb_period_id' => 'nullable|exists:spmb_periods,id',
            'sumber_pendaftaran' => 'required|in:online,offline',
            'password' => 'nullable|string|min:6',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fc_kk' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'fc_akta' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'fc_ijazah' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'nik.unique' => 'NIK anda telah terdaftar, hubungi admin',
            'nisn.unique' => 'NISN anda telah terdaftar, hubungi admin'
        ]);

        $year = date('Y');
        $count = Casis::whereYear('created_at', $year)->count() + 1;
        $no_pendaftaran = 'BPA-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $validated['no_pendaftaran'] = $no_pendaftaran;
        $validated['password'] = Hash::make($request->password ?: ($request->nisn ?: '123456'));
        $validated['status_verifikasi'] = 'Belum Diverifikasi';
        $validated['status_kelulusan'] = 'Proses';
        $validated['status_daftar_ulang'] = 'Belum';
        
        if (empty($validated['spmb_period_id'])) {
            $activePeriod = SpmbPeriod::where('status', 'aktif')->first();
            if ($activePeriod) {
                $validated['spmb_period_id'] = $activePeriod->id;
            }
        }

        $casis = Casis::create($validated);

        if ($request->hasFile('pas_foto')) {
            $path = $request->file('pas_foto')->store('berkas/foto', 'public');
            $casis->berkas()->create([
                'nama_berkas' => 'Pas Foto 3x4',
                'path' => $path,
                'extension' => $request->file('pas_foto')->getClientOriginalExtension(),
                'size' => $request->file('pas_foto')->getSize()
            ]);
        }

        $otherFiles = [
            'fc_kk' => 'FC Kartu Keluarga',
            'fc_akta' => 'FC Akta Kelahiran',
            'fc_ijazah' => 'FC Ijazah / SKL'
        ];

        foreach ($otherFiles as $fileKey => $docName) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store('berkas/dokumen', 'public');
                $casis->berkas()->create([
                    'nama_berkas' => $docName,
                    'path' => $path,
                    'extension' => $request->file($fileKey)->getClientOriginalExtension(),
                    'size' => $request->file($fileKey)->getSize()
                ]);
            }
        }

        return redirect()->route('admin.casis.index')->with('success', 'Calon Siswa baru berhasil ditambahkan.');
    }

    public function show(Casis $casis)
    {
        $casis->load(['jurusan', 'programKeunggulan', 'spmbPeriod.tahunAjaran', 'pembayaran' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'berkas']);
        
        $pembayaran = $casis->pembayaranTerakhir;
        return view('admin.casis.show', compact('casis', 'pembayaran'));
    }

    public function edit(Casis $casis)
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $programs = ProgramKeunggulan::where('status_aktif', true)->get();
        $periods = SpmbPeriod::with('tahunAjaran')->get();

        $ketrampilan = DB::table('master_ketrampilan')->get();
        $hobi = DB::table('master_hobi')->get();
        $cita = DB::table('master_cita')->get();
        $pendidikan = DB::table('master_pendidikan')->get();
        $pekerjaan = DB::table('master_pekerjaan')->get();
        $penghasilan = DB::table('master_penghasilan')->get();
        $orientasi = DB::table('master_orientasi_ortu')->get();

        return view('admin.casis.form', compact(
            'casis', 'jurusans', 'programs', 'periods', 'ketrampilan', 
            'hobi', 'cita', 'pendidikan', 'pekerjaan', 'penghasilan', 'orientasi'
        ));
    }

    public function update(Request $request, Casis $casis)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string',
'nik' =>    'nullable|string|size:16',
            'no_kk' => 'nullable|string',
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'agama' => 'required|string',
            'no_hp_siswa' => 'nullable|string',
            'alamat_siswa' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
            'id_provinsi' => 'nullable|string',
            'id_kabupaten' => 'nullable|string',
            'id_kecamatan' => 'nullable|string',
            'id_kelurahan' => 'nullable|string',
            'nama_sekolah' => 'nullable|string',
            'alamat_sekolah' => 'nullable|string',
            'jurusan_id' => 'required|exists:master_jurusan,id',
            'program_keunggulan_id' => 'nullable|exists:program_keunggulan,id',
            'spmb_period_id' => 'nullable|exists:spmb_periods,id',
            'status_verifikasi' => 'nullable|in:Belum Diverifikasi,Diverifikasi',
            'status_kelulusan' => 'nullable|in:Proses,Lulus,Tidak Lulus,Cadangan',
            'status_daftar_ulang' => 'nullable|in:Belum,Sudah',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fc_kk' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'fc_akta' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'fc_ijazah' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $casis->update($validated);

        if ($request->hasFile('pas_foto')) {
            $path = $request->file('pas_foto')->store('berkas/foto', 'public');
            $casis->berkas()->updateOrCreate(
                ['nama_berkas' => 'Pas Foto 3x4'],
                [
                    'path' => $path,
                    'extension' => $request->file('pas_foto')->getClientOriginalExtension(),
                    'size' => $request->file('pas_foto')->getSize()
                ]
            );
        }

        $otherFiles = [
            'fc_kk' => 'FC Kartu Keluarga',
            'fc_akta' => 'FC Akta Kelahiran',
            'fc_ijazah' => 'FC Ijazah / SKL'
        ];

        foreach ($otherFiles as $fileKey => $docName) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store('berkas/dokumen', 'public');
                $casis->berkas()->updateOrCreate(
                    ['nama_berkas' => $docName],
                    [
                        'path' => $path,
                        'extension' => $request->file($fileKey)->getClientOriginalExtension(),
                        'size' => $request->file($fileKey)->getSize()
                    ]
                );
            }
        }

        // If verified or graduated, automatically issue re-enrollment billing
        if ($casis->isVerified()) {
            PembayaranService::createOrGetTagihanDaftarUlang($casis, true);
        }

        return redirect()->route('admin.casis.show', $casis->id)->with('success', 'Data Calon Siswa berhasil diperbarui.');
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

        // If Lulus, automatically create re-enrollment billing & notify WA
        if ($validated['status_kelulusan'] === 'Lulus') {
            PembayaranService::createOrGetTagihanDaftarUlang($casis, true);
        } elseif ($validated['status_kelulusan'] !== 'Proses') {
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
            'payment_type' => 'nullable|string',
            'nomor_referensi' => 'nullable|string',
            'catatan_admin' => 'nullable|string',
            'paid_at' => 'nullable|date'
        ]);

        if ($request->status_daftar_ulang === 'Sudah') {
            PembayaranService::processManualPayment($casis, [
                'nominal' => $request->nominal,
                'payment_type' => $request->payment_type ?: 'Tunai',
                'nomor_referensi' => $request->nomor_referensi,
                'catatan_admin' => $request->catatan_admin ?: 'Pembayaran Manual Kasir Sekolah (Offline)',
                'paid_at' => $request->paid_at ?: now(),
                'transaction_status' => 'settlement'
            ], auth()->user());
        } else {
            $casis->status_daftar_ulang = 'Belum';
            $casis->tgl_daftar_ulang = null;
            $casis->save();

            // Set payment back to pending if exists
            $p = $casis->pembayaranDaftarUlang;
            if ($p) {
                $p->update(['transaction_status' => 'pending', 'settlement_time' => null]);
            }
        }

        return back()->with('success', 'Status daftar ulang & pembayaran berhasil diperbarui.');
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

    public function updateKelulusan(Request $request, $id)
    {
        $casis = Casis::findOrFail($id);
        
        $request->validate([
            'status_kelulusan' => 'required|in:Proses,Lulus,Tidak Lulus,Cadangan'
        ]);

        $casis->update([
            'status_kelulusan' => $request->status_kelulusan
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status kelulusan berhasil diperbarui.'
            ]);
        }

        return redirect()->back()->with('success', 'Status kelulusan berhasil diperbarui.');
    }

    public function bulkUpdateKelulusan(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:casis,id',
            'status_kelulusan' => 'required|in:Proses,Lulus,Tidak Lulus,Cadangan'
        ]);

        Casis::whereIn('id', $request->ids)->update([
            'status_kelulusan' => $request->status_kelulusan
        ]);

        return redirect()->route('admin.casis.index')->with('success', 'Status kelulusan berhasil diperbarui untuk ' . count($request->ids) . ' siswa.');
    }

    public function export(Request $request)
    {
        $query = Casis::with(['jurusan', 'programKeunggulan', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan']);

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
                'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'RT', 'RW', 'Kelurahan', 'Kecamatan', 'Kab/Kota', 'Provinsi', 'No HP', 
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
                echo "<td style='border: 1px solid #000;'>" . ($item->kelurahan?->nama_desa_kel ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->kecamatan?->nama_kec ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->kabupaten?->nama_kabkota ?? '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->provinsi?->nama_provinsi ?? '-') . "</td>";
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

    public function printKartu($id)
    {
        $casis = Casis::with(['jurusan', 'spmbPeriod.tahunAjaran', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])->findOrFail($id);
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casis.pdf.kartu', compact('casis', 'settings', 'tahun_ajaran'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Kartu_Bukti_Pendaftaran_' . $casis->no_pendaftaran . '.pdf');
    }

    public function printFormulir($id)
    {
        $casis = Casis::with(['jurusan', 'spmbPeriod.tahunAjaran', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])->findOrFail($id);
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casis.pdf.formulir', compact('casis', 'settings', 'tahun_ajaran'));
        return $pdf->download('Formulir_SPMB_' . $casis->no_pendaftaran . '.pdf');
    }

    public function printPengumuman($id)
    {
        $casis = Casis::with(['jurusan', 'spmbPeriod.tahunAjaran', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])->findOrFail($id);
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';
        $tahun_masuk = explode('/', $tahun_ajaran)[0] ?? '2026';
        
        $tagihanDaftarUlang = \App\Services\PembayaranService::createOrGetTagihanDaftarUlang($casis);
        $rincianBiaya = \App\Models\Biaya::whereHas('jurusans', function($query) use ($casis) {
            $query->where('jurusan_id', $casis->jurusan_id);
        })->whereIn('jenis_biaya', ['Daftar Ulang', 'SPP'])->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casis.pdf.pengumuman', compact('casis', 'tagihanDaftarUlang', 'settings', 'rincianBiaya', 'tahun_ajaran', 'tahun_masuk'));
        return $pdf->download('Surat_Pengumuman_' . $casis->no_pendaftaran . '.pdf');
    }
}