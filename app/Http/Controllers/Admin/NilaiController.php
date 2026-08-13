<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Casis;
use App\Models\NilaiRapor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class NilaiController extends Controller
{
    public function index()
    {
        // Fetch Casis with their NilaiRapor
        $casis = Casis::with('nilaiRapor')->latest()->paginate(20);
        return view('admin.nilai.index', compact('casis'));
    }

    public function download(Request $request)
    {
        $type = $request->query('type', 'csv');
        $casis = Casis::with('nilaiRapor')->get();

        if ($type == 'csv') {
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=rekap_nilai_casis.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $columns = ['Nama Lengkap', 'NISN', 'Matematika', 'IPA', 'Bahasa Indonesia', 'Bahasa Inggris', 'Rata-rata'];

            $callback = function () use ($casis, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($casis as $student) {
                    $nilais = $student->nilaiRapor;

                    if ($nilais->count() > 0) {
                        $mtk = $nilais->avg('matematika');
                        $ipa = $nilais->avg('ipa');
                        $bind = $nilais->avg('bind');
                        $bing = $nilais->avg('bing');
                        $avg = ($mtk + $ipa + $bind + $bing) / 4;
                    }
                    else {
                        $mtk = $ipa = $bind = $bing = $avg = 0;
                    }

                    fputcsv($file, [
                        $student->nama_lengkap,
                        $student->nisn,
                        number_format($mtk, 2),
                        number_format($ipa, 2),
                        number_format($bind, 2),
                        number_format($bing, 2),
                        number_format($avg, 2)
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back();
    }
}