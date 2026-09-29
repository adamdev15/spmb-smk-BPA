<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casis;
use App\Models\Jurusan;
use App\Models\Pembayaran;
use App\Services\PembayaranService;
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

        // Automatically create/ensure re-enrollment billing and trigger WA
        PembayaranService::createOrGetTagihanDaftarUlang($casis, true);

        return back()->with('success', 'Data siswa berhasil diverifikasi & tagihan daftar ulang otomatis diterbitkan.');
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

        $casisList = Casis::whereIn('id', $request->casis_ids)->get();

        foreach ($casisList as $casis) {
            $casis->update(['status_verifikasi' => 'Diverifikasi']);
            // Automatically create/ensure re-enrollment billing and trigger WA
            PembayaranService::createOrGetTagihanDaftarUlang($casis, true);
        }

        return back()->with('success', count($request->casis_ids) . ' data siswa berhasil diverifikasi & tagihan daftar ulang diterbitkan.');
    }

    public function chartData(Request $request)
    {
        $type = $request->input('type', 'trend'); // 'trend' or 'payment'
        $filter = $request->input('filter', '7_days'); // '7_days', '1_month', 'custom'
        
        // Get base params
        $selectedTahunAjaranId = $request->input('tahun_ajaran_id');
        $selectedSpmbPeriodId = $request->input('spmb_period_id');
        
        if (!$selectedTahunAjaranId && !$selectedSpmbPeriodId) {
            $activePeriod = \App\Models\SpmbPeriod::where('status', 'aktif')->first();
            if ($activePeriod) {
                $selectedTahunAjaranId = $activePeriod->tahun_ajaran_id;
                $selectedSpmbPeriodId = $activePeriod->id;
            }
        }
        
        $startDate = \Carbon\Carbon::now();
        $endDate = \Carbon\Carbon::now();
        
        if ($filter === '7_days') {
            $startDate = \Carbon\Carbon::now()->subDays(6);
        } elseif ($filter === '1_month') {
            $startDate = \Carbon\Carbon::now()->subDays(29);
        } elseif ($filter === 'custom') {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
            }
        }
        
        $data = [];
        $labels = [];
        
        // Loop through dates
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            
            if ($type === 'trend') {
                $query = Casis::query()->whereDate('created_at', $dateStr);
                if ($selectedSpmbPeriodId) {
                    $query->where('spmb_period_id', $selectedSpmbPeriodId);
                } elseif ($selectedTahunAjaranId) {
                    $query->whereHas('spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                        $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
                    });
                }
                $labels[] = $date->format('d M');
                $data[] = $query->count();
            } else {
                $query = \App\Models\Pembayaran::where('transaction_status', 'settlement')
                    ->whereDate('settlement_time', $dateStr);
                
                if ($selectedSpmbPeriodId) {
                    $query->whereHas('casis', function($q) use ($selectedSpmbPeriodId) {
                        $q->where('spmb_period_id', $selectedSpmbPeriodId);
                    });
                } elseif ($selectedTahunAjaranId) {
                    $query->whereHas('casis.spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                        $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
                    });
                }
                
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $labels[] = $date->format('d') . ' ' . $months[$date->format('n') - 1] . ' ' . $date->format('Y');
                $data[] = $query->count();
            }
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}