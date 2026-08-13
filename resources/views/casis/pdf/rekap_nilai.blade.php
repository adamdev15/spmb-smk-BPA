<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Nilai - {{ $casis?->nama_lengkap ?? 'Siswa' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.4;
            color: #333;
            font-size: 14px;
        }

        .table-data {
            border-collapse: collapse;
            width: 100%;
        }

        .table-data th,
        .table-data td {
            padding: 8px;
            border: 1px solid #333;
        }

        .no-border td {
            border: none;
            padding: 2px;
        }

        .header-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    @include('casis.pdf.kop_lap')

    <h3 align="center" style="margin-top: 20px; text-decoration: underline;">REKAPITULASI NILAI RAPORT</h3>

    <table class="no-border" style="margin-bottom: 20px;">
        <tr>
            <td width="200">NO. PENDAFTARAN</td>
            <td width="10">:</td>
            <td><strong>{{ $casis?->no_pendaftaran ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>:</td>
            <td>{{ $casis?->nisn ?? '-' }}</td>
        </tr>
        <tr>
            <td>NAMA LENGKAP</td>
            <td>:</td>
            <td><strong>{{ strtoupper($casis?->nama_lengkap ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td>ASAL SEKOLAH</td>
            <td>:</td>
            <td>{{ strtoupper($casis?->nama_sekolah ?? '-') }}</td>
        </tr>
    </table>

    <table class="table-data header-table">
        <thead>
            <tr>
                <th rowspan="2">Mata Pelajaran</th>
                <th colspan="3">Nilai Rapor Semester</th>
                <th rowspan="2">Rata-rata</th>
            </tr>
            <tr>
                <th>3</th>
                <th>4</th>
                <th>5</th>
            </tr>
        </thead>
        <tbody>
            @php
            $subjects = [
            'ipa' => 'Ilmu Pengetahuan Alam (IPA)',
            'ips' => 'Ilmu Pengetahuan Sosial (IPS)',
            'matematika' => 'Matematika',
            'bind' => 'Bahasa Indonesia',
            'bing' => 'Bahasa Inggris'
            ];
            $overallTotal = 0;
            $count = 0;
            @endphp

            @foreach($subjects as $key => $label)
            @php
            $s3 = $casis?->nilaiRapor?->where('semester', 3)->first()?->$key ?? 0;
            $s4 = $casis?->nilaiRapor?->where('semester', 4)->first()?->$key ?? 0;
            $s5 = $casis?->nilaiRapor?->where('semester', 5)->first()?->$key ?? 0;
            $avg = ($s3 + $s4 + $s5) / 3;
            $overallTotal += $avg;
            $count++;
            @endphp
            <tr align="center">
                <td align="left">{{ $label }}</td>
                <td>{{ number_format($s3, 2) }}</td>
                <td>{{ number_format($s4, 2) }}</td>
                <td>{{ number_format($s5, 2) }}</td>
                <td style="background-color: #f9f9f9; font-weight: bold;">{{ number_format($avg, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #eee; font-weight: bold;">
                <td align="left">RATA-RATA TOTAL</td>
                <td colspan="4" align="center">{{ number_format($overallTotal / $count, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-left: 65%; margin-top: 40px;">
        Tegal, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Calon Peserta PMBM
        <br><br><br><br>
        <strong>{{ strtoupper($casis?->nama_lengkap ?? '-') }}</strong>
    </div>

    <div style="margin-top: 50px; font-size: 11px;">
        <p><strong>Keterangan:</strong></p>
        <ol>
            <li>Semua data nilai yang dimasukan adalah benar dan sesuai dengan nilai asli.</li>
            <li>Apabila ditemukan data nilai yang telah saya berikan tidak benar, maka saya bersedia dikenakan sanksi
                dan atau didiskualifikasi dari seleksi PMBM MAN 1 Tegal Tahun Pelajaran {{ $tahun_ajaran }}.</li>
        </ol>
    </div>
</body>

</html>