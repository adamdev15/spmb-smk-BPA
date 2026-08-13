<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Calon Siswa - SPMB SMK Bhakti Praja Adiwerna</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(!empty($settings['logo']))
        <link rel="icon" href="{{ asset('storage/' . $settings['logo']) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('images/logo_smk_bpa.png') }}" type="image/png">
    @endif
    <!-- Midtrans Snap JS (Sandbox mode by default) -->
    <script type="text/javascript"
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800" x-data="studentDashboard()">

    <!-- Top Header -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-lg shadow-blue-500/20 font-heading overflow-hidden">
                            @if(!empty($settings['logo']))
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain p-1">
                            @else
                                <img src="{{ asset('images/logo_smk_bpa.png') }}" alt="Logo" class="w-full h-full object-contain p-1" onerror="this.outerHTML='{{ $settings['singkatan_sekolah'] ?? 'BPA' }}'">
                            @endif
                        </div>
                    <div>
                        <h1 class="text-xs sm:text-sm md:text-base font-bold text-slate-900 leading-none font-heading">
                            SPMB {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}
                        </h1>
                        <span class="text-[11px] text-blue-500 font-medium">Gelombang {{ $casis->spmbPeriod ? $casis->spmbPeriod->gelombang : '-' }} &bull; Tahun Ajaran {{ $tahun_ajaran ?? '2026/2027' }}</span>
                    </div>
                </div>

                <div class="relative" x-data="{ openProfile: false }">
                    <button @click="openProfile = !openProfile" @click.outside="openProfile = false" class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-slate-100 transition focus:outline-none">
                        <div class="hidden sm:flex flex-col items-end text-right">
                            <span class="text-xs font-bold text-slate-900">{{ $casis->nama_lengkap }}</span>
                            <span class="text-[10px] text-blue-600 font-extrabold bg-blue-50 px-2 py-0.5 rounded">No. Pendaftaran: {{ $casis->no_pendaftaran }}</span>
                        </div>
                        @php
                            $pasFoto = $casis->berkas->firstWhere('nama_berkas', 'Pas Foto 3x4');
                        @endphp
                        @if($pasFoto)
                            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
                                <img src="{{ asset('storage/' . $pasFoto->path) }}" alt="Foto Casis" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-200 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-300 shrink-0">
                                {{ substr($casis->nama_lengkap, 0, 1) }}
                            </div>
                        @endif
                        <svg class="w-4 h-4 text-slate-400" :class="{'rotate-180': openProfile}" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <div x-show="openProfile" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50"
                         style="display: none;">
                         
                        <div class="block sm:hidden px-4 py-3 border-b border-slate-100 mb-1">
                            <div class="font-bold text-slate-900 text-xs">{{ $casis->nama_lengkap }}</div>
                            <div class="text-[10px] text-slate-500 mt-1">No. Pend: {{ $casis->no_pendaftaran }}</div>
                        </div>

                        <form action="{{ route('casis.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold flex items-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Welcome Banner -->
        <div class="rounded-3xl p-5 md:p-8 mb-6 md:mb-8 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
            <div>
                <span class="px-3 py-1 bg-blue-50 text-blue-800 rounded-full text-xs font-extrabold tracking-wider mb-3 inline-block">
                    Terdaftar: {{ $casis->spmbPeriod ? $casis->spmbPeriod->gelombang . ' Tahun Ajaran ' . $casis->spmbPeriod->tahunAjaran->nama : 'Belum Ditentukan' }}
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold font-heading mb-2">Selamat Datang, {{ $casis->nama_lengkap }}!</h2>
                <p class="text-blue-800 text-sm max-w-xl">
                    Jurusan Pilihan: <strong>{{ $casis->jurusan ? $casis->jurusan->nama : 'Belum Dipilih' }}</strong> 
                    @if($casis->programKeunggulan)
                        ({{ $casis->programKeunggulan->nama }})
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('casis.print.kartu') }}" target="_blank" class="px-6 py-3 bg-white text-blue-700 hover:bg-blue-50 rounded-2xl font-bold text-xs flex items-center gap-2">
                    🖨️ Cetak Kartu Pendaftaran
                </a>
                <a href="{{ route('casis.print.formulir') }}" target="_blank" class="px-6 py-3 bg-blue-800 text-white hover:bg-blue-900 rounded-2xl font-bold text-xs flex items-center gap-2">
                    📄 Cetak Formulir
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Info Panel -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Biodata Lengkap Siswa Card -->
                <div class="bg-white rounded-3xl p-5 md:p-8 border border-slate-200 shadow-sm">
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 font-heading mb-4 md:mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">1</span>
                        Biodata Lengkap Siswa
                    </h3>

                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-0">
                        <table class="w-full text-sm text-left text-slate-600 h-fit">
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 w-1/3 md:w-2/5">Nama Lengkap</th>
                                    <td class="py-3 px-2">{{ $casis->nama_lengkap }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">NISN</th>
                                    <td class="py-3 px-2">{{ $casis->nisn }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">NIK</th>
                                    <td class="py-3 px-2">{{ $casis->nik ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Tempat, Tgl Lahir</th>
                                    <td class="py-3 px-2">{{ $casis->tempat_lahir }}, {{ $casis->tgl_lahir ? \Carbon\Carbon::parse($casis->tgl_lahir)->translatedFormat('d F Y') : '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Jenis Kelamin</th>
                                    <td class="py-3 px-2">{{ $casis->jk == 'L' ? 'Laki-Laki' : ($casis->jk == 'P' ? 'Perempuan' : $casis->jk) }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Asal Sekolah</th>
                                    <td class="py-3 px-2">{{ $casis->nama_sekolah }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <table class="w-full text-sm text-left text-slate-600 h-fit">
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 w-1/3 md:w-2/5">Nama Ayah</th>
                                    <td class="py-3 px-2">{{ $casis->nama_ayah ?: '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Nama Ibu</th>
                                    <td class="py-3 px-2">{{ $casis->nama_ibu ?: '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">No. HP (WA)</th>
                                    <td class="py-3 px-2">{{ $casis->no_hp_siswa }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">RT / RW</th>
                                    <td class="py-3 px-2">{{ $casis->rt ?? '-' }} / {{ $casis->rw ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Kecamatan</th>
                                    <td class="py-3 px-2">{{ $casis->kecamatan ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Kabupaten/Kota</th>
                                    <td class="py-3 px-2">{{ $casis->kab_kota ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Alamat Lengkap</th>
                                    <td class="py-3 px-2">{{ $casis->alamat_siswa }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Final Result Banner -->
                    <div class="p-6 rounded-2xl border-2 {{ $casis->status_kelulusan == 'Lulus' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : ($casis->status_kelulusan == 'Tidak Lulus' ? 'bg-red-50 border-red-200 text-red-900' : 'bg-amber-50 border-amber-200 text-amber-900') }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider opacity-75">Hasil Kelulusan Final</span>
                                <h4 class="text-xl md:text-2xl font-black font-heading mt-1">STATUS: {{ strtoupper($casis->status_kelulusan) }}</h4>
                                <p class="text-[11px] md:text-xs mt-1">
                                    @if($casis->status_kelulusan == 'Lulus')
                                        Selamat! Anda dinyatakan LULUS seleksi SPMB SMK Bhakti Praja Adiwerna. Silakan lakukan proses <strong>Daftar Ulang</strong> untuk mengamankan kuota jurusan Anda.
                                    @elseif($casis->status_kelulusan == 'Tidak Lulus')
                                        Mohon maaf, Anda belum memenuhi kualifikasi seleksi SPMB SMK Bhakti Praja Adiwerna.
                                    @else
                                        Proses seleksi sedang berjalan atau menunggu verifikasi Panitia Sekolah.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Ulang & Pembayaran Card -->
                    <div class="bg-white p-6 md:p-8 rounded-3xl border border-blue-100 shadow-lg shadow-blue-500/5 mb-8">
                        <h3 class="text-base md:text-lg font-bold text-slate-900 font-heading mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">2</span>
                            Daftar Ulang & Pembayaran
                        </h3>

                        <div class="space-y-4">
                            @if($casis->status_verifikasi != 'Diverifikasi')
                                <div class="p-6 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                                    <h4 class="text-slate-800 font-bold mb-2">Status Pendaftaran Masih Pending</h4>
                                    <p class="text-sm text-slate-500">Pendaftaran Anda sedang diverifikasi oleh panitia. Mohon tunggu proses verifikasi selesai.</p>
                                </div>
                            @elseif($casis->status_kelulusan == 'Pending')
                                <div class="p-6 bg-amber-50 border border-amber-200 rounded-2xl text-center">
                                    <h4 class="text-amber-800 font-bold mb-2">Menunggu Hasil Seleksi</h4>
                                    <p class="text-sm text-amber-600">Pembayaran daftar ulang dapat dilakukan setelah Anda dinyatakan LULUS seleksi.</p>
                                </div>
                            @elseif($casis->status_kelulusan == 'Tidak Lulus')
                                <div class="p-6 bg-red-50 border border-red-200 rounded-2xl text-center">
                                    <h4 class="text-red-800 font-bold mb-2">Mohon Maaf</h4>
                                    <p class="text-sm text-red-600">Anda dinyatakan Tidak Lulus seleksi sehingga tidak dapat melakukan pendaftaran ulang.</p>
                                </div>
                            @elseif($casis->status_kelulusan == 'Lulus')
                                @if(!$isJadwalDaftarUlang && $casis->status_daftar_ulang == 'Belum')
                                    <div class="p-6 rounded-2xl text-center">
                                        <h4 class="text-blue-800 font-bold mb-2">Jadwal Daftar Ulang Belum Dimulai</h4>
                                        <p class="text-sm text-blue-600">Pembayaran daftar ulang dapat dilakukan mulai tanggal {{ $jadwalDaftarUlang->translatedFormat('d F Y') }}.</p>
                                    </div>
                                @else
                                    <!-- Bagian Pembayaran Aktif -->
                                    <div class="p-6 rounded-2xl">
                                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                                            <div>
                                                <h4 class="text-blue-900 font-black text-xl mb-1">Rincian Pembayaran</h4>
                                                <p class="text-sm text-blue-700 font-medium mb-3">Biaya Daftar Ulang ({{ $casis->jurusan ? $casis->jurusan->nama : 'SMK BPA' }})</p>
                                                <div class="text-3xl font-black text-blue-600 font-heading">
                                                    Rp {{ number_format($casis->jurusan && $casis->jurusan->biaya_daftar_ulang > 0 ? $casis->jurusan->biaya_daftar_ulang : ($settings['biaya_daftar_ulang_global'] ?? 1500000), 0, ',', '.') }}
                                                </div>
                                            </div>

                                            @if($casis->status_daftar_ulang == 'Sudah')
                                                    <!-- STAMP IMAGE PLACEHOLDER -->
                                                    <!-- Silakan upload file stempel-lunas.png ke folder public/img/ -->
                                                    <img src="{{ asset('images/stempel-lunas.png') }}" alt="LUNAS" class="h-32 md:h-40 object-contain drop-shadow-sm transform -rotate-12 hover:scale-105 transition-transform duration-300">
                                            @else
                                                @php
                                                    $activePayment = $historyPembayaran->firstWhere('transaction_status', 'pending');
                                                @endphp

                                                <button @click="payWithMidtrans()" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm shadow-xl shadow-blue-500/30 transition transform hover:-translate-y-0.5 min-w-[200px]">
                                                    @if($activePayment)
                                                        💳 Lanjutkan Pembayaran
                                                    @else
                                                        💳 Bayar Daftar Ulang
                                                    @endif
                                                </button>
                                            @endif
                                        </div>

                                        @if($casis->status_daftar_ulang == 'Belum')
                                        <div class="mt-6 p-4 bg-white/60 rounded-xl text-xs text-blue-800 space-y-2">
                                            <p class="font-bold">Tata Cara Pembayaran Online:</p>
                                            <ol class="list-decimal pl-4 space-y-1">
                                                <li>Klik tombol pembayaran di atas.</li>
                                                <li>Pilih metode pembayaran yang diinginkan (Transfer Bank / Virtual Account / e-Wallet / QRIS / Kasir Sekolah / Minimarket).</li>
                                                <li>Selesaikan pembayaran sebelum batas waktu yang diberikan.</li>
                                                <li>Status pendaftaran akan otomatis berubah menjadi "LUNAS" setelah pembayaran berhasil diverifikasi.</li>
                                            </ol>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Tabel Riwayat Pembayaran -->
                                    @if($historyPembayaran->count() > 0)
                                    <div class="mt-6">
                                        <h4 class="text-sm font-bold text-slate-800 mb-3">Riwayat Transaksi</h4>
                                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                                            <table class="w-full text-left text-xs whitespace-nowrap">
                                                <thead class="bg-slate-50 text-slate-500 font-bold">
                                                    <tr>
                                                        <th class="px-4 py-3">Order ID</th>
                                                        <th class="px-4 py-3">Waktu Transaksi</th>
                                                        <th class="px-4 py-3">Metode</th>
                                                        <th class="px-4 py-3">Nominal</th>
                                                        <th class="px-4 py-3">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 text-slate-700">
                                                    @foreach($historyPembayaran as $trx)
                                                    <tr>
                                                        <td class="px-4 py-3 font-medium">{{ $trx->order_id }}</td>
                                                        <td class="px-4 py-3">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                                        <td class="px-4 py-3 uppercase">{{ str_replace('_', ' ', $trx->payment_type ?: '-') }}</td>
                                                        <td class="px-4 py-3 font-bold text-slate-900">Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                                                        <td class="px-4 py-3">
                                                            @if($trx->transaction_status == 'settlement')
                                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md font-bold text-[10px] uppercase">Sukses</span>
                                                            @elseif($trx->transaction_status == 'pending')
                                                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-md font-bold text-[10px] uppercase">Pending</span>
                                                            @else
                                                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-md font-bold text-[10px] uppercase">Gagal</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>

            </div>

            <!-- Right Document Upload & Details Panel -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Upload Dokumen -->
                <div class="bg-white rounded-3xl p-5 md:p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-base md:text-lg font-bold text-slate-900 font-heading mb-4">Upload Dokumen</h3>
                    
                    <div class="space-y-3">
                        @php
                            $dokumenList = [
                                'Pas Foto 3x4' => 'image/*',
                                'FC Kartu Keluarga' => 'image/*,.pdf',
                                'FC Akta Kelahiran' => 'image/*,.pdf',
                                'FC Ijazah / SKL' => 'image/*,.pdf'
                            ];
                        @endphp

                        @foreach($dokumenList as $namaDokumen => $accept)
                            @php
                                $isUploaded = $casis->berkas->where('nama_berkas', $namaDokumen)->first();
                            @endphp
                            <div class="flex items-center justify-between p-3 border border-slate-200 rounded-2xl {{ $isUploaded ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50' }}">
                                <div class="flex-1">
                                    <h4 class="text-xs font-bold {{ $isUploaded ? 'text-emerald-800' : 'text-slate-800' }}">{{ $namaDokumen }}</h4>
                                    @if($isUploaded)
                                        <div class="flex items-center gap-3 mt-1">
                                            <p class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Terunggah
                                            </p>
                                            <a href="{{ asset('storage/' . $isUploaded->path) }}" target="_blank" class="text-[10px] text-blue-600 font-bold hover:underline flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Lihat File
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-[10px] text-slate-500 mt-1">Max 2MB</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <form action="{{ route('casis.upload.berkas') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="jenis_berkas" value="{{ $namaDokumen }}">
                                        <label class="cursor-pointer px-4 py-2 {{ $isUploaded ? 'bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-50' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-md' }} rounded-xl text-[10px] font-bold transition inline-block text-center min-w-[80px]">
                                            {{ $isUploaded ? 'Ganti File' : 'Upload' }}
                                            <input type="file" name="file" accept="{{ $accept }}" class="hidden" onchange="this.form.submit()">
                                        </label>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- WhatsApp Group Card -->
                @if($casis->jurusan && $casis->jurusan->link_wa_group)
                <div class="bg-emerald-500 border border-emerald-100 rounded-3xl p-5 md:p-6 text-white shadow-sm mb-8 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
                    <div class="flex items-center gap-4">
                        <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.03A10.02 10.02 0 002.03 12c0 1.76.46 3.42 1.28 4.88L2 21.99l5.25-1.37a9.98 9.98 0 004.76 1.18c5.52 0 10.02-4.5 10.02-10.02S17.53 2.03 12.01 2.03zm5.42 14.5c-.24.67-1.38 1.25-1.93 1.34-.52.09-1.21.15-3.52-.8-2.79-1.16-4.6-4.04-4.74-4.23-.14-.19-1.13-1.51-1.13-2.88 0-1.38.72-2.06 1-2.36.27-.29.59-.36.78-.36.2 0 .39 0 .56.01.19.01.44-.07.67.5.24.58.84 2.04.91 2.18.07.14.12.3.03.49-.09.19-.14.3-.29.47-.14.16-.31.36-.43.49-.14.14-.28.29-.12.56.16.27.7 1.16 1.51 1.88.75.66 1.62.87 1.84.97.23.09.36.08.49-.07.13-.15.58-.67.73-.9.15-.24.3-.2.52-.12.22.08 1.39.66 1.63.78.24.12.4.18.45.28.06.1.06.57-.18 1.24z"/></svg>
                        <div>
                            <h3 class="text-base md:text-lg font-bold font-heading">Grup WhatsApp Jurusan {{ $casis->jurusan->kode }}</h3>
                        </div>
                    </div>
                    <a href="{{ $casis->jurusan->link_wa_group }}" target="_blank" class="px-6 py-3 bg-white text-emerald-700 font-bold text-xs rounded-2xl shadow-sm transition whitespace-nowrap">
                        Join Grup →
                    </a>
                </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        function studentDashboard() {
            return {
                payWithMidtrans() {
                    Swal.fire({
                        title: 'Memproses Transaksi...',
                        text: 'Menghubungkan ke Payment Gateway Midtrans',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    fetch('{{ route("casis.pay") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        if (data.success && data.snap_token) {
                            snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    Swal.fire('Pembayaran Berhasil!', 'Status daftar ulang Anda akan diperbarui.', 'success')
                                        .then(() => window.location.reload());
                                },
                                onPending: function(result) {
                                    Swal.fire('Pembayaran Pending', 'Selesaikan pembayaran sesuai instruksi.', 'info');
                                },
                                onError: function(result) {
                                    Swal.fire('Gagal Pembayaran', 'Terjadi kesalahan saat transaksi.', 'error');
                                }
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Gagal membuat transaksi', 'error');
                        }
                    })
                    .catch(err => {
                        Swal.close();
                        Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
                    });
                }
            }
        }
    </script>

</body>
</html>