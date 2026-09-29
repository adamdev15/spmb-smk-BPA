<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Bukti Pendaftaran SPMB - {{ $casis->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.4;
            color: #111;
            font-size: 13px;
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
            font-size: 16px;
            text-transform: uppercase;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table td {
            padding: 4px 2px;
            vertical-align: top;
        }
        .photo-box {
            position: absolute;
            right: 10px;
            top: 10px;
            width: 100px;
            height: 130px;
            border: 1px solid #333;
            text-align: center;
            line-height: 130px;
            background: #fafafa;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: #e5e5e5;
            color: #000;
            font-size: 14px;
            border-radius: 4px;
            font-weight: 900;
            border: 1px solid #ccc;
        }
        .footer-sig {
            margin-top: 25px;
            float: right;
            width: 250px;
            text-align: center;
        }
    </style>
</head>
<body>


    <table class="header-table">
        <tr>
            <td width="80" align="center">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo-bpa.png'))) }}"
                width="80">
            </td>
            <td align="center">
                <h2 style="margin:0; font-size: 18px;">SEKOLAH MENENGAH KEJURUAN BHAKTI PRAJA ADIWERNA</h2>
                <p style="margin:0; font-size: 11px; font-weight: bold;">PANITIA SISTEM PENERIMAAN MURID BARU (SPMB) TA {{ $tahun_ajaran }}</p>
                <p style="margin:2px 0; font-size: 12px;">Alamat: Jl. KH. Wahid Hasyim No. 125 Adiwerna, Tegal 52165 | (0283)-4541933
            <br>
            Website: https://smkbpadw.sch.id | E-mail: smkbpadw@gmail.com | Tegal 52194</p>
            </td>
        </tr>
    </table>

    <div class="title">KARTU BUKTI PENDAFTARAN PESERTA</div>

    <div style="position: relative; min-height: 170px; border: 1px solid #ccc; padding: 3px;">
        <table class="data-table">
            <tr>
                <td width="160">NO. PENDAFTARAN</td>
                <td width="10">:</td>
                <td><span class="badge">{{ $casis->no_pendaftaran }}</span></td>
            </tr>
            <tr>
                <td>NAMA LENGKAP</td>
                <td>:</td>
                <td><strong>{{ strtoupper($casis->nama_lengkap) }}</strong></td>
            </tr>
            <tr>
                <td>TEMPAT, TGL LAHIR</td>
                <td>:</td>
                <td>{{ strtoupper($casis->tempat_lahir) }}, {{ \Carbon\Carbon::parse($casis->tgl_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>JURUSAN</td>
                <td>:</td>
                <td><strong>{{ $casis->jurusan ? strtoupper($casis->jurusan->nama) : '-' }}</strong></td>
            </tr>
            <tr>
                <td>PROGRAM KEUNGGULAN</td>
                <td>:</td>
                <td>{{ $casis->programKeunggulan ? strtoupper($casis->programKeunggulan->nama) : '-' }}</td>
            </tr>
            <tr>
                <td>ASAL SEKOLAH</td>
                <td>:</td>
                <td>{{ strtoupper($casis->nama_sekolah ?? '-') }}</td>
            </tr>
            <tr>
                <td>NO. HP SISWA</td>
                <td>:</td>
                <td>{{ $casis->no_hp_siswa }}</td>
            </tr>
            <tr>
                <td>ALAMAT</td>
                <td>:</td>
                <td>{{ $casis->alamat_siswa}}, RT {{ $casis->rt}} / RW {{ $casis->rw }}, {{ $casis->kelurahan?->nama_desa_kel }}, {{ $casis->kecamatan?->nama_kec }}, {{ $casis->kabupaten?->nama_kabkota }}, {{ $casis->provinsi?->nama_provinsi }}</td>
            </tr>
        </table>

        <div class="photo-box">
            @php
                $foto = $casis->berkas->where('nama_berkas', 'Pas Foto 3x4')->first();
            @endphp
            @if($foto && file_exists(public_path('storage/' . $foto->path)))
                @php
                    $pathParts = pathinfo(public_path('storage/' . $foto->path));
                    $extension = strtolower($pathParts['extension'] ?? 'jpg');
                    $mime = ($extension == 'png') ? 'image/png' : 'image/jpeg';
                @endphp
                <img src="data:{{ $mime }};base64,{{ base64_encode(file_get_contents(public_path('storage/' . $foto->path))) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
            @else
                <span style="font-size: 10px; color: #888;">FOTO 3X4</span>
            @endif
        </div>
    </div>

    <table style="width: 100%; margin-top: 15px;">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <p style="text-align: left; font-style: italic; font-weight: bold; margin-bottom: 5px; margin-top: 0;">Pendaftaran Gratis</p>
                <p style="text-align: left; margin: 0;">Contact Person:</p>
                <ul style="margin-top: 5px; padding-left: 20px;">
                    @if(!empty($settings['contact_person']))
                        @foreach(explode("\n", str_replace("\r", "", $settings['contact_person'])) as $cp)
                            @if(trim($cp) != '')
                                <li>{{ trim($cp) }}</li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </td>
            <td style="width: 40%; vertical-align: top; text-align: center; font-size: 13px;">
                Adiwerna, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Panitia SPMB SMK BPA,<br><br>
                <div style="position: relative; height: 80px; width: 100%; margin: 0 auto;">
                    @if(isset($settings['kwitansi_stempel_panitia']) && $settings['kwitansi_stempel_panitia'] && file_exists(public_path('storage/' . $settings['kwitansi_stempel_panitia'])))
                        <img src="{{ public_path('storage/' . $settings['kwitansi_stempel_panitia']) }}" style="height: 160px; position: absolute; left: 10%; top: -35px; z-index: 3; opacity: 0.85;">
                    @endif

                    @if(isset($settings['kwitansi_ttd_panitia']) && $settings['kwitansi_ttd_panitia'] && file_exists(public_path('storage/' . $settings['kwitansi_ttd_panitia'])))
                        <img src="{{ public_path('storage/' . $settings['kwitansi_ttd_panitia']) }}" style="height: 80px; position: relative; z-index: 2; margin: 0 auto; display: block; object-fit: contain;">
                    @else
                        <div style="height: 80px;"></div>
                    @endif
                </div>
                <strong><u>{{ $settings['kwitansi_nama_panitia'] ?? 'Panitia Pendaftaran' }}</u></strong>
            </td>
        </tr>
    </table>

</body>
</html>