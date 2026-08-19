<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Jurusan;
use App\Models\ProgramKeunggulan;
use App\Models\SpmbPeriod;
class LandingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $programs = ProgramKeunggulan::where('status_aktif', true)->get();

        $activePeriod = SpmbPeriod::with('tahunAjaran')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->where('status', 'aktif')
            ->first();

        // Registration status
        if ($activePeriod) {
            $registrationStatus = 'open';
            $tanggalMulai = Carbon::parse($activePeriod->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($activePeriod->tanggal_selesai);
        } else {
            $registrationStatus = 'closed';
            $tanggalMulai = null;
            $tanggalSelesai = null;
        }

        // Just fetching some active periods to show in the landing page if needed
        $jadwals = SpmbPeriod::with('tahunAjaran')->where('status', 'aktif')->orderBy('tanggal_mulai')->get();

        return view('landing', compact(
            'settings', 'jurusans', 'programs', 'jadwals',
            'registrationStatus', 'tanggalMulai', 'tanggalSelesai', 'activePeriod'
        ));
    }

    public function previewHasilPengumuman()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        $activePeriod = SpmbPeriod::with('tahunAjaran')
            ->where('status', 'aktif')
            ->first();
            
        $tahun_ajaran = $activePeriod ? $activePeriod->tahunAjaran->nama : '2026/2027';
        $tahun_masuk = explode('/', $tahun_ajaran)[0] ?? '2026';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casis.pdf.hasil-pengumuman', compact('settings', 'tahun_ajaran', 'tahun_masuk'));
        return $pdf->stream('Hasil_Pengumuman.pdf');
    }
}