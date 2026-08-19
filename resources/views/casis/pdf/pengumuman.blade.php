<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengumuman SPMB - {{ $casis->nama_lengkap }}</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: "Times New Roman", Times, serif; line-height: 1.5; color: #000; font-size: 14px; }
        .header-table { width: 100%; border-bottom: 3px double #000; padding-bottom: 5px; margin-bottom: 20px; }
        .header-table h1 { margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header-table h2 { margin: 0; font-size: 16px; font-weight: normal; }
        .header-table p { margin: 0; font-size: 12px; }
        .title { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 5px; text-decoration: underline; letter-spacing: 1px; }
        .subtitle { text-align: center; font-size: 14px; margin-bottom: 25px; }
        .content { text-align: justify; }
        .data-table { width: 80%; margin: 15px auto; font-size: 14px; }
        .data-table td { padding: 3px 0; }
        .status-box { text-align: center; margin: 15px 0; }
        .status-text { font-size: 18px; font-weight: bold; text-transform: uppercase; display: inline-block; letter-spacing: 2px; }
        
        .rincian-biaya { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 13px; }
        .rincian-biaya th, .rincian-biaya td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        .rincian-biaya th { text-align: center; font-weight: bold; background-color: #f0f0f0; }
        .rincian-biaya td.center { text-align: center; }
        .rincian-biaya td.right { text-align: right; }
        .rincian-biaya td.bold { font-weight: bold; }
        
        .signature-table { width: 100%; margin-top: 40px; text-align: center; page-break-inside: avoid; }
        .signature-table td { width: 50%; vertical-align: bottom; position: relative; }
        .signature-img { height: 80px; margin: 10px auto; display: block; object-fit: contain; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="90" style="text-align: center; vertical-align: middle;">
                @if(isset($settings['logo']) && file_exists(public_path('storage/' . $settings['logo'])))
                    <img src="{{ public_path('storage/' . $settings['logo']) }}" alt="Logo" style="height: 80px; max-width: 80px;">
                @else
                    <div style="height: 80px; width: 80px; border: 1px solid #000; text-align: center; line-height: 80px; font-size: 12px; margin: 0 auto;">LOGO</div>
                @endif
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <h2>PANITIA PENERIMAAN PESERTA DIDIK BARU</h2>
                <h1>{{ $settings['nama_sekolah'] ?? 'SMK BHAKTI PRAJA ADIWERNA' }}</h1>
                <p>{{ $settings['alamat_sekolah'] ?? 'Jl. Raya Singkil Adiwerna Kab. Tegal' }}</p>
                <p>Website: www.smkbpa.sch.id | Email: info@smkbpa.sch.id</p>
            </td>
        </tr>
    </table>

    <div class="title">PENGUMUMAN</div>
    <div class="subtitle">
        Nomor : {{ $settings['pengumuman_nomor_surat'] ?? '700.a/SMK.BP/VI/2026' }}<br>
        Perihal : Hasil Seleksi Penerimaan Peserta Didik Baru<br>
        Tahun Pelajaran : {{ $tahun_ajaran }}
    </div>

    <div class="content">
        <p>Berdasarkan hasil seleksi Panitia Penerimaan Peserta Didik Baru (PPDB/SPMB) {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }} Tahun Pelajaran {{ $tahun_ajaran }}, maka peserta dengan data di bawah ini :</p>
        
        <table class="data-table">
            <tr>
                <td width="180">Nama Peserta</td>
                <td width="10">:</td>
                <td style="font-weight: bold; text-transform: uppercase;">{{ $casis->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Nomor Pendaftaran</td>
                <td>:</td>
                <td>{{ $casis->no_pendaftaran }}</td>
            </tr>
            <tr>
                <td>Kompetensi Keahlian</td>
                <td>:</td>
                <td>{{ $casis->jurusan ? $casis->jurusan->nama : '-' }}</td>
            </tr>
        </table>

        <p>Dinyatakan :</p>

        <div class="status-box">
            <span class="status-text">DITERIMA</span>
        </div>

        <p>di {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}.</p>
        <p>Bagi peserta didik yang telah dinyatakan diterima, <strong>WAJIB</strong> mengikuti seluruh rangkaian kegiatan daftar ulang sesuai dengan ketentuan berikut:</p>
        
        <p style="margin-bottom: 5px;"><strong>A. Waktu dan Tempat Daftar Ulang</strong></p>
        <table style="margin-left: 15px; margin-bottom: 15px;">
            @php $jadwals = \App\Models\SpmbPeriod::orderBy('gelombang')->get(); @endphp
            @foreach($jadwals as $jadwal)
            <tr>
                <td width="100">Gelombang {{ $jadwal->gelombang }}</td>
                <td width="10">:</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->translatedFormat('d F Y') }}</td>
            </tr>
            @endforeach
            <tr>
                <td style="vertical-align: top;">Tempat</td>
                <td style="vertical-align: top;">:</td>
                <td>Kampus {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}</td>
            </tr>
        </table>

        <p style="margin-bottom: 5px;"><strong>B. Rincian Biaya Daftar Ulang</strong></p>
        <table class="rincian-biaya">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th width="60%">Uraian Kegiatan</th>
                    <th width="30%">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $biayas = $casis->jurusan ? $casis->jurusan->biayas()->whereIn('jenis_biaya', ['Daftar Ulang', 'SPP'])->get() : collect();
                    $totalBiaya = 0;
                @endphp
                @forelse($biayas as $index => $biaya)
                    @php $totalBiaya += $biaya->nominal; @endphp
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $biaya->nama_biaya }}</td>
                        <td class="right">Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="center">Rincian biaya belum diatur oleh Panitia.</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="2" class="right bold">JUMLAH BIAYA</td>
                    <td class="right bold">
                        Rp {{ number_format($totalBiaya > 0 ? $totalBiaya : ($casis->jurusan ? $casis->jurusan->biaya_daftar_ulang : 1500000), 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p><strong>C. Informasi Tambahan</strong></p>
        <ul style="margin-top: 5px; margin-bottom: 20px;">
            <li>Pembekalan MPLS : <strong>{{ isset($settings['pengumuman_tgl_mpls']) && $settings['pengumuman_tgl_mpls'] ? \Carbon\Carbon::parse($settings['pengumuman_tgl_mpls'])->translatedFormat('l, d F Y') : '10 Juli 2026' }}</strong></li>
            <li>Awal Masuk Sekolah : <strong>{{ isset($settings['pengumuman_tgl_masuk']) && $settings['pengumuman_tgl_masuk'] ? \Carbon\Carbon::parse($settings['pengumuman_tgl_masuk'])->translatedFormat('l, d F Y') : '13 Juli 2026' }}</strong></li>
        </ul>

        <table class="signature-table">
            <tr>
                <td>
                    Kepala Sekolah,
                    <br><br>
                    <div style="position: relative; height: 100px; width: 200px; margin: 0 auto;">
                        @if(isset($settings['kwitansi_stempel_panitia']) && $settings['kwitansi_stempel_panitia'] && file_exists(public_path('storage/' . $settings['kwitansi_stempel_panitia'])))
                            <img src="{{ public_path('storage/' . $settings['kwitansi_stempel_panitia']) }}" style="height: 140px; position: absolute; left: -20px; top: -30px; z-index: 1; opacity: 0.8;">
                        @endif

                        @if(isset($settings['pengumuman_ttd_kepsek']) && $settings['pengumuman_ttd_kepsek'] && file_exists(public_path('storage/' . $settings['pengumuman_ttd_kepsek'])))
                            <img src="{{ public_path('storage/' . $settings['pengumuman_ttd_kepsek']) }}" class="signature-img" style="position: relative; z-index: 2;">
                        @endif
                    </div>
                    <strong><u>{{ $settings['pengumuman_nama_kepsek'] ?? '_____________________' }}</u></strong><br>
                    NIP. {{ $settings['pengumuman_nip_kepsek'] ?? '________________' }}
                </td>
                <td>
                    Adiwerna, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Ketua SPMB,
                    <br><br>
                    <div style="position: relative; height: 100px; width: 200px; margin: 0 auto;">
                        @if(isset($settings['pengumuman_ttd_ketua']) && $settings['pengumuman_ttd_ketua'] && file_exists(public_path('storage/' . $settings['pengumuman_ttd_ketua'])))
                            <img src="{{ public_path('storage/' . $settings['pengumuman_ttd_ketua']) }}" class="signature-img" style="position: relative; z-index: 2;">
                        @endif
                    </div>
                    <strong><u>{{ $settings['pengumuman_nama_ketua'] ?? '_____________________' }}</u></strong>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
