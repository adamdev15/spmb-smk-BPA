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
    @php
        $midtransClientKey = \App\Services\MidtransService::getClientKey();
        $isMidtransProd = \App\Services\MidtransService::isProduction();
    @endphp
    @if(!empty($midtransClientKey))
        <script type="text/javascript"
            src="{{ $isMidtransProd ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ $midtransClientKey }}"></script>
    @endif

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
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak Kartu Pendaftaran
                </a>
                <a href="{{ route('casis.print.formulir') }}" target="_blank" class="px-6 py-3 bg-blue-800 text-white hover:bg-blue-900 rounded-2xl font-bold text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Cetak Formulir
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
                        <table class="w-full text-sm text-left text-slate-600 h-fit table-fixed">
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 w-1/3 md:w-2/5">Nama Lengkap</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->nama_lengkap }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">NISN</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->nisn }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">NIK</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->nik ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 align-top">Tempat, Tgl Lahir</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->tempat_lahir }}, {{ $casis->tgl_lahir ? \Carbon\Carbon::parse($casis->tgl_lahir)->translatedFormat('d F Y') : '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Jenis Kelamin</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->jk == 'L' ? 'Laki-Laki' : ($casis->jk == 'P' ? 'Perempuan' : $casis->jk) }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 align-top">Asal Sekolah</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->nama_sekolah }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <table class="w-full text-sm text-left text-slate-600 h-fit table-fixed">
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 w-1/3 md:w-2/5">Nama Ayah</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->nama_ayah ?: '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">Nama Ibu</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->nama_ibu ?: '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">No. HP (WA)</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->no_hp_siswa }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900">RT / RW</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->rt ?? '-' }} / {{ $casis->rw ?? '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 align-top">Provinsi</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->provinsi ? $casis->provinsi->nama_provinsi : '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 align-top">Kabupaten/Kota</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->kabupaten ? $casis->kabupaten->nama_kabkota : '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 align-top">Kecamatan</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->kecamatan ? $casis->kecamatan->nama_kec : '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 align-top">Kelurahan</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->kelurahan ? $casis->kelurahan->nama_desa_kel : '-' }}</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 px-2 font-bold text-slate-900 align-top">Alamat Lengkap</th>
                                    <td class="py-3 px-2 break-words whitespace-normal">{{ $casis->alamat_siswa }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Final Result Banner -->
                    <div class="p-6 rounded-2xl border-2 {{ $casis->status_kelulusan == 'Lulus' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : ($casis->status_kelulusan == 'Tidak Lulus' ? 'bg-red-50 border-red-200 text-red-900' : 'bg-amber-50 border-amber-200 text-amber-900') }}">
                          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
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
                        
                                @if($casis->status_kelulusan === 'Lulus')
                                <div class="flex-shrink-0 mt-4 md:mt-0">
                                    <a href="{{ route('casis.print.pengumuman') }}" target="_blank" class="px-5 py-3 bg-emerald-600 text-white hover:bg-emerald-700 rounded-xl font-bold text-xs flex items-center gap-2 shadow-sm transition hover:shadow-md animate-pulse hover:animate-none">
                                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                      Cetak Pengumuman
                                  </a>
                              </div>
                              @endif
                          </div>
                    </div>
                </div>

                                <!-- Daftar Ulang & Pembayaran Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm mb-8 relative overflow-hidden">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <h3 class="text-lg md:text-xl font-bold text-slate-900 font-heading flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">2</span>
                            Daftar Ulang & Pembayaran
                        </h3>

                        <!-- Status Badge -->
                        @if($casis->status_daftar_ulang === 'Sudah' || ($tagihanDaftarUlang && $tagihanDaftarUlang->isSettlement()))
                            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                LUNAS
                            </span>
                        @elseif($casis->isVerified())
                            @if($tagihanDaftarUlang && $tagihanDaftarUlang->isExpired())
                                <span class="px-4 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs font-extrabold uppercase tracking-wider">
                                    EXPIRED
                                </span>
                            @else
                                <span class="px-4 py-1.5 bg-amber-100 text-amber-800 rounded-full text-xs font-extrabold uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    MENUNGGU PEMBAYARAN
                                </span>
                            @endif
                        @else
                            <span class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-full text-xs font-extrabold uppercase tracking-wider">
                                MENUNGGU VERIFIKASI
                            </span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @if(!$casis->isVerified())
                            <div class="p-6 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h4 class="text-slate-800 font-bold mb-1">Status Pendaftaran Sedang Diverifikasi</h4>
                                <p class="text-xs text-slate-500 max-w-md mx-auto">Pendaftaran Anda sedang ditinjau oleh Panitia SPMB. Opsi pembayaran daftar ulang akan otomatis aktif setelah diverifikasi.</p>
                            </div>
                        @else
                            @if($casis->status_daftar_ulang === 'Sudah' || ($tagihanDaftarUlang && $tagihanDaftarUlang->isSettlement()))
                                <!-- TAMPILAN LUNAS / SETTLEMENT -->
                                <div class="p-6 bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl">
                                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                                        <div>
                                            <div class="flex items-center gap-2 text-emerald-700 font-extrabold text-sm mb-1 uppercase tracking-wider">
                                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                PEMBAYARAN BERHASIL DITERIMA
                                            </div>
                                            <p class="text-xs text-slate-600 mb-4">Selamat! Proses Daftar Ulang Anda di {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }} telah selesai dan kuota jurusan Anda telah terisi.</p>

                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-left">
                                                <div class="bg-white/80 p-3 rounded-xl border border-emerald-100">
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Nominal Lunas</span>
                                                    <span class="text-sm font-extrabold text-emerald-700 font-mono">
                                                        Rp {{ number_format($tagihanDaftarUlang ? $tagihanDaftarUlang->nominal : 1500000, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="bg-white/80 p-3 rounded-xl border border-emerald-100">
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Metode Pembayaran</span>
                                                    <span class="text-xs font-bold text-slate-800 capitalize">
                                                        @if($tagihanDaftarUlang && $tagihanDaftarUlang->tipe_pembayaran === 'offline')
                                                            Kasir ({{ $tagihanDaftarUlang->payment_type ?: 'Offline' }})
                                                        @else
                                                            Online ({{ $tagihanDaftarUlang && $tagihanDaftarUlang->payment_type ? strtoupper(str_replace('_', ' ', $tagihanDaftarUlang->payment_type)) : 'Payment Gateway' }})
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="bg-white/80 p-3 rounded-xl border border-emerald-100 col-span-2 sm:col-span-1">
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Tanggal Lunas</span>
                                                    <span class="text-xs font-bold text-slate-800">
                                                        {{ $tagihanDaftarUlang && $tagihanDaftarUlang->settlement_time ? \Carbon\Carbon::parse($tagihanDaftarUlang->settlement_time)->translatedFormat('d M Y, H:i') : ($casis->tgl_daftar_ulang ? \Carbon\Carbon::parse($casis->tgl_daftar_ulang)->translatedFormat('d M Y, H:i') : '-') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-shrink-0 text-center flex flex-col items-center gap-4">
                                            <div class="w-28 h-28 md:w-32 md:h-32 rounded-full border-4 border-dashed border-emerald-500 bg-white flex flex-col items-center justify-center p-2 text-emerald-600 shadow-md transform -rotate-12 hover:rotate-0 transition-transform">
                                                <span class="text-[9px] font-black tracking-widest uppercase text-emerald-400">SMK BPA</span>
                                                <span class="text-lg md:text-xl font-black tracking-tight text-emerald-700">LUNAS</span>
                                                <span class="text-[8px] font-bold text-slate-400 mt-0.5">{{ $tagihanDaftarUlang ? $tagihanDaftarUlang->order_id : 'DAFTAR ULANG' }}</span>
                                            </div>
                                            <a href="{{ route('casis.print.kwitansi') }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs shadow-md transition flex items-center gap-2">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                                Cetak Kwitansi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- TAMPILAN PENDING ATAU EXPIRED -->
                                <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl">
                                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
                                        <div>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-600 bg-blue-100/80 px-2.5 py-1 rounded-md inline-block mb-2">
                                                Tagihan Resmi SPMB
                                            </span>
                                            <h4 class="text-slate-900 font-black text-xl mb-1">
                                                Biaya Daftar Ulang ({{ $casis->jurusan ? $casis->jurusan->nama : 'SMK BPA' }})
                                            </h4>
                                            <div class="text-3xl font-black text-blue-700 font-heading mb-2">
                                                Rp {{ number_format($tagihanDaftarUlang ? $tagihanDaftarUlang->nominal : 1500000, 0, ',', '.') }}
                                            </div>
                                            <p class="text-xs text-slate-600 flex items-center gap-1.5 mb-4">
                                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Jatuh Tempo: <strong class="text-slate-900">{{ $tagihanDaftarUlang && $tagihanDaftarUlang->tgl_jatuh_tempo ? \Carbon\Carbon::parse($tagihanDaftarUlang->tgl_jatuh_tempo)->translatedFormat('d F Y') : ($jadwalDaftarUlang ? \Carbon\Carbon::parse($jadwalDaftarUlang)->translatedFormat('d F Y') : 'Sesuai Jadwal') }}</strong>
                                            </p>

                                            @if(isset($rincianBiaya) && $rincianBiaya->count() > 0)
                                            <div class="bg-white/60 rounded-xl p-2.5 sm:p-3 border border-blue-100 max-w-sm shadow-sm">
                                                <h5 class="text-[10px] sm:text-xs font-bold text-slate-700 mb-1.5 sm:mb-2 border-b border-blue-100 pb-1.5 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    Rincian Tagihan
                                                </h5>
                                                <ul class="text-[9.5px] sm:text-[11px] space-y-1 sm:space-y-1.5">
                                                    @foreach($rincianBiaya as $biaya)
                                                    <li class="flex justify-between items-center text-slate-600">
                                                        <span>{{ $biaya->nama_biaya }}</span>
                                                        <span class="font-bold text-slate-800">Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</span>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="w-full md:w-auto text-center md:text-right">
                                            <button @click="payWithMidtrans()" 
                                                    class="w-full md:w-auto px-2 md:px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-extrabold text-sm shadow-xl shadow-blue-500/30 transition transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-1">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                {{ $tagihanDaftarUlang && $tagihanDaftarUlang->isExpired() ? 'Pembayaran Baru' : 'Bayar Sekarang' }}
                                            </button>
                                            <span class="text-[10px] text-slate-400 block mt-4 font-medium">Mendukung QRIS, Virtual Account, & E-Wallet</span>
                                        </div>
                                    </div>

                                    <div class="mt-6 p-4 bg-white/80 rounded-xl text-xs text-slate-700 space-y-2 border border-blue-100">
                                        <p class="font-bold text-slate-900 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Panduan Pembayaran Daftar Ulang:
                                        </p>
                                        <ul class="list-disc pl-5 space-y-1 text-slate-600">
                                            <li><strong>Online:</strong> Klik tombol <em>BAYAR SEKARANG</em> di atas, lalu pilih metode transfer bank Virtual Account (BCA/BRI/BNI/Mandiri), QRIS, atau E-Wallet. Status otomatis lunas setelah pembayaran selesai.</li>
                                            <li><strong>Offline / Manual:</strong> Anda juga dapat melakukan pembayaran langsung secara tunai/debit ke loket kasir SMK Bhakti Praja Adiwerna dengan menunjukkan nomor pendaftaran <strong>{{ $casis->no_pendaftaran }}</strong>.</li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Tabel Riwayat Pembayaran -->
                        @if($historyPembayaran->count() > 0)
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Riwayat Transaksi Pembayaran
                            </h4>
                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="w-full text-left text-xs whitespace-nowrap">
                                    <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                                        <tr>
                                            <th class="px-4 py-3">No. Invoice / Order ID</th>
                                            <th class="px-4 py-3">Waktu Transaksi</th>
                                            <th class="px-4 py-3">Tipe / Metode</th>
                                            <th class="px-4 py-3">Nominal</th>
                                            <th class="px-4 py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 text-slate-700">
                                        @foreach($historyPembayaran as $trx)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $trx->order_id }}</td>
                                            <td class="px-4 py-3 text-slate-500">{{ $trx->created_at ? $trx->created_at->format('d M Y, H:i') : '-' }}</td>
                                            <td class="px-4 py-3 capitalize">
                                                @if($trx->tipe_pembayaran === 'offline')
                                                    <span class="px-2 py-0.5 bg-orange-50 text-orange-700 border border-orange-200 rounded text-[10px] font-bold">Offline / {{ $trx->payment_type ?: 'Kasir' }}</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[10px] font-bold">Online / {{ str_replace('_', ' ', $trx->payment_type ?: 'Payment Gateway') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-mono font-bold text-slate-900">Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3">
                                                @if($trx->transaction_status === 'settlement' || $trx->transaction_status === 'success')
                                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-md font-extrabold text-[10px] uppercase">LUNAS</span>
                                                @elseif($trx->transaction_status === 'pending')
                                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-md font-extrabold text-[10px] uppercase">PENDING</span>
                                                @elseif($trx->transaction_status === 'expire')
                                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md font-extrabold text-[10px] uppercase">EXPIRED</span>
                                                @else
                                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-md font-extrabold text-[10px] uppercase">GAGAL</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Info Panel -->
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
                <div class="bg-emerald-500 border border-emerald-100 rounded-2xl p-3 md:p-4 text-white shadow-sm mb-6 flex flex-row items-center justify-between gap-3 md:gap-4 max-w-sm">
                    <div class="flex items-center gap-2 md:gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.03A10.02 10.02 0 002.03 12c0 1.76.46 3.42 1.28 4.88L2 21.99l5.25-1.37a9.98 9.98 0 004.76 1.18c5.52 0 10.02-4.5 10.02-10.02S17.53 2.03 12.01 2.03zm5.42 14.5c-.24.67-1.38 1.25-1.93 1.34-.52.09-1.21.15-3.52-.8-2.79-1.16-4.6-4.04-4.74-4.23-.14-.19-1.13-1.51-1.13-2.88 0-1.38.72-2.06 1-2.36.27-.29.59-.36.78-.36.2 0 .39 0 .56.01.19.01.44-.07.67.5.24.58.84 2.04.91 2.18.07.14.12.3.03.49-.09.19-.14.3-.29.47-.14.16-.31.36-.43.49-.14.14-.28.29-.12.56.16.27.7 1.16 1.51 1.88.75.66 1.62.87 1.84.97.23.09.36.08.49-.07.13-.15.58-.67.73-.9.15-.24.3-.2.52-.12.22.08 1.39.66 1.63.78.24.12.4.18.45.28.06.1.06.57-.18 1.24z"/></svg>
                        <div>
                            <h3 class="text-[11px] md:text-sm font-bold font-heading m-0 leading-tight">Grup WhatsApp Jurusan {{ $casis->jurusan->kode }}</h3>
                        </div>
                    </div>
                    <a href="{{ $casis->jurusan->link_wa_group }}" target="_blank" class="px-3 py-1.5 md:px-4 md:py-2 bg-white text-emerald-700 font-bold text-[10px] md:text-xs rounded-xl shadow-sm transition whitespace-nowrap flex items-center gap-1">
                        Join Grup
                        <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
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