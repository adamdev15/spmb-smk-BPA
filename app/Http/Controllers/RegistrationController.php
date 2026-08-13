<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Casis;
use App\Models\Jurusan;
use App\Models\ProgramKeunggulan;
use App\Models\SpmbPeriod;
use App\Models\Setting;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $programs = ProgramKeunggulan::where('status_aktif', true)->get();

        $ketrampilan = DB::table('master_ketrampilan')->get();
        $hobi = DB::table('master_hobi')->get();
        $cita = DB::table('master_cita')->get();
        $pendidikan = DB::table('master_pendidikan')->get();
        $pekerjaan = DB::table('master_pekerjaan')->get();
        
        $pekerjaan_ayah = $pekerjaan->filter(fn($p) => in_array($p->target, ['ayah', 'semua']));
        $pekerjaan_ibu = $pekerjaan->filter(fn($p) => in_array($p->target, ['ibu', 'semua']));
        $pekerjaan_wali = $pekerjaan->filter(fn($p) => in_array($p->target, ['wali', 'semua']));

        $penghasilan = DB::table('master_penghasilan')->get();
        $orientasi = DB::table('master_orientasi_ortu')->get();

        $settings = Setting::all()->pluck('value', 'key');
        $activePeriod = SpmbPeriod::with('tahunAjaran')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->where('status', 'aktif')
            ->first();

        return view('registration.wizard', compact(
            'jurusans', 'programs', 'ketrampilan', 'hobi', 'cita', 
            'pendidikan', 'pekerjaan_ayah', 'pekerjaan_ibu', 'pekerjaan_wali', 
            'penghasilan', 'orientasi', 'settings', 'activePeriod'
        ));
    }

    public function store(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key');
        $now = Carbon::now();

        $activePeriod = SpmbPeriod::where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->where('status', 'aktif')
            ->first();

        if (!$activePeriod) {
            return response()->json([
                'success' => false,
                'status' => 'closed',
                'message' => 'Pendaftaran SPMB saat ini sedang ditutup atau belum ada gelombang yang aktif.'
            ], 403);
        }

        $msg = [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'exists' => ':attribute tidak valid.'
        ];

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|unique:casis,nisn',
            'nik' => 'required|string|size:16|unique:casis,nik',
            'agama' => 'required|string',
            'no_hp_siswa' => 'required|string',
            'jurusan_id' => 'required|exists:master_jurusan,id',
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'rt' => 'required|string',
            'rw' => 'required|string',
            'kecamatan' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_siswa' => 'required|string',
            'nama_sekolah' => 'required|string',
            'alamat_sekolah' => 'required|string',
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fc_kk' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'fc_akta' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'fc_ijazah' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048'
        ], $msg);

        // Uppercase inputs except password & files
        $input = $request->all();
        array_walk_recursive($input, function (&$item, $key) {
            if (is_string($item) && !in_array($key, ['pas_foto', 'fc_kk', 'fc_akta', 'fc_ijazah', 'pas_foto_base64', 'password', '_token'])) {
                $item = strtoupper($item);
            }
        });
        $request->merge($input);

        DB::beginTransaction();
        try {
            $year = date('Y');
            $dd = date('d');
            $rawPassword = $request->nisn;

            // Generate Sequential No Pendaftaran: BPA-2026-0001
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

            $data = [
                'spmb_period_id' => $activePeriod->id,
                'no_pendaftaran' => $noPendaftaran,
                'sumber_pendaftaran' => 'online',
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'jk' => $request->jk,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'anak_ke' => $request->anak_ke,
                'jml_saudara' => $request->jml_saudara,
                'agama' => $request->agama,
                'status_keluarga' => $request->status_keluarga,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'kecamatan' => $request->kecamatan,
                'kab_kota' => $request->kab_kota,
                'alamat_siswa' => $request->alamat_siswa,
                'no_hp_siswa' => $request->no_hp_siswa,
                'jurusan_id' => $request->jurusan_id,
                'program_keunggulan_id' => $request->program_keunggulan_id ?: null,
                'ketrampilan_id' => $request->ketrampilan_id ?: null,
                'hobi_id' => $request->hobi_id ?: null,
                'cita_id' => $request->cita_id ?: null,

                // Ayah
                'nama_ayah' => $request->nama_ayah,
                'nik_ayah' => $request->nik_ayah,
                'tgl_lahir_ayah' => $request->tgl_lahir_ayah,
                'alamat_ayah' => $request->alamat_ayah,
                'pendidikan_ayah_id' => $request->pendidikan_ayah_id ?: null,
                'pekerjaan_ayah_id' => $request->pekerjaan_ayah_id ?: null,
                'penghasilan_ayah_id' => $request->penghasilan_ayah_id ?: null,
                'no_hp_ayah' => $request->no_hp_ayah,
                'status_ayah' => $request->status_ayah,

                // Ibu
                'nama_ibu' => $request->nama_ibu,
                'nik_ibu' => $request->nik_ibu,
                'tgl_lahir_ibu' => $request->tgl_lahir_ibu,
                'alamat_ibu' => $request->alamat_ibu,
                'pendidikan_ibu_id' => $request->pendidikan_ibu_id ?: null,
                'pekerjaan_ibu_id' => $request->pekerjaan_ibu_id ?: null,
                'penghasilan_ibu_id' => $request->penghasilan_ibu_id ?: null,
                'no_hp_ibu' => $request->no_hp_ibu,
                'status_ibu' => $request->status_ibu,

                // Wali
                'nama_wali' => $request->nama_wali,
                'nik_wali' => $request->nik_wali,
                'tgl_lahir_wali' => $request->tgl_lahir_wali,
                'alamat_wali' => $request->alamat_wali,
                'pendidikan_wali_id' => $request->pendidikan_wali_id ?: null,
                'pekerjaan_wali_id' => $request->pekerjaan_wali_id ?: null,
                'penghasilan_wali_id' => $request->penghasilan_wali_id ?: null,
                'no_hp_wali' => $request->no_hp_wali,
                'status_wali' => $request->status_wali,
                'orientasi_ortu_id' => $request->orientasi_ortu_id ?: null,

                // Sekolah Asal
                'nama_sekolah' => $request->nama_sekolah,
                'jenis_sekolah' => $request->jenis_sekolah ?: 'SMP',
                'status_sekolah' => $request->status_sekolah ?: 'NEGERI',
                'akreditasi_sekolah' => $request->akreditasi_sekolah ?: 'A',

                // System
                'password' => Hash::make($rawPassword),
                'status_pendaftaran' => 'Submitted',
                'status_verifikasi' => 'Belum Diverifikasi',
                'status_kelulusan' => 'Proses',
                'status_daftar_ulang' => 'Belum'
            ];

            $casis = Casis::create($data);

            // Handle Pas Foto Base64 (Kamera) or Upload
            if ($request->filled('pas_foto_base64')) {
                $image_parts = explode(";base64,", $request->pas_foto_base64);
                if (count($image_parts) == 2) {
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'berkas/foto/' . uniqid() . '.' . $image_type;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
                    
                    $casis->berkas()->create([
                        'nama_berkas' => 'Pas Foto 3x4',
                        'path' => $fileName,
                        'extension' => $image_type,
                        'size' => strlen($image_base64)
                    ]);
                }
            } elseif ($request->hasFile('pas_foto')) {
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

            // Send WhatsApp Notification
            WhatsAppService::sendRegistrationSuccess($casis);

            DB::commit();

            // Auto-login session for candidate student
            session(['casis_id' => $casis->id]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dikirim!',
                'redirect' => route('casis.dashboard')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', \Illuminate\Support\Arr::flatten($e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Registration Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}