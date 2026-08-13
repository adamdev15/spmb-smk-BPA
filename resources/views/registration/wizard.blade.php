<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pendaftaran SPMB - SMK Bhakti Praja Adiwerna</title>
    @if(!empty($settings['logo']))
        <link rel="icon" href="{{ asset('storage/' . $settings['logo']) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('images/logo_smk_bpa.png') }}" type="image/png">
    @endif
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col" x-data="registrationWizard()">

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 flex items-center justify-center font-extrabold text-xl text-slate-900 group-hover:scale-105 transition transform">
                        @if(!empty($settings['logo']))
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            BPA
                        @endif
                    </div>
                <div>
                    <h1 class="text-lg font-bold font-heading text-slate-900 leading-none">SPMB SMK Bhakti Praja Adiwerna</h1>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold mt-0.5">Formulir Pendaftaran Murid Baru</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100 hidden sm:inline-block">
                    {{ $activePeriod ? 'Tahun Ajaran ' . $activePeriod->tahunAjaran->nama . ' - ' . $activePeriod->gelombang : 'Pendaftaran Ditutup' }}
                </span>
                <a href="{{ route('landing') }}" class="text-xs font-bold text-slate-500 hover:text-red-600 transition">
                    Batal
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow py-8 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto">

            @if(!$activePeriod)
            <div class="bg-red-50 border-l-4 border-red-500 p-8 rounded-2xl text-center shadow-sm mt-10">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-red-700 font-heading mb-2">Pendaftaran SPMB Ditutup</h2>
                <p class="text-red-600 mb-6">Mohon maaf, saat ini belum ada gelombang pendaftaran yang aktif atau periode pendaftaran telah berakhir.</p>
                <a href="{{ route('landing') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-md">Kembali ke Beranda</a>
            </div>
            @else
            <!-- Step Progress Indicator -->
            <div class="mb-8 px-4">
                <div class="relative flex items-center justify-between max-w-3xl mx-auto">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -z-10 rounded-full transform -translate-y-1/2"></div>
                    <div class="absolute top-1/2 left-0 h-1 bg-blue-600 -z-10 rounded-full transition-all duration-500 ease-in-out transform -translate-y-1/2"
                         :style="'width: ' + ((step - 1) / 3 * 100) + '%'"></div>

                    <template x-for="(label, index) in ['Syarat', 'Pilihan Jurusan Keahlian', 'Data Siswa & Ortu', 'Upload Dokumen']">
                        <div class="flex flex-col items-center cursor-pointer" @click="step > index + 1 ? step = index + 1 : null">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-white"
                                 :class="{
                                     'border-blue-600 bg-blue-600 text-white shadow-lg shadow-blue-500/30 scale-110': step > index + 1,
                                     'border-blue-600 text-blue-600 bg-white ring-4 ring-blue-100 scale-110': step === index + 1,
                                     'border-slate-300 text-slate-400': step < index + 1
                                 }">
                                <span x-show="step <= index + 1" x-text="index + 1"></span>
                                <svg x-show="step > index + 1" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="mt-2 text-[11px] font-bold uppercase tracking-wider hidden sm:block"
                                  :class="step >= index + 1 ? 'text-blue-600' : 'text-slate-400'" x-text="label"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Main Form Card -->
            <form @submit.prevent="submitForm" class="bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden relative">
                
                <!-- Loading Overlay -->
                <div x-show="submitting" x-cloak class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center backdrop-blur-sm">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                    <p class="mt-4 text-blue-800 font-bold text-sm animate-pulse">Sedang Memproses Pendaftaran...</p>
                </div>

                <div class="p-6 sm:p-10 min-h-[480px]">

                    <!-- STEP 1: PERSYARATAN -->
                    <div x-show="step === 1" x-transition>
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-extrabold text-slate-900 font-heading">Syarat & Ketentuan SPMB</h2>
                            <p class="text-slate-500 text-sm mt-1">Pahami persyaratan pendaftaran sebelum melengkapi formulir.</p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-sm text-slate-600 mb-8 max-h-80 overflow-y-auto leading-relaxed">
                            {!! $settings['ketentuan_spmb'] ?? '<ul><li>Calon siswa wajib melengkapi data pendaftaran secara akurat.</li><li>Wajib mengunggah Pas Foto 3x4 berwarna.</li><li>Mengikuti tes psikotes & seleksi fisik (pemeriksaan tindik, tato, buta warna).</li><li>Daftar ulang dilakukan setelah siswa dinyatakan LULUS.</li></ul>' !!}
                        </div>

                        <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition"
                               :class="formData.agreed ? 'border-blue-600 bg-blue-50/50' : 'border-slate-200 hover:border-blue-300'">
                            <input type="checkbox" x-model="formData.agreed" class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="font-semibold text-slate-700 text-sm">Saya menyetujui seluruh syarat & ketentuan pendaftaran SPMB SMK Bhakti Praja Adiwerna.</span>
                        </label>
                    </div>

                    <!-- STEP 2: PILIHAN JURUSAN & PROGRAM KEUNGGULAN -->
                    <div x-show="step === 2" x-cloak x-transition>
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="text-xl font-bold text-slate-900 font-heading flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                                Pilihan Program Keahlian (Jurusan) & Keunggulan
                            </h2>
                        </div>

                        <div class="space-y-6">
                            <!-- Jurusan Selector -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                                    Pilih Program Keahlian (Jurusan) <span class="text-red-500">*</span>
                                </label>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                                    @foreach($jurusans as $j)
                                    <label class="relative p-3 sm:p-5 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between"
                                           :class="formData.jurusan_id == '{{ $j->id }}' ? 'border-blue-600 bg-blue-50/50 shadow-md' : 'border-slate-200 hover:border-blue-300'">
                                        <input type="radio" name="jurusan_id" value="{{ $j->id }}" x-model="formData.jurusan_id" class="sr-only">
                                        <div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 bg-blue-600 text-white rounded-md sm:rounded-lg text-[10px] sm:text-xs font-extrabold font-heading">
                                                    {{ $j->kode }}
                                                </span>
                                                <span class="text-[9px] sm:text-xs font-bold text-emerald-600">Sisa: {{ $j->sisa_kuota }}</span>
                                            </div>
                                            <h4 class="text-sm sm:text-base font-bold text-slate-900 font-heading mb-1 leading-tight">{{ $j->nama }}</h4>
                                            <p class="text-[10px] sm:text-xs text-slate-500 line-clamp-2 hidden sm:block">{{ $j->deskripsi }}</p>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Program Keunggulan Selector -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Pilihan Program Keunggulan Industri (Opsional)
                                </label>
                                <select x-model="formData.program_keunggulan_id" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                                    <option value="">-- Pilih Program Keunggulan --</option>
                                    @foreach($programs as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: DATA SISWA & ORANG TUA -->
                    <div x-show="step === 3" x-cloak x-transition>
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="text-xl font-bold text-slate-900 font-heading flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                                Biodata Siswa & Orang Tua
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.nama_lengkap" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="Sesuai Ijazah/Akta">
                                <p x-show="errors.nama_lengkap" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nama_lengkap"></p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">NISN <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.nisn" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="10 Digit NISN">
                                <p x-show="errors.nisn" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nisn"></p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">NIK Siswa <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.nik" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="16 Digit NIK" maxlength="16">
                                <p x-show="formData.nik.length > 0 && formData.nik.length !== 16" class="text-red-500 text-[10px] mt-1 font-bold">NIK harus 16 digit</p>
                                <p x-show="errors.nik" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nik"></p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">No. WhatsApp Siswa <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.no_hp_siswa" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="08123456789">
                                <p x-show="errors.no_hp_siswa" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.no_hp_siswa"></p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select x-model="formData.jk" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm">
                                    <option value="">Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <p x-show="errors.jk" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.jk"></p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Agama <span class="text-red-500">*</span></label>
                                <select x-model="formData.agama" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm">
                                    <option value="">Pilih Agama...</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                                <p x-show="errors.agama" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.agama"></p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tempat / Tanggal Lahir <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" x-model="formData.tempat_lahir" placeholder="Kota Lahir" class="rounded-xl border-slate-300 py-2.5 bg-slate-50 text-sm">
                                    <input type="date" x-model="formData.tgl_lahir" class="rounded-xl border-slate-300 py-2.5 bg-slate-50 text-sm">
                                </div>
                                <p x-show="errors.tempat_lahir || errors.tgl_lahir" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.tempat_lahir || errors.tgl_lahir"></p>
                            </div>
                            
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Sekolah Asal (SMP/MTs) <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.nama_sekolah" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 text-sm" placeholder="Contoh: SMPN 1 Adiwerna">
                                <p x-show="errors.nama_sekolah" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nama_sekolah"></p>
                            </div>
                            
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Alamat Sekolah Asal <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.alamat_sekolah" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 text-sm" placeholder="Contoh: Jl. Merdeka No. 1, Kab. Tegal">
                                <p x-show="errors.alamat_sekolah" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.alamat_sekolah"></p>
                            </div>

                            <div class="md:col-span-2 pt-4 border-t border-slate-100">
                                <h4 class="font-bold text-slate-900 text-sm mb-3">Data Orang Tua / Wali</h4>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Ayah Kandung <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.nama_ayah" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 text-sm">
                                <p x-show="errors.nama_ayah" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nama_ayah"></p>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Ibu Kandung <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.nama_ibu" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 text-sm">
                                <p x-show="errors.nama_ibu" class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nama_ibu"></p>
                            </div>

                            <div class="md:col-span-2">
                                <h4 class="block font-bold text-slate-700 mb-3 border-b border-slate-100 pb-2">Alamat Tempat Tinggal <span class="text-red-500">*</span></h4>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <label class="block font-semibold text-slate-600 text-[11px] uppercase tracking-wider mb-1.5">RT</label>
                                        <input type="text" x-model="formData.rt" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="01">
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-600 text-[11px] uppercase tracking-wider mb-1.5">RW</label>
                                        <input type="text" x-model="formData.rw" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="02">
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-600 text-[11px] uppercase tracking-wider mb-1.5">Kecamatan</label>
                                        <input type="text" x-model="formData.kecamatan" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="Contoh: Adiwerna">
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-600 text-[11px] uppercase tracking-wider mb-1.5">Kab / Kota</label>
                                        <input type="text" x-model="formData.kab_kota" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="Contoh: Kab. Tegal">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block font-semibold text-slate-600 text-[11px] uppercase tracking-wider mb-1.5">Alamat Lengkap (Jalan / Gang / Blok)</label>
                                    <textarea x-model="formData.alamat_siswa" rows="2" required class="w-full rounded-xl border-slate-300 py-2.5 bg-slate-50 focus:bg-white text-sm" placeholder="Contoh: Jl. Anggrek No. 15, Desa Bulakwaru"></textarea>
                                </div>

                                <p x-show="errors.rt || errors.rw || errors.kecamatan || errors.kab_kota || errors.alamat_siswa" class="text-red-500 text-[10px] mt-1 font-bold">Harap lengkapi seluruh kolom alamat.</p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: UPLOAD DOKUMEN -->
                    <div x-show="step === 4" x-cloak x-transition>
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="text-xl font-bold text-slate-900 font-heading flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                                Upload Dokumen Persyaratan
                            </h2>
                        </div>

                        <!-- Info Text -->
                        <div class="mb-5 bg-amber-50 border border-amber-200 p-4 rounded-xl text-amber-800 text-xs leading-relaxed">
                            <strong>Informasi Pengumpulan Berkas Fisik:</strong><br>
                            Selain mengunggah dokumen digital di bawah ini, calon siswa diwajibkan untuk menyerahkan berkas fisik (fotokopi) ke Panitia SPMB di SMK Bhakti Praja Adiwerna pada jam kerja.
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <!-- Pas Foto -->
                            <div class="p-5 bg-white rounded-2xl border border-slate-200 flex flex-col justify-between hover:border-blue-400 transition">
                                <div>
                                    <label class="block font-bold text-slate-900 text-sm mb-1">Pas Foto 3x4 Berwarna <span class="text-red-500">* (Wajib)</span></label>
                                    <p class="text-slate-500 text-xs mb-4 leading-relaxed">Foto berwarna latar belakang merah/biru. Format JPG/PNG max 2MB. Silakan ambil dari kamera atau unggah file.</p>
                                </div>
                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="px-4 py-2.5 bg-blue-50 text-blue-700 font-bold text-xs rounded-xl group-hover:bg-blue-100 transition flex-shrink-0 flex items-center gap-2 border border-blue-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            Pilih File
                                        </div>
                                        <span class="text-xs text-slate-500 truncate font-medium flex-1" x-text="berkasFiles.pas_foto ? berkasFiles.pas_foto.name : 'No file chosen'"></span>
                                        <input type="file" @change="handleFileUpload($event, 'pas_foto')" accept="image/*" class="hidden">
                                    </label>
                                    <p x-show="errors.pas_foto" class="text-red-500 text-[10px] mt-2 font-bold" x-text="errors.pas_foto"></p>
                                </div>
                            </div>

                            <!-- Upload KK -->
                            <div class="p-5 bg-white rounded-2xl border border-slate-200 flex flex-col justify-between hover:border-blue-400 transition">
                                <div>
                                    <label class="block font-bold text-slate-900 text-sm mb-1">FC Kartu Keluarga (KK)</label>
                                    <p class="text-slate-500 text-xs mb-4 leading-relaxed">Scan/foto Kartu Keluarga yang jelas dan mudah dibaca. Format JPG/PNG/PDF max 2MB.</p>
                                </div>
                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="px-4 py-2.5 bg-blue-50 text-blue-700 font-bold text-xs rounded-xl group-hover:bg-blue-100 transition flex-shrink-0 flex items-center gap-2 border border-blue-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            Pilih File
                                        </div>
                                        <span class="text-xs text-slate-500 truncate font-medium flex-1" x-text="berkasFiles.fc_kk ? berkasFiles.fc_kk.name : 'No file chosen'"></span>
                                        <input type="file" @change="handleFileUpload($event, 'fc_kk')" accept="image/*,.pdf" class="hidden">
                                    </label>
                                    <p x-show="errors.fc_kk" class="text-red-500 text-[10px] mt-2 font-bold" x-text="errors.fc_kk"></p>
                                </div>
                            </div>

                            <!-- Upload Akta -->
                            <div class="p-5 bg-white rounded-2xl border border-slate-200 flex flex-col justify-between hover:border-blue-400 transition">
                                <div>
                                    <label class="block font-bold text-slate-900 text-sm mb-1">FC Akta Kelahiran</label>
                                    <p class="text-slate-500 text-xs mb-4 leading-relaxed">Scan/foto Akta Kelahiran yang jelas dan mudah dibaca. Format JPG/PNG/PDF max 2MB.</p>
                                </div>
                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="px-4 py-2.5 bg-slate-50 text-slate-700 font-bold text-xs rounded-xl group-hover:bg-slate-100 transition flex-shrink-0 flex items-center gap-2 border border-slate-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            Pilih File
                                        </div>
                                        <span class="text-xs text-slate-500 truncate font-medium flex-1" x-text="berkasFiles.fc_akta ? berkasFiles.fc_akta.name : 'No file chosen'"></span>
                                        <input type="file" @change="handleFileUpload($event, 'fc_akta')" accept="image/*,.pdf" class="hidden">
                                    </label>
                                    <p x-show="errors.fc_akta" class="text-red-500 text-[10px] mt-2 font-bold" x-text="errors.fc_akta"></p>
                                </div>
                            </div>

                            <!-- Upload Ijazah -->
                            <div class="p-5 bg-white rounded-2xl border border-slate-200 flex flex-col justify-between hover:border-blue-400 transition">
                                <div>
                                    <label class="block font-bold text-slate-900 text-sm mb-1">FC Ijazah / SKL (2 Lembar)</label>
                                    <p class="text-slate-500 text-xs mb-4 leading-relaxed">Scan/foto Ijazah atau SKL yang jelas dan mudah dibaca. Format JPG/PNG/PDF max 2MB.</p>
                                </div>
                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="px-4 py-2.5 bg-slate-50 text-slate-700 font-bold text-xs rounded-xl group-hover:bg-slate-100 transition flex-shrink-0 flex items-center gap-2 border border-slate-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            Pilih File
                                        </div>
                                        <span class="text-xs text-slate-500 truncate font-medium flex-1" x-text="berkasFiles.fc_ijazah ? berkasFiles.fc_ijazah.name : 'No file chosen'"></span>
                                        <input type="file" @change="handleFileUpload($event, 'fc_ijazah')" accept="image/*,.pdf" class="hidden">
                                    </label>
                                    <p x-show="errors.fc_ijazah" class="text-red-500 text-[10px] mt-2 font-bold" x-text="errors.fc_ijazah"></p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Navigation Buttons Footer -->
                <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex justify-between items-center">
                    <button type="button" x-show="step > 1" @click="step--" class="px-6 py-2.5 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition">
                        ← Kembali
                    </button>

                    <div class="ml-auto">
                        <button type="button" x-show="step < 4" @click="nextStep()" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-500/20 transition">
                            Lanjut →
                        </button>

                        <button type="submit" x-show="step === 4" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-500/20 transition">
                            Submit Pendaftaran
                        </button>
                    </div>
                </div>

            </form>
        </div>
        @endif
    </main>

    <script>
        function registrationWizard() {
            return {
                step: 1,
                submitting: false,
                berkasFiles: {
                    pas_foto: null,
                    fc_kk: null,
                    fc_akta: null,
                    fc_ijazah: null
                },
                errors: {},
                formData: {
                    agreed: false,
                    jurusan_id: '{{ old("jurusan_id") }}',
                    program_keunggulan_id: '{{ old("program_keunggulan_id") }}',
                    nama_lengkap: '{{ old("nama_lengkap") }}',
                    nisn: '{{ old("nisn") }}',
                    nik: '{{ old("nik") }}',
                    agama: '{{ old("agama") }}',
                    jk: '{{ old("jk") }}',
                    tempat_lahir: '{{ old("tempat_lahir") }}',
                    tgl_lahir: '{{ old("tgl_lahir") }}',
                    no_hp_siswa: '{{ old("no_hp_siswa") }}',
                    nama_ayah: '{{ old("nama_ayah") }}',
                    nama_ibu: '{{ old("nama_ibu") }}',
                    rt: '{{ old("rt") }}',
                    rw: '{{ old("rw") }}',
                    kecamatan: '{{ old("kecamatan") }}',
                    kab_kota: '{{ old("kab_kota") }}',
                    alamat_siswa: '{{ old("alamat_siswa") }}',
                    nama_sekolah: '{{ old("nama_sekolah") }}',
                    alamat_sekolah: '{{ old("alamat_sekolah") }}',
                },

                nextStep() {
                    // Reset erors
                    this.errors = {};
                    
                    if (this.step === 1 && !this.formData.agreed) {
                        Swal.fire('Perhatian', 'Anda harus menyetujui syarat & ketentuan.', 'warning');
                        return;
                    }
                    if (this.step === 2 && !this.formData.jurusan_id) {
                        Swal.fire('Perhatian', 'Silakan pilih Program Keahlian (Jurusan) terlebih dahulu.', 'warning');
                        return;
                    }
                    if (this.step === 3) {
                        // Basic JS validation
                        if (!this.formData.nama_lengkap || !this.formData.nisn || !this.formData.nik || !this.formData.agama || !this.formData.nama_sekolah || !this.formData.alamat_sekolah || !this.formData.alamat_siswa || !this.formData.rt || !this.formData.rw || !this.formData.kecamatan || !this.formData.kab_kota) {
                            Swal.fire('Perhatian', 'Harap lengkapi semua kolom wajib (*) pada Biodata.', 'warning');
                            return;
                        }
                        if (this.formData.nik.length !== 16) {
                            Swal.fire('Perhatian', 'NIK Siswa harus 16 digit.', 'warning');
                            return;
                        }
                    }
                    this.step++;
                },

                handleFileUpload(e, type) {
                    this.berkasFiles[type] = e.target.files[0];
                    if (type === 'pas_foto') {
                        this.capturedPhoto = null; // reset hasil kamera jika mereka unggah file
                    }
                },

                submitForm() {
                    this.submitting = true;
                    this.errors = {}; // clear errors

                    let formPayload = new FormData();
                    for (let key in this.formData) {
                        formPayload.append(key, this.formData[key]);
                    }
                    
                    if (this.capturedPhoto) {
                        formPayload.append('pas_foto_base64', this.capturedPhoto);
                    }
                    
                    for (let fileKey in this.berkasFiles) {
                        if (this.berkasFiles[fileKey]) {
                            formPayload.append(fileKey, this.berkasFiles[fileKey]);
                        }
                    }

                    fetch('{{ route("pendaftaran.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formPayload
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.submitting = false;
                        if (data.success) {
                            Swal.fire('Pendaftaran Berhasil!', 'Akun pendaftaran Anda telah dibuat.', 'success')
                                .then(() => {
                                    window.location.href = data.redirect || '{{ route("casis.dashboard") }}';
                                });
                        } else {
                            if (data.errors) {
                                this.errors = data.errors;
                                Swal.fire('Data Tidak Valid', 'Silakan periksa kembali form isian Anda (terutama error teks merah).', 'error');
                                
                                // Return to step that has error
                                if (this.errors.pas_foto || this.errors.fc_kk || this.errors.fc_akta || this.errors.fc_ijazah) {
                                    this.step = 4;
                                } else if (this.errors.nama_lengkap || this.errors.nisn || this.errors.nik || this.errors.agama) {
                                    this.step = 3;
                                }
                            } else {
                                Swal.fire('Gagal Pendaftaran', data.message || 'Terjadi kesalahan sistem', 'error');
                            }
                        }
                    })
                    .catch(err => {
                        this.submitting = false;
                        Swal.fire('Error', 'Terjadi kesalahan sistem/jaringan.', 'error');
                    });
                }
            }
        }
    </script>
</body>
</html>