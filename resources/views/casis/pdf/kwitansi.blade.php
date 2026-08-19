<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pembayaran - {{ $casis->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.4;
            color: #111;
            font-size: 14px;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table td {
            padding: 8px 4px;
            vertical-align: top;
        }
        .terbilang-box {
            border: 1px dashed #666;
            padding: 10px;
            font-style: italic;
            font-weight: bold;
            margin-bottom: 40px;
            line-height: 1.5;
        }
        .signature-area {
            width: 100%;
            margin-top: 30px;
            position: relative;
        }
        .signature-box {
            float: right;
            width: 300px;
            text-align: center;
            position: relative;
        }
        .signature-img {
            position: relative;
            height: 80px;
            z-index: 2;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .stamp-img {
            position: absolute;
            left: 20px;
            top: 25px;
            height: 110px;
            z-index: -1;
            opacity: 0.85;
        }
    </style>
</head>
<body>

<table border="0" width="100%" style="border-collapse: collapse;">
    <!-- HEADER UTAMA -->
    <tr>
        <!-- LOGO KIRI -->
        <td width="15%" align="center" valign="middle">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo-bpa.png'))) }}"
                width="80">
        </td>

        <!-- IDENTITAS SEKOLAH -->
        <td width="70%" align="center" style="padding: 0 10px;">
            <b style="font-size:16px; display:block;">
                YAYASAN PENDIDIKAN BHAKTI PRAJA TEGAL<br>
                SEKOLAH MENENGAH KEJURUAN
            </b>

            <b style="font-size:28px; display:block;">
                SMK BHAKTI PRAJA ADIWERNA
            </b>

            <b style="font-size:16px; display:block;">
                STATUS TERAKREDITASI B
            </b>
        </td>

        <!-- LOGO KANAN -->
        <td width="15%" align="center" valign="middle">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo-uqas.png'))) }}"
                width="80">
        </td>
    </tr>

    <!-- ALAMAT FULL WIDTH -->
    <tr>
        <td colspan="3" align="center" style="font-size:10px; padding-top:5px; line-height:1.5;">
            Alamat: Jl. KH. Wahid Hasyim No. 125 Adiwerna, Telp. (0283)-4541933 | Website: https://smkbpadw.sch.id | E-mail: smkbpadw@gmail.com | Tegal 52194
        </td>
    </tr>

    <!-- GARIS -->
    <tr>
        <td colspan="3" style="padding:0;">
            <hr style="border:0; border-top:2px solid #000; margin:10px 0 0 0;">
        </td>
    </tr>
</table>

    <div class="title" style="margin-top: 5px; margin-bottom: 2px;">Bukti Pembayaran Daftar Ulang</div>
    <p align="center" style="margin-top: 0; margin-bottom: 20px;">Penerimaan Siswa Baru TH {{ $tahun_ajaran }}</p>

    <table class="data-table">
        <tr>
            <td width="150">Nama</td>
            <td width="10">:</td>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $casis->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>No. Pendaftaran</td>
            <td>:</td>
            <td>{{ $casis->no_pendaftaran }}</td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td>:</td>
            <td>{{ $casis->jurusan ? $casis->jurusan->nama : '-' }}</td>
        </tr>
        <tr>
            <td>Telah dibayarkan sebesar</td>
            <td>:</td>
            <td style="font-weight: bold; font-size: 16px;">Rp {{ number_format($tagihanDaftarUlang->nominal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Untuk Pembayaran</td>
            <td>:</td>
            <td>Daftar Ulang Penerimaan Siswa Baru Tahun Ajaran {{ $tahun_ajaran }}</td>
        </tr>
    </table>

    <div class="terbilang-box">
        Terbilang: <br>
        <span style="font-size: 15px;">{{ ucwords(terbilang($tagihanDaftarUlang->nominal)) }} Rupiah</span>
    </div>

    <div class="signature-area">
        <div class="signature-box">
            Adiwerna, {{ \Carbon\Carbon::parse($tagihanDaftarUlang->settlement_time ?? now())->translatedFormat('d F Y') }}<br>
            Panitia SPMB,
            <br>
            <div style="position: relative; height: 100px; width: 200px; margin: 0 auto;">
                @if(isset($settings['kwitansi_stempel_panitia']) && $settings['kwitansi_stempel_panitia'] && file_exists(public_path('storage/' . $settings['kwitansi_stempel_panitia'])))
                    <img src="{{ public_path('storage/' . $settings['kwitansi_stempel_panitia']) }}" style="height: 120px; position: absolute; left: -10px; top: -10px; z-index: -1; opacity: 0.85;">
                @endif

                @if(isset($settings['kwitansi_ttd_panitia']) && $settings['kwitansi_ttd_panitia'] && file_exists(public_path('storage/' . $settings['kwitansi_ttd_panitia'])))
                    <img src="{{ public_path('storage/' . $settings['kwitansi_ttd_panitia']) }}" style="height: 80px; position: relative; z-index: 2; margin-top: 10px;">
                @else
                    <div style="height: 80px;"></div>
                @endif
            </div>

            <strong><u>{{ $settings['kwitansi_nama_panitia'] ?? 'Panitia SPMB' }}</u></strong>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
