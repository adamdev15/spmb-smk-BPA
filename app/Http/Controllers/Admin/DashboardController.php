<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casis;
use App\Models\Jurusan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjarans = \App\Models\TahunAjaran::with('spmbPeriods')->orderBy('nama', 'desc')->get();
        $activePeriod = \App\Models\SpmbPeriod::where('status', 'aktif')->first();

        $selectedTahunAjaranId = $request->input('tahun_ajaran_id');
        $selectedSpmbPeriodId = $request->input('spmb_period_id');

        if (!$selectedTahunAjaranId && !$selectedSpmbPeriodId && $activePeriod) {
            $selectedTahunAjaranId = $activePeriod->tahun_ajaran_id;
            $selectedSpmbPeriodId = $activePeriod->id;
        }

        $baseQuery = Casis::query();
        if ($selectedSpmbPeriodId) {
            $baseQuery->where('spmb_period_id', $selectedSpmbPeriodId);
        } elseif ($selectedTahunAjaranId) {
            $baseQuery->whereHas('spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
            });
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'online' => (clone $baseQuery)->where('sumber_pendaftaran', 'online')->count(),
            'offline' => (clone $baseQuery)->where('sumber_pendaftaran', 'offline')->count(),
            'lulus' => (clone $baseQuery)->where('status_kelulusan', 'Lulus')->count(),
            'sudah_daftar_ulang' => (clone $baseQuery)->where('status_daftar_ulang', 'Sudah')->count(),
            'belum_daftar_ulang' => (clone $baseQuery)->where('status_kelulusan', 'Lulus')->where('status_daftar_ulang', 'Belum')->count(),
            'total_pembayaran' => Pembayaran::whereHas('casis', function($q) use ($selectedSpmbPeriodId, $selectedTahunAjaranId) {
                if ($selectedSpmbPeriodId) {
                    $q->where('spmb_period_id', $selectedSpmbPeriodId);
                } elseif ($selectedTahunAjaranId) {
                    $q->whereHas('spmbPeriod', function($q2) use ($selectedTahunAjaranId) {
                        $q2->where('tahun_ajaran_id', $selectedTahunAjaranId);
                    });
                }
            })->where('transaction_status', 'settlement')->sum('nominal'),
        ];

        $jurusans = Jurusan::where('status_aktif', true)->get();
        $casis = (clone $baseQuery)->with(['jurusan', 'programKeunggulan'])->latest()->paginate(10);

        // Chart Data - Pendaftar per Jurusan
        $jurusanStats = (clone $baseQuery)->select('jurusan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('jurusan_id')
            ->pluck('total', 'jurusan_id');

        $jurusanChartData = [
            'labels' => $jurusans->pluck('kode')->toArray(),
            'data' => $jurusans->map(fn($j) => $jurusanStats[$j->id] ?? 0)->toArray(),
            're_enrolled' => $jurusans->map(fn($j) => $j->jumlah_daftar_ulang)->toArray(),
            'quota' => $jurusans->map(fn($j) => $j->kuota)->toArray(),
        ];

        // Chart Data - Gender Distribution
        $genderPieData = [
            'labels' => ['Laki-Laki', 'Perempuan'],
            'data' => [
                (clone $baseQuery)->where('jk', 'L')->count(),
                (clone $baseQuery)->where('jk', 'P')->count(),
            ],
        ];

        // Last 7 days registration trends & payments
        $last7Days = [];
        $last7DaysPayments = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $last7Days[$date] = (clone $baseQuery)->whereDate('created_at', $date)->count();
            
            // Payment Data
            $paymentQuery = \App\Models\Pembayaran::where('transaction_status', 'settlement')
                ->whereDate('settlement_time', $date);
            
            if ($selectedSpmbPeriodId) {
                $paymentQuery->whereHas('casis', function($q) use ($selectedSpmbPeriodId) {
                    $q->where('spmb_period_id', $selectedSpmbPeriodId);
                });
            } elseif ($selectedTahunAjaranId) {
                $paymentQuery->whereHas('casis.spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                    $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
                });
            }
            
            $last7DaysPayments[$date] = $paymentQuery->count();
        }

        return view('admin.dashboard', compact(
            'stats',
            'jurusans',
            'casis',
            'jurusanChartData',
            'genderPieData',
            'last7Days',
            'last7DaysPayments',
            'tahunAjarans',
            'selectedTahunAjaranId',
            'selectedSpmbPeriodId'
        ));
    }

    public function verify(Request $request, $id)
    {
        $casis = Casis::findOrFail($id);
        $casis->update(['status_verifikasi' => 'Diverifikasi']);
        return back()->with('success', 'Data siswa berhasil diverifikasi.');
    }

    public function unverify(Request $request, $id)
    {
        $casis = Casis::findOrFail($id);
        $casis->update(['status_verifikasi' => 'Belum Diverifikasi']);
        return back()->with('success', 'Verifikasi data siswa berhasil dibatalkan.');
    }

    public function bulkVerify(Request $request)
    {
        $request->validate([
            'casis_ids' => 'required|array',
            'casis_ids.*' => 'exists:casis,id'
        ]);

        Casis::whereIn('id', $request->casis_ids)->update(['status_verifikasi' => 'Diverifikasi']);

        return back()->with('success', count($request->casis_ids) . ' data siswa berhasil diverifikasi.');
    }
}