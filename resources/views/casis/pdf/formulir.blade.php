<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Formulir Pendaftaran - {{ $casis?->nama_lengkap ?? 'Siswa' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.5;
            color: #333;
            font-size: 13px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .data-table td {
            padding: 4px;
            vertical-align: top;
        }

        .gray-bg {
            background-color: #f5f5f5;
        }

        .section-title {
            background-color: #eee;
            padding: 5px 10px;
            font-weight: bold;
            margin: 15px 0 10px 0;
            border-left: 5px solid #333;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @include('casis.pdf.kop_lap')

    <h2 align="center" style="margin-top: 10px; margin-bottom: 0;">
    FORMULIR PENDAFTARAN
    </h2>

    <h2 align="center" style="margin-top: -5px; margin-bottom: 0;">
        Sistem Penerimaan Murid Baru (SPMB)
    </h2>

    <h2 align="center" style="margin-top: -5px; margin-bottom: 10px;">
        Tahun Pelajaran {{ $tahun_ajaran }}
    </h2>

    <div class="section-title">A. DATA CALON PESERTA DIDIK</div>
    <table class="data-table">
        <tr class="gray-bg">
            <td width="30%">1. No. Pendaftaran</td>
            <td width="2%">:</td>
            <td><strong>{{ $casis?->no_pendaftaran ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>2. Nama Lengkap</td>
            <td>:</td>
            <td><strong>{{ strtoupper($casis?->nama_lengkap ?? '-') }}</strong></td>
        </tr>
        <tr class="gray-bg">
            <td>3. NISN / NIK</td>
            <td>:</td>
            <td>{{ $casis?->nisn ?? '-' }} / {{ $casis?->nik ?? '-' }}</td>
        </tr>
        <tr class="gray-bg">
            <td>4. Jenis Kelamin</td>
            <td>:</td>
            <td>{{ ($casis?->jk ?? '') == 'L' ? 'LAKI-LAKI' : (($casis?->jk ?? '') == 'P' ? 'PEREMPUAN' : '-') }}</td>
        </tr>
        <tr>
            <td>5. Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ strtoupper($casis?->tempat_lahir ?? '-') }}, {{ $casis?->tgl_lahir ?
                \Carbon\Carbon::parse($casis->tgl_lahir)->translatedFormat('d
                F Y') : '-' }}</td>
        </tr>
        <tr class="gray-bg">
            <td>6. Agama</td>
            <td>:</td>
            <td>{{ strtoupper($casis?->agama ?? '-') }}</td>
        </tr>
        <tr>
            <td>7. Alamat Tempat Tinggal</td>
            <td>:</td>
            <td>{{ strtoupper($casis?->alamat_siswa ?? '-') }}</td>
        </tr>
        <tr class="gray-bg">
            <td>8. No. WhatsApp Siswa</td>
            <td>:</td>
            <td>{{ $casis?->no_hp_siswa ?? '-' }}</td>
        </tr>
        <tr>
            <td>9. Program Keahlian</td>
            <td>:</td>
            <td>{{ strtoupper(DB::table('master_jurusan')->where('id', $casis?->jurusan_id)->value('nama') ??
                '-') }}</td>
        </tr>
    </table>

    <div class="section-title">B. DATA Orang Tua Siswa</div>
    <table class="data-table">
        <tr class="gray-bg">
            <td width="30%">1. Nama Ayah</td>
            <td width="2%">:</td>
            <td>{{ strtoupper($casis?->nama_ayah ?? '-') }}</td>
        </tr>
        <tr class="gray-bg">
            <td width="30%">2. Nama Ibu</td>
            <td width="2%">:</td>
            <td>{{ strtoupper($casis?->nama_ibu ?? '-') }}</td>
        </tr>
        <tr class="gray-bg">
            <td>3. No. WhatsApp</td>
            <td>:</td>
            <td>{{ $casis?->no_hp_siswa ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">C. DATA ASAL SEKOLAH</div>
    <table class="data-table">
        <tr class="gray-bg">
            <td width="30%">1. Nama Sekolah</td>
            <td width="2%">:</td>
            <td>{{ strtoupper($casis?->nama_sekolah ?? '-') }}</td>
        </tr>
        <tr>
            <td>2. Alamat Sekolah</td>
            <td>:</td>
            <td>{{ strtoupper($casis?->alamat_sekolah ?? '-') }}</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 50px; text-align: center;">
        <tr>
            <td style="width: 50%; vertical-align: bottom;">
                Mengetahui,<br>
                Panitia SPMB SMK BPA
                <br><br>
                <div style="position: relative; height: 80px; width: 100%; margin: 0 auto;">
                    @if(isset($settings['kwitansi_stempel_panitia']) && $settings['kwitansi_stempel_panitia'] && file_exists(public_path('storage/' . $settings['kwitansi_stempel_panitia'])))
                        <img src="{{ public_path('storage/' . $settings['kwitansi_stempel_panitia']) }}" style="height: 120px; position: absolute; left: 10%; top: -20px; z-index: 1; opacity: 0.8;">
                    @endif

                    @if(isset($settings['kwitansi_ttd_panitia']) && $settings['kwitansi_ttd_panitia'] && file_exists(public_path('storage/' . $settings['kwitansi_ttd_panitia'])))
                        <img src="{{ public_path('storage/' . $settings['kwitansi_ttd_panitia']) }}" style="height: 80px; position: relative; z-index: 2; margin: 0 auto; display: block; object-fit: contain;">
                    @else
                        <div style="height: 80px;"></div>
                    @endif
                </div>
                <strong><u>{{ $settings['kwitansi_nama_panitia'] ?? 'Panitia Pendaftaran' }}</u></strong>
            </td>
            <td style="width: 50%; vertical-align: bottom;">
                Tegal, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Calon Peserta SPMB
                <br><br>
                <div style="height: 80px;"></div>
                <strong><u>{{ strtoupper($casis?->nama_lengkap ?? '-') }}</u></strong>
            </td>
        </tr>
    </table>
</body>

</html>