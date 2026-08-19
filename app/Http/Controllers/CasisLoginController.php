<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Casis;
use App\Models\Setting;
use App\Models\SpmbPeriod;
use App\Models\Pembayaran;
use App\Services\PembayaranService;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class CasisLoginController extends Controller
{
    public function showLoginForm()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('auth.casis-login', compact('settings'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $casis = Casis::where('nisn', $request->nisn)->first();

        if ($casis && Hash::check($request->password, $casis->password)) {
            session(['casis_id' => $casis->id]);
            return redirect()->route('casis.dashboard');
        }

        return back()->withErrors(['nisn' => 'NISN atau Password salah.']);
    }

    public function dashboard()
    {
        $casis = $this->getCasisOrRedirect();
        if (!$casis) {
            return redirect()->route('casis.login');
        }

        $settings = Setting::all()->pluck('value', 'key');
        $jadwals = SpmbPeriod::with('tahunAjaran')->where('status', 'aktif')->orderBy('tanggal_mulai')->get();
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';

        $rincianBiaya = collect();
        if ($casis->jurusan) {
            $rincianBiaya = $casis->jurusan->biayas()->whereIn('jenis_biaya', ['Daftar Ulang', 'SPP'])->get();
        }

        // Get jadwal_daftar_ulang from settings, default to today if not set
        $jadwalDaftarUlang = isset($settings['jadwal_daftar_ulang']) ? \Carbon\Carbon::parse($settings['jadwal_daftar_ulang'])->startOfDay() : \Carbon\Carbon::now()->startOfDay();
        $isJadwalDaftarUlang = \Carbon\Carbon::now()->startOfDay()->greaterThanOrEqualTo($jadwalDaftarUlang);

        // Ensure active re-enrollment billing exists if student is verified/accepted
        $tagihanDaftarUlang = null;
        if ($casis->isVerified()) {
            $tagihanDaftarUlang = PembayaranService::createOrGetTagihanDaftarUlang($casis, false);
        } else {
            $tagihanDaftarUlang = $casis->pembayaranDaftarUlang;
        }

        $historyPembayaran = Pembayaran::where('casis_id', $casis->id)->orderBy('created_at', 'desc')->get();

        return view('casis.dashboard', compact(
            'casis', 'settings', 'jadwals', 'tahun_ajaran', 
            'isJadwalDaftarUlang', 'jadwalDaftarUlang', 
            'tagihanDaftarUlang', 'historyPembayaran', 'rincianBiaya'
        ));
    }

    public function printKartu()
    {
        $casis = $this->getCasisOrRedirect();
        if (!$casis) {
            return redirect()->route('casis.login');
        }

        $settings = Setting::all()->pluck('value', 'key');
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';

        $pdf = Pdf::loadView('casis.pdf.kartu', compact('casis', 'settings', 'tahun_ajaran'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Kartu_Bukti_Pendaftaran_' . $casis->no_pendaftaran . '.pdf');
    }

    public function printPengumuman()
    {
        $casis = $this->getCasisOrRedirect();
        if (!$casis) {
            return redirect()->route('casis.login');
        }

        if (!$casis->isVerified()) {
            return redirect()->route('casis.dashboard')->with('error', 'Surat Pengumuman belum tersedia.');
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';
        // Extract the end year for gelombang (e.g., 2026/2027 -> 2026)
        $tahun_masuk = explode('/', $tahun_ajaran)[0] ?? '2026';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casis.pdf.pengumuman', compact('casis', 'settings', 'tahun_ajaran', 'tahun_masuk'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('Surat_Pengumuman_' . $casis->no_pendaftaran . '.pdf');
    }

    public function printKwitansi()
    {
        $casis = $this->getCasisOrRedirect();
        if (!$casis) {
            return redirect()->route('casis.login');
        }

        $tagihanDaftarUlang = $casis->pembayaranDaftarUlang;
        
        if (!$tagihanDaftarUlang || !$tagihanDaftarUlang->isSettlement()) {
            return redirect()->route('casis.dashboard')->with('error', 'Kwitansi belum tersedia. Silakan lunasi pembayaran terlebih dahulu.');
        }

        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casis.pdf.kwitansi', compact('casis', 'tagihanDaftarUlang', 'settings', 'tahun_ajaran'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('Kwitansi_Pembayaran_' . $casis->no_pendaftaran . '.pdf');
    }

    public function printFormulir()
    {
        $casis = $this->getCasisOrRedirect();
        if (!$casis) {
            return redirect()->route('casis.login');
        }

        $settings = Setting::all()->pluck('value', 'key');
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';

        $pdf = Pdf::loadView('casis.pdf.formulir', compact('casis', 'settings', 'tahun_ajaran'));
        return $pdf->download('Formulir_SPMB_' . $casis->no_pendaftaran . '.pdf');
    }

    private function getCasisOrRedirect()
    {
        if (!session('casis_id')) {
            return null;
        }
        return Casis::with(['jurusan', 'programKeunggulan', 'berkas', 'pembayaran', 'spmbPeriod.tahunAjaran'])->find(session('casis_id'));
    }

    public function logout()
    {
        session()->forget('casis_id');
        return redirect()->route('casis.login');
    }

    public function uploadBerkas(Request $request)
    {
        $casisId = session('casis_id');
        if (!$casisId) {
            return redirect()->route('casis.login');
        }

        $request->validate([
            'jenis_berkas' => 'required|string',
            'file' => 'required|file|max:2048',
        ]);

        $file = $request->file('file');
        $jenisBerkas = $request->jenis_berkas;

        $path = $file->store('berkas', 'public');

        \App\Models\CasisBerkas::create([
            'casis_id' => $casisId,
            'nama_berkas' => $jenisBerkas,
            'path' => $path,
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'Dokumen (' . $jenisBerkas . ') berhasil diunggah.');
    }
}
