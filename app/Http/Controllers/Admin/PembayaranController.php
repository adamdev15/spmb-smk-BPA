<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casis;
use App\Models\Pembayaran;
use App\Models\TahunAjaran;
use App\Models\SpmbPeriod;
use App\Models\Jurusan;
use App\Services\WhatsAppService;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Get filter data
        $jurusans = Jurusan::orderBy('nama', 'asc')->get();
        $tahunAjarans = TahunAjaran::with('spmbPeriods')->orderBy('nama', 'desc')->get();
        $activePeriod = SpmbPeriod::where('status', 'aktif')->first();

        // Defaults
        $selectedTahunAjaranId = $request->input('tahun_ajaran_id');
        $selectedSpmbPeriodId = $request->input('spmb_period_id');

        if (!$selectedTahunAjaranId && !$selectedSpmbPeriodId && $activePeriod) {
            $selectedTahunAjaranId = $activePeriod->tahun_ajaran_id;
            $selectedSpmbPeriodId = $activePeriod->id;
        }

        $query = Casis::with(['pembayaranTerakhir', 'jurusan', 'programKeunggulan', 'spmbPeriod'])
            ->where('status_verifikasi', 'Diverifikasi')
            ->has('pembayaran');

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('pembayaranTerakhir', function ($q2) use ($search) {
                      $q2->where('order_id', 'like', "%{$search}%");
                  });
            });
        }

        // Apply Filters
        if ($request->filled('status_daftar_ulang')) {
            $query->where('status_daftar_ulang', $request->status_daftar_ulang);
        }

        if ($request->filled('tipe_pembayaran')) {
            $query->whereHas('pembayaranTerakhir', function ($q) use ($request) {
                $q->where('tipe_pembayaran', $request->tipe_pembayaran);
            });
        }

        if ($request->filled('status_transaksi')) {
            $query->whereHas('pembayaranTerakhir', function ($q) use ($request) {
                $q->where('transaction_status', $request->status_transaksi);
            });
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($selectedSpmbPeriodId) {
            $query->where('spmb_period_id', $selectedSpmbPeriodId);
        } elseif ($selectedTahunAjaranId) {
            $query->whereHas('spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
            });
        }

        $casis = $query->latest()->paginate(20)->withQueryString();

        return view('admin.pembayaran.index', compact(
            'casis', 'jurusans', 'tahunAjarans', 'activePeriod',
            'selectedTahunAjaranId', 'selectedSpmbPeriodId'
        ));
    }

    public function show($id)
    {
        $casis = Casis::with(['pembayaranTerakhir', 'jurusan', 'programKeunggulan', 'spmbPeriod.tahunAjaran'])
            ->findOrFail($id);
            
        return view('admin.pembayaran.show', compact('casis'));
    }

    public function reminder($id)
    {
        $casis = Casis::findOrFail($id);
        
        if ($casis->status_daftar_ulang === 'Sudah') {
            return back()->with('error', 'Siswa sudah melakukan daftar ulang.');
        }
        
        if (!$casis->no_hp_siswa) {
            return back()->with('error', 'Siswa tidak memiliki nomor WhatsApp.');
        }

        $success = WhatsAppService::sendDaftarUlangReminder($casis);

        if ($success) {
            return back()->with('success', 'Notifikasi pengingat WhatsApp berhasil dikirim ke ' . $casis->nama_lengkap);
        } else {
            return back()->with('error', 'Gagal mengirim notifikasi WhatsApp. Pastikan nomor valid atau API aktif.');
        }
    }

    public function export(Request $request)
    {
        $query = Casis::with(['pembayaranTerakhir', 'jurusan', 'programKeunggulan'])
            ->where('status_verifikasi', 'Diverifikasi')
            ->has('pembayaran');

        // Apply all filters exactly like index
        $activePeriod = SpmbPeriod::where('status', 'aktif')->first();
        $selectedTahunAjaranId = $request->input('tahun_ajaran_id');
        $selectedSpmbPeriodId = $request->input('spmb_period_id');

        if (!$selectedTahunAjaranId && (!$selectedSpmbPeriodId && $activePeriod)) {
            $selectedTahunAjaranId = $activePeriod->tahun_ajaran_id;
            $selectedSpmbPeriodId = $activePeriod->id;
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('pembayaranTerakhir', function ($q2) use ($search) {
                      $q2->where('order_id', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('status_daftar_ulang')) $query->where('status_daftar_ulang', $request->status_daftar_ulang);
        if ($request->filled('tipe_pembayaran')) $query->whereHas('pembayaranTerakhir', function ($q) use ($request) { $q->where('tipe_pembayaran', $request->tipe_pembayaran); });
        if ($request->filled('status_transaksi')) $query->whereHas('pembayaranTerakhir', function ($q) use ($request) { $q->where('transaction_status', $request->status_transaksi); });
        if ($request->filled('jurusan_id')) $query->where('jurusan_id', $request->jurusan_id);
        
        if ($selectedSpmbPeriodId) {
            $query->where('spmb_period_id', $selectedSpmbPeriodId);
        } elseif ($selectedTahunAjaranId) {
            $query->whereHas('spmbPeriod', function($q) use ($selectedTahunAjaranId) {
                $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
            });
        }

        $casis = $query->latest()->get();

        $filename = 'rekap_pembayaran_daftar_ulang_' . date('Y-m-d_His') . '.xls';
        
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
                'No', 'Nama Lengkap', 'NISN', 'No. Pendaftaran', 'Jurusan', 
                'Status Daftar Ulang', 'Nomor Pembayaran', 'Nominal', 'Type', 'Status Transaksi', 'Tanggal Transaksi'
            ];

            echo "<tr>";
            foreach($columns as $col) {
                echo "<th style='background-color: #16a34a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000; padding: 10px;'>" . $col . "</th>";
            }
            echo "</tr>";

            $no = 1;
            foreach ($casis as $item) {
                $p = $item->pembayaranTerakhir;
                echo "<tr>";
                echo "<td style='border: 1px solid #000; text-align: center;'>" . $no++ . "</td>";
                echo "<td style='border: 1px solid #000;'>" . $item->nama_lengkap . "</td>";
                echo "<td style='border: 1px solid #000; mso-number-format:\"\\@\";'>" . $item->nisn . "</td>";
                echo "<td style='border: 1px solid #000; mso-number-format:\"\\@\";'>" . $item->no_pendaftaran . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->jurusan ? $item->jurusan->nama : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($item->status_daftar_ulang ?? 'Belum') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($p ? $p->order_id : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($p ? 'Rp ' . number_format($p->nominal, 0, ',', '.') : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($p ? ucfirst($p->tipe_pembayaran) : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($p ? ucfirst($p->transaction_status) : '-') . "</td>";
                echo "<td style='border: 1px solid #000;'>" . ($p && $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</body></html>";
        };

        return response()->stream($callback, 200, $headers);
    }
}
