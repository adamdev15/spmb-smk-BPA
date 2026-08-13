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
            top: 50px;
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
            padding: 3px 8px;
            background: #f3f3f3ff;
            color: #797979ff;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
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
                <p style="margin:2px 0; font-size: 12px;">Jl. Singkil No. 24, Adiwerna, Kab. Tegal | Telp: (0283) 443210</p>
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
                <td>NISN / NIK</td>
                <td>:</td>
                <td>{{ $casis->nisn }} / {{ $casis->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>JENIS KELAMIN</td>
                <td>:</td>
                <td>{{ $casis->jk == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</td>
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
                <td>{{ $casis->alamat_siswa}}, RT {{ $casis->rt}} / RW {{ $casis->rw }}, {{ $casis->kecamatan}}, {{ $casis->kab_kota}}</td>
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

    <p style="text-align: left; font-style: italic; font-weight: bold;">Pendaftaran Gratis</p>
    <p style="text-align: left;">Contact Person:</p>
    <ul>
        @if(!empty($settings['contact_person']))
            @foreach(explode("\n", str_replace("\r", "", $settings['contact_person'])) as $cp)
                @if(trim($cp) != '')
                    <li>{{ trim($cp) }}</li> <br>
                @endif
            @endforeach
        @endif
    </ul>

    <div style="margin-top: 15px; background: #f3f3f3ff; border: 1px solid #e6e6e6ff; padding: 10px; border-radius: 5px;">
        <strong style="color: #515151ff;">INFORMASI TAHAPAN SELEKSI:</strong>
        <ol style="margin: 5px 0 0 15px; padding: 0; font-size: 12px;">
            <li>Calon siswa wajib mengikuti Tes Psikotes dan Seleksi Fisik (pemeriksaan tindik, tato, dan buta warna).</li>
            <li>Hasil pengumuman dan link join Grup WhatsApp Jurusan dapat diakses melalui Dashboard Siswa.</li>
            <li>Simpan kartu bukti ini dan tunjukkan kepada panitia saat verifikasi berkas fisik & seleksi.</li>
            <li>Daftar ulang dapat dilakukan setelah dinyatakan lulus seleksi dengan membayaran biaya daftar ulang.</li>
        </ol>
    </div>

    <div class="footer-sig">
        Adiwerna, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Panitia SPMB SMK BPA,<br><br><br><br>
        <strong>( Panitia Pendaftaran )</strong>
    </div>

</body>
</html>