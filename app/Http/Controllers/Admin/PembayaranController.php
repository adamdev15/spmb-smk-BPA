<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casis;
use App\Models\Jurusan;
use App\Models\Pembayaran;
use App\Models\SpmbPeriod;
use App\Models\TahunAjaran;
use App\Services\WhatsAppService;
use App\Services\PembayaranService;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();
        $tahunAjarans = TahunAjaran::with('spmbPeriods')->orderBy('nama', 'desc')->get();
        $activePeriod = SpmbPeriod::where('status', 'aktif')->first();

        $selectedTahunAjaranId = $request->input('tahun_ajaran_id');
        $selectedSpmbPeriodId = $request->input('spmb_period_id');

        if (!$selectedTahunAjaranId && (!$selectedSpmbPeriodId && $activePeriod)) {
            $selectedTahunAjaranId = $activePeriod->tahun_ajaran_id;
            $selectedSpmbPeriodId = $activePeriod->id;
        }

        $query = Casis::with(['pembayaranTerakhir', 'pembayaranDaftarUlang', 'jurusan', 'programKeunggulan', 'spmbPeriod'])
            ->where(function($q) {
                $q->where('status_verifikasi', 'Diverifikasi')
                  ->orWhere('status_kelulusan', 'Lulus')
                  ->orWhereHas('pembayaran');
            });

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('pembayaran', function ($q2) use ($search) {
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
        $casis = Casis::with(['jurusan', 'programKeunggulan', 'spmbPeriod.tahunAjaran', 'pembayaran' => function($q) {
            $q->with('admin')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        $pembayaran = PembayaranService::createOrGetTagihanDaftarUlang($casis, false);
            
        return view('admin.pembayaran.show', compact('casis', 'pembayaran'));
    }

    public function printKwitansi($id)
    {
        $casis = Casis::with(['jurusan', 'spmbPeriod.tahunAjaran'])->findOrFail($id);
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        
        $tagihanDaftarUlang = \App\Services\PembayaranService::createOrGetTagihanDaftarUlang($casis, false);
        $tahun_ajaran = $casis->spmbPeriod ? $casis->spmbPeriod->tahunAjaran->nama : '2026/2027';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casis.pdf.kwitansi', compact('casis', 'tagihanDaftarUlang', 'settings', 'tahun_ajaran'));
        return $pdf->stream('Kwitansi_Pembayaran_' . $casis->no_pendaftaran . '.pdf');
    }

    public function processPayment(Request $request, $id)
    {
        $casis = Casis::findOrFail($id);

        $request->validate([
            'payment_mode' => 'required|in:offline,online',
            'nominal' => 'required|numeric|min:0',
            'payment_type' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'nomor_referensi' => 'nullable|string',
            'catatan_admin' => 'nullable|string',
            'transaction_status' => 'nullable|in:settlement,pending,failed'
        ]);

        if ($request->payment_mode === 'offline') {
            PembayaranService::processManualPayment($casis, [
                'nominal' => $request->nominal,
                'payment_type' => $request->payment_type ?: 'Tunai',
                'paid_at' => $request->paid_at ?: now(),
                'nomor_referensi' => $request->nomor_referensi,
                'catatan_admin' => $request->catatan_admin ?: 'Pembayaran Manual Kasir Sekolah (Offline)',
                'transaction_status' => $request->transaction_status ?: 'settlement'
            ], auth()->user());

            return back()->with('success', 'Pembayaran manual sebesar Rp ' . number_format($request->nominal, 0, ',', '.') . ' berhasil disimpan dan status daftar ulang telah diperbarui.');
        } else {
            // Online mode: generate/refresh snap token or send WA payment link
            $result = PembayaranService::getOrCreateSnapToken($casis);
            
            if ($request->has('send_wa') && $casis->no_hp_siswa) {
                $p = $result['pembayaran'] ?? PembayaranService::createOrGetTagihanDaftarUlang($casis, false);
                WhatsAppService::sendTagihanDaftarUlang($casis, $p);
                return back()->with('success', 'Rincian pembayaran online berhasil dikirim ke WhatsApp ' . $casis->nama_lengkap);
            }

            if ($result['success']) {
                return back()
                    ->with('success', 'Transaksi online Payment Gateway berhasil disiapkan.')
                    ->with('open_snap_token', $result['snap_token']);
            } else {
                return back()->with('error', $result['message'] ?? 'Gagal membuat transaksi online.');
            }
        }
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

        $pembayaran = PembayaranService::createOrGetTagihanDaftarUlang($casis, false);
        $success = WhatsAppService::sendTagihanDaftarUlang($casis, $pembayaran);

        if ($success) {
            return back()->with('success', 'Notifikasi rincian tagihan daftar ulang WhatsApp berhasil dikirim ke ' . $casis->nama_lengkap);
        } else {
            return back()->with('error', 'Gagal mengirim notifikasi WhatsApp. Pastikan nomor valid atau API Fonnte aktif.');
        }
    }

    public function export(Request $request)
    {
        $query = Casis::with(['pembayaranTerakhir', 'jurusan', 'programKeunggulan'])
            ->where(function($q) {
                $q->where('status_verifikasi', 'Diverifikasi')
                  ->orWhere('status_kelulusan', 'Lulus')
                  ->orWhereHas('pembayaran');
            });

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
                  ->orWhereHas('pembayaran', function ($q2) use ($search) {
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