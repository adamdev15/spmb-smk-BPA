<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Penerimaan Murid Baru (SPMB) SMK Bhakti Praja Adiwerna. Program Keahlian TKRO, TJKT, AKL, TBSM & Kelas Binaan Industri.">
    <link rel="icon" href="{{ asset('images/logo_smk_bpa.png') }}" type="image/png">
    <meta name="description" content="SPMB SMK Bhakti Praja Adiwerna - Sistem Penerimaan Murid Baru dari SMK Bhakti Praja Adiwerna">
    <meta name="keywords" content="SPMB SMK Bhakti Praja Adiwerna, Sistem Penerimaan Murid Baru, SMK Bhakti Praja Adiwerna">
    <meta name="author" content="SPMB SMK Bhakti Praja Adiwerna">
    <title>SPMB Admin - {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}</title>
    @if(!empty($settings['logo']))
        <link rel="icon" href="{{ asset('storage/' . $settings['logo']) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('images/logo_smk_bpa.png') }}" type="image/png">
    @endif
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        .glow-bg {
            background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
    </style>
</head>

<body class="antialiased bg-slate-50 text-slate-800 glow-bg selection:bg-blue-600 selection:text-white" x-data="landingPage()" x-init="init()">

    <!-- Navbar (Transparent, floating over hero) -->
    <nav x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 50)"
         :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm text-slate-800 border-b border-slate-100' : 'bg-transparent text-white border-b border-transparent'"
         class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Logo & School Name -->
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 flex items-center justify-center font-extrabold text-xl group-hover:scale-105 transition transform">
                        @if(!empty($settings['logo']))
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            <div class="bg-white/20 rounded-full w-full h-full flex items-center justify-center" :class="scrolled ? 'text-slate-900' : 'text-white'">BPA</div>
                        @endif
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight font-heading block leading-none transition-colors"
                              :class="scrolled ? 'text-slate-900' : 'text-white'">
                            {{ $settings['nama_sekolah'] ?? 'SMK BPA' }}
                        </span>
                        <span class="text-xs font-medium tracking-wide transition-colors"
                              :class="scrolled ? 'text-slate-500' : 'text-slate-300'">
                            SPMB Tahun Ajaran {{ $activePeriod ? $activePeriod->tahunAjaran->nama : '2026/2027' }}
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex space-x-8 items-center font-medium text-sm transition-colors" :class="scrolled ? 'text-slate-600' : 'text-slate-200'">
                    <a href="#jurusan" class="transition hover:text-blue-500">Program Keahlian</a>
                    <a href="#keunggulan" class="transition hover:text-blue-500">Program Keunggulan</a>
                    <a href="#alur" class="transition hover:text-blue-500">Alur SPMB</a>
                    <a href="#jadwal" class="transition hover:text-blue-500">Jadwal</a>
                    <a href="#kontak" class="transition hover:text-blue-500">Kontak</a>
                </div>

                <!-- Action Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('casis.login') }}" class="font-semibold text-sm transition px-4 py-2 hover:text-blue-500" :class="scrolled ? 'text-slate-700' : 'text-white'">
                        Login
                    </a>
                    <button @click="handleDaftarClick()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold text-sm shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5 active:translate-y-0">
                        Daftar
                    </button>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="openMobile = true" class="p-2 rounded-lg focus:outline-none transition-colors" :class="scrolled ? 'text-slate-600 hover:bg-slate-100' : 'text-white hover:bg-white/10'">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Menu -->
    <div x-show="openMobile" x-cloak class="md:hidden relative z-[70]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        
        <!-- Background Overlay -->
        <div x-show="openMobile" 
             x-transition:enter="ease-in-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in-out duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
             @click="openMobile = false"></div>

        <!-- Sidebar Panel -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 left-0 flex max-w-full">
                    <div x-show="openMobile" 
                         @click.away="openMobile = false"
                         x-transition:enter="transform transition ease-in-out duration-300" 
                         x-transition:enter-start="-translate-x-full" 
                         x-transition:enter-end="translate-x-0" 
                         x-transition:leave="transform transition ease-in-out duration-300" 
                         x-transition:leave-start="translate-x-0" 
                         x-transition:leave-end="-translate-x-full" 
                         class="pointer-events-auto w-[75vw] max-w-sm">
                         
                        <div class="flex h-full flex-col bg-white shadow-2xl">
                            
                            <!-- Sidebar Header -->
                            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    @if(!empty($settings['logo']))
                                        <div class="w-8 h-8 flex items-center justify-center">
                                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain">
                                        </div>
                                    @endif
                                    <span class="text-lg font-bold text-slate-900 tracking-tight font-heading block leading-none">
                                        {{ $settings['nama_sekolah'] ?? 'SMK BPA' }}
                                    </span>
                                </div>
                                <button type="button" @click="openMobile = false" class="rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 p-1 focus:outline-none transition">
                                    <span class="sr-only">Tutup menu</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Sidebar Links -->
                            <div class="relative flex-1 px-6 py-6 overflow-y-auto space-y-1">
                                <a href="#jurusan" @click="openMobile = false" class="block py-3.5 text-base font-semibold text-slate-700 hover:text-blue-600 border-b border-slate-50 transition">Program Keahlian</a>
                                <a href="#keunggulan" @click="openMobile = false" class="block py-3.5 text-base font-semibold text-slate-700 hover:text-blue-600 border-b border-slate-50 transition">Program Keunggulan</a>
                                <a href="#alur" @click="openMobile = false" class="block py-3.5 text-base font-semibold text-slate-700 hover:text-blue-600 border-b border-slate-50 transition">Alur SPMB</a>
                                <a href="#jadwal" @click="openMobile = false" class="block py-3.5 text-base font-semibold text-slate-700 hover:text-blue-600 border-b border-slate-50 transition">Jadwal SPMB</a>
                                <a href="#kontak" @click="openMobile = false" class="block py-3.5 text-base font-semibold text-slate-700 hover:text-blue-600 transition">Kontak Panitia</a>
                            </div>
                            
                            <!-- Sidebar Footer Action -->
                            <div class="p-6 border-t border-slate-100 flex flex-col gap-3 bg-slate-50/50">
                                <a href="{{ route('casis.login') }}" class="w-full flex items-center justify-center py-3.5 rounded-full border border-slate-300 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 transition shadow-sm">
                                    Login Siswa
                                </a>
                                <button @click="handleDaftarClick(); openMobile = false" class="w-full flex items-center justify-center py-3.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/25 transition transform hover:-translate-y-0.5">
                                    Daftar Sekarang
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $heroImages = [];
        if (!empty($settings['landing_hero'])) {
            $decoded = json_decode($settings['landing_hero'], true);
            if (is_array($decoded)) {
                $heroImages = $decoded;
            } else {
                $heroImages = [$settings['landing_hero']]; // backward compatibility
            }
        }
    @endphp

    <!-- HERO SECTION (Inspired by Reference Design with Alpine Slider) -->
    <section x-data="{ 
            currentSlide: 0, 
            slides: {{ json_encode($heroImages) }},
            timer: null,
            init() {
                if(this.slides.length > 1) {
                    this.startTimer();
                }
            },
            startTimer() {
                this.timer = setInterval(() => { this.nextSlide() }, 5000);
            },
            resetTimer() {
                clearInterval(this.timer);
                if(this.slides.length > 1) this.startTimer();
            },
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            },
            prevSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            }
        }" 
        class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        
        <!-- Background Image with Gradient Overlay -->
        <div class="absolute inset-0 z-0 bg-slate-900">
            <template x-if="slides.length > 0">
                <template x-for="(slide, index) in slides" :key="index">
                    <img :src="'{{ asset('storage') }}/' + slide" 
                         alt="Hero Visual" 
                         x-show="currentSlide === index"
                         x-transition:enter="transition-all duration-1000 ease-in-out"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition-all duration-1000 ease-in-out absolute inset-0"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="w-full h-full object-cover">
                </template>
            </template>

            <!-- Gradient Overlay (Dark on the left, fading to transparent on the right) -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
        </div>

        <!-- Slider Arrows -->
        <template x-if="slides.length > 1">
            <div>
                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-10 hidden lg:block">
                    <button @click="prevSlide(); resetTimer();" class="w-12 h-12 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white/10 transition focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 z-10 hidden lg:block">
                    <button @click="nextSlide(); resetTimer();" class="w-12 h-12 rounded-full border border-white/30 flex items-center justify-center text-white hover:bg-white/10 transition focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
                
                <!-- Pagination Dots -->
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="currentSlide = index; resetTimer();" 
                                class="w-2.5 h-2.5 rounded-full transition-colors focus:outline-none"
                                :class="currentSlide === index ? 'bg-white' : 'bg-white/40 hover:bg-white/60'">
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full mt-10 md:mt-0">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Text Column -->
                <div class="lg:col-span-8 text-left" data-aos="fade-right">
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white tracking-tight leading-[1.1] mb-6 font-heading drop-shadow-md">
                        Mewujudkan Lulusan <br/>
                        <span class="text-blue-400">Kompeten & Siap Kerja</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-200 leading-relaxed mb-8 max-w-2xl font-medium drop-shadow-md">
                        {{ $settings['deskripsi_hero'] ?? 'Selamat datang di SPMB SMK Bhakti Praja Adiwerna. Sekolah Kejuruan Unggulan yang didukung Kelas Binaan Industri Terkemuka seperti Isuzu, Daihatsu, Axioo, Astra Motor, Bank Jateng Syariah, dan Bahasa Jepang.' }}
                    </p>

                    <!-- Dual Action Buttons matching reference -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <button @click="handleDaftarClick()" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white rounded-md font-bold text-sm tracking-wide transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 uppercase">
                            Daftar Online <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <a href="{{ route('casis.login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-transparent hover:bg-white/10 text-white border-2 border-white rounded-md font-bold text-sm tracking-wide transition flex items-center justify-center gap-2 uppercase backdrop-blur-sm">
                            Login Siswa <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM KEUNGGULAN SECTION (Reference Features Design) -->
    <section id="keunggulan" class="py-20 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Kelas Binaan & Mitra Industri</h2>
                <h3 class="text-3xl sm:text-2xl font-extrabold text-slate-900 font-heading">
                    6 Program Keunggulan SMK Bhakti Praja Adiwerna
                </h3>
                <p class="text-slate-600 mt-3 text-base">
                    Menggabungkan kurikulum nasional dengan standar kompetensi industri terkemuka demi menjamin kesiapan kerja lulusan.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:grid-cols-2">
                @forelse($programs as $prog)
                <div class="bg-slate-50 rounded-2xl p-4 sm:p-7 border border-slate-100 card-hover flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="w-10 h-10 sm:w-13 sm:h-12 font-bold text-lg sm:text-xl mb-2 overflow-hidden">
                        @if($prog->logo)
                            <img src="{{ asset('storage/' . $prog->logo) }}" alt="{{ $prog->nama }}" class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl">🏭</div>
                        @endif
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-slate-900 mb-1 sm:mb-2">{{ $prog->nama }}</h4>
                    <p class="sm:text-sm text-slate-600 leading-snug sm:leading-relaxed">{{ $prog->deskripsi }}</p>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-slate-500">Program keunggulan belum dikonfigurasi.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ALUR PENDAFTARAN (Reference How It Works Design) -->
    <section id="alur" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">How It Works</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    Alur & Tahapan SPMB SMK BPA
                </h3>
                <p class="text-slate-600 mt-3 text-base">
                    Proses penerimaan peserta didik baru secara transparan, akuntabel, dan mudah diakses online maupun offline.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                <!-- Left Column: Image -->
                <div class="bg-white rounded-[2rem] p-4 sm:p-6 shadow-sm border border-slate-200 h-fit flex flex-col" data-aos="fade-right" data-aos-delay="100">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-full flex items-center gap-2 shadow-md shadow-blue-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Alur Pendaftaran
                        </div>
                    </div>
                    <div class="w-full rounded-2xl overflow-hidden">
                        @php
                            $alurGambar = $settings['alur_spmb_gambar'] ?? null;
                            $alurGambarUrl = asset('images/alur-pendaftaran.png');
                            if ($alurGambar) {
                                $alurGambarUrl = str_starts_with($alurGambar, 'settings/') 
                                    ? asset('storage/' . $alurGambar) 
                                    : asset($alurGambar);
                            }
                        @endphp
                        <img src="{{ $alurGambarUrl }}" alt="Alur SPMB" class="w-full h-auto object-contain rounded-xl">
                    </div>
                </div>

                <!-- Right Column: Accordion -->
                <div class="bg-white rounded-[2rem] p-4 sm:p-6 shadow-sm border border-slate-200 h-full flex flex-col" data-aos="fade-left" data-aos-delay="200">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-full flex items-center gap-2 shadow-md shadow-blue-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            Informasi Alur Tahapan
                        </div>
                    </div>
                    <div class="w-full flex-grow">
                        <details class="group border border-slate-200 rounded-2xl bg-white overflow-hidden open:shadow-md transition-all duration-300 open:border-blue-600/30">
                            <summary class="flex justify-between items-center font-bold cursor-pointer list-none p-5 sm:p-6 text-slate-800 hover:text-blue-600 transition-colors">
                                <span class="text-lg">Alur SPMB Online</span>
                                <span class="transition-transform duration-300 group-open:rotate-180">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-[#6b46c1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </summary>
                            <div class="p-5 sm:p-6 pt-0 text-slate-600 text-sm md:text-base leading-relaxed border-t border-slate-100 prose prose-slate max-w-none prose-a:text-[#6b46c1] hover:prose-a:text-purple-800">
                                {!! $settings['alur_spmb_konten'] ?? 'Belum ada informasi alur pendaftaran.' !!}
                            </div>
                        </details>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- PROGRAM KEAHLIAN & REALTIME KUOTA SECTION -->
    <section id="jurusan" class="py-20 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Pilihan Program Keahlian</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    Jurusan & Informasi Kuota Terkini
                </h3>
                <p class="text-slate-600 mt-3 text-base">
                    Kuota berkurang secara otomatis ketika calon siswa telah menyelesaikan proses <strong>Daftar Ulang</strong>.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($jurusans as $j)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col card-hover" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    
                    <!-- Bagian Atas: Gambar Full Width No Margin -->
                    <div class="relative w-full h-40 bg-slate-100 group overflow-hidden">
                        
                        @if($j->logo)
                            <img src="{{ asset('storage/' . $j->logo) }}" alt="{{ $j->nama }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <!-- Overlay gradient (opsional untuk estetika) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>

                        <!-- Badge Sisa Kuota (Overlap) -->
                        @if($j->sisa_kuota > 0)
                            <div class="absolute top-3 right-3 bg-emerald-500/95 backdrop-blur text-white px-3 py-1 rounded-full text-[11px] font-bold shadow-md border border-emerald-400 z-20">
                                Sisa Kuota: {{ $j->sisa_kuota }}
                            </div>
                        @else
                            <div class="absolute top-3 right-3 bg-red-500/95 backdrop-blur text-white px-3 py-1 rounded-full text-[11px] font-bold shadow-md border border-red-400 z-20">
                                Penuh
                            </div>
                        @endif
                    </div>

                    <!-- Konten Bawah -->
                    <div class="p-5 flex flex-col flex-grow">
                        <!-- Kode Jurusan -->
                        <span class="text-blue-600 text-[11px] font-extrabold tracking-widest uppercase font-heading mb-1 block">
                            {{ $j->kode }}
                        </span>
                        
                        <!-- Nama Jurusan -->
                        <h4 class="text-lg font-bold text-slate-900 font-heading mb-2 leading-tight" title="{{ $j->nama }}">
                            {{ $j->nama }}
                        </h4>
                        
                        <!-- Deskripsi -->
                        <p class="text-slate-500 text-xs leading-relaxed mb-4 line-clamp-3 flex-grow">
                            {{ $j->deskripsi ?? 'Deskripsi jurusan belum ditambahkan.' }}
                        </p>
                        
                        <!-- Garis Pemisah -->
                        <hr class="border-t border-slate-100 mb-4">
                        
                        <!-- Statistik -->
                        <div class="flex justify-between items-center text-xs font-semibold">
                            <span class="text-slate-500">Total: <span class="text-slate-900">{{ $j->kuota }}</span></span>
                            <span class="text-slate-500">Daftar Ulang: <span class="text-blue-600">{{ $j->jumlah_daftar_ulang }}</span></span>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- JADWAL SECTION -->
    <section id="jadwal" class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Agenda & Timeline</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">
                    Jadwal Resmi SPMB {{ $activePeriod ? $activePeriod->tahunAjaran->nama : '2026/2027' }}
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pendaftaran -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between" data-aos="fade-up" data-aos-delay="100">
                    <div>
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-sm mb-4">01</span>
                        <h4 class="text-lg font-bold text-slate-900 font-heading mb-2">Pendaftaran SPMB</h4>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Pengisian formulir pendaftaran secara online atau datang ke sekretariat panitia di sekolah.</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        @if($jadwals->count() > 0)
                            <div class="space-y-1.5">
                                @foreach($jadwals as $j)
                                    <div class="flex justify-between items-center text-[11px]">
                                        <span class="font-medium text-slate-600">{{ $j->gelombang }}</span>
                                        <span class="font-bold {{ ($activePeriod && $activePeriod->id == $j->id) ? 'text-green-600' : 'text-blue-600' }}">
                                            {{ \Carbon\Carbon::parse($j->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($j->tanggal_selesai)->format('d M y') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs font-bold text-blue-600 block text-center">Menunggu Jadwal</span>
                        @endif
                    </div>
                </div>

                <!-- Verifikasi Akun -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between" data-aos="fade-up" data-aos-delay="200">
                    <div>
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-sm mb-4">02</span>
                        <h4 class="text-lg font-bold text-slate-900 font-heading mb-2">Verifikasi Akun</h4>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Proses validasi kelengkapan data dan dokumen pendaftaran oleh Panitia SPMB.</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                        <span class="text-xs font-bold text-blue-600 block">
                            @if(isset($activePeriod) && $activePeriod)
                                {{ \Carbon\Carbon::parse($activePeriod->tanggal_mulai)->locale('id')->translatedFormat('l, d F Y') }} -
                                <br>
                                {{ \Carbon\Carbon::parse($activePeriod->tanggal_selesai)->locale('id')->translatedFormat('l, d F Y') }}
                            @else
                                Menunggu Jadwal
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Daftar Ulang -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between" data-aos="fade-up" data-aos-delay="300">
                    <div>
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-sm mb-4">03</span>
                        <h4 class="text-lg font-bold text-slate-900 font-heading mb-2">Daftar Ulang</h4>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Proses daftar ulang dan pembayaran administrasi pendaftaran secara online/kasir.</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                        <span class="text-xs font-bold text-blue-600 block">
                            @if(!empty($settings['jadwal_daftar_ulang']))
                                {{ \Carbon\Carbon::parse($settings['jadwal_daftar_ulang'])->locale('id')->translatedFormat('l, d M Y') }}
                            @else
                                Menunggu Jadwal
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Pengumuman -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between" data-aos="fade-up" data-aos-delay="400">
                    <div>
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-sm mb-4">04</span>
                        <h4 class="text-lg font-bold text-slate-900 font-heading mb-2">Hasil Pengumuman</h4>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">Pengumuman hasil akhir penerimaan peserta didik baru.</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 flex flex-col gap-2">
                        <span class="text-xs font-bold text-blue-600 block">
                            @if(!empty($settings['jadwal_pengumuman']))
                                {{ \Carbon\Carbon::parse($settings['jadwal_pengumuman'])->locale('id')->translatedFormat('l, d M Y') }}
                            @else
                                Menunggu Jadwal
                            @endif
                        </span>
                        <a href="{{ route('preview.hasil.pengumuman') }}" target="_blank" class="mt-1 inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold py-2 px-3 rounded-lg transition-all shadow-sm">
                            Hasil Pengumuman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION BANNER (Inspired by Reference Design) -->
    <section class="py-10 sm:py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 rounded-3xl p-6 sm:p-10 md:p-16 text-center text-white overflow-hidden shadow-2xl" data-aos="fade-up">
                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2 class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-blue-400 mb-2 sm:mb-4">Siap Bergabung?</h2>
                    <h3 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold font-heading leading-tight mb-4 sm:mb-6">
                        Segera Amankan Kuota Jurusan Impian Anda!
                    </h3>
                    <p class="text-xs sm:text-base lg:text-lg text-slate-300 mb-6 sm:mb-8">
                        Pendaftaran terbuka secara online maupun langsung melalui Panitia SPMB di SMK Bhakti Praja Adiwerna.
                    </p>
                    <button @click="handleDaftarClick()" class="px-10 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold text-base shadow-xl shadow-blue-600/30 transition transform hover:-translate-y-0.5">
                        Daftar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER (Inspired by Reference Design) -->
    <footer id="kontak" class="bg-slate-900 text-white pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                
                <div class="md:col-span-1">
                    <h4 class="text-xl font-bold font-heading mb-4 text-blue-400">
                        {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}
                    </h4>
                    <p class="text-slate-400 text-xs leading-relaxed mb-4">
                        {{ $settings['tagline_sekolah'] ?? 'Mewujudkan Lulusan Berkarakter, Kompeten, dan Siap Kerja' }}
                    </p>
                    <p class="text-slate-400 text-xs">
                        {{ $settings['alamat_sekolah'] ?? 'Jl. Singkil No. 24, Adiwerna, Kab. Tegal' }}
                    </p>

                    <div class="mt-6 flex items-center gap-3">
                        @php
                            $fb = !empty($settings['sosmed_facebook']) ? $settings['sosmed_facebook'] : 'https://facebook.com';
                            $ig = !empty($settings['sosmed_instagram']) ? $settings['sosmed_instagram'] : 'https://instagram.com';
                            $yt = !empty($settings['sosmed_youtube']) ? $settings['sosmed_youtube'] : 'https://youtube.com';
                            $tk = !empty($settings['sosmed_tiktok']) ? $settings['sosmed_tiktok'] : 'https://tiktok.com';
                        @endphp
                        <a href="{{ $fb }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                        </a>
                        <a href="{{ $ig }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.64.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92-.06-1.27-.07-1.64-.07-4.85s.01-3.58.07-4.85c.15-3.23 1.66-4.77 4.92-4.92 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07 2.69.27.27 2.69.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.2 4.36 2.62 6.78 6.98 6.98 1.28.06 1.69.07 4.95.07s3.67-.01 4.95-.07c4.36-.2 6.78-2.62 6.98-6.98.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.2-4.36-2.62-6.78-6.98-6.98C15.67.01 15.26 0 12 0zm0 5.84A6.16 6.16 0 1018.16 12 6.16 6.16 0 0012 5.84zm0 10.16A4 4 0 1116 12a4 4 0 01-4 4zm5.8-10.4a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/></svg>
                        </a>
                        <a href="{{ $yt }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.49 7.03a3 3 0 00-2.11-2.1C19.5 4.43 12 4.43 12 4.43s-7.5 0-9.38.5a3 3 0 00-2.11 2.1C0 8.92 0 12 0 12s0 3.08.5 4.97a3 3 0 002.11 2.1C4.5 19.57 12 19.57 12 19.57s7.5 0 9.38-.5a3 3 0 002.11 2.1C24 15.08 24 12 24 12s0-3.08-.51-4.97zM9.54 15.35V8.65L15.82 12l-6.28 3.35z"/></svg>
                        </a>
                        <a href="{{ $tk }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.78-1.15 5.54-3.33 7.31-1.9 1.54-4.5 2.1-6.81 1.63-2.65-.55-4.88-2.31-5.91-4.78-1.07-2.58-.8-5.74.75-8.07 1.4-2.11 3.73-3.4 6.22-3.6 0 1.34-.01 2.68.01 4.02-1.37.07-2.73.65-3.56 1.76-.83 1.11-1.03 2.69-.47 3.91.56 1.21 1.95 2.06 3.29 2.06 1.23.01 2.45-.48 3.25-1.38.8-.9 1.15-2.2 1.11-3.42V.02z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-wider text-slate-200 mb-4">Program Keahlian</h5>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li>Teknik Kendaraan Ringan Otomotif (TKRO)</li>
                        <li>Teknik Jaringan Komputer & Telecom (TJKT)</li>
                        <li>Akuntansi & Keuangan Lembaga (AKL)</li>
                        <li>Teknik & Bisnis Sepeda Motor (TBSM)</li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-wider text-slate-200 mb-4">Kontak & WA Center</h5>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li>Telepon: {{ $settings['telepon_sekolah'] ?? '(0283) 443210' }}</li>
                        <li>Email: {{ $settings['email_sekolah'] ?? 'info@smkbhaktiprajaadiwerna.sch.id' }}</li>
                        <li>WA Center: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['wa_center'] ?? '085866918641') }}" target="_blank" class="text-blue-400 underline">{{ $settings['wa_center'] ?? '085866918641' }}</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold uppercase tracking-wider text-slate-200 mb-4">Akses Cepat</h5>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('casis.login') }}" class="hover:text-white">Login Siswa</a></li>
                        <li><a href="{{ route('pendaftaran') }}" class="hover:text-white">Formulir Pendaftaran</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Login Panitia / Admin</a></li>
                    </ul>
                </div>

            </div>

            <div class="pt-8 text-center text-xs text-slate-500">
                © {{ date('Y') }} {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Modal Status Pendaftaran -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>
        
        <div class="relative bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl text-center z-10" @click.stop>
            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 font-heading mb-2">Informasi Jadwal SPMB</h3>
            <p class="text-slate-600 text-sm mb-6" x-text="modalMessage"></p>
            
            <template x-if="registrationStatus === 'not_started' && countdownActive">
                <div class="mb-6 bg-blue-600 text-white rounded-xl p-5 shadow-inner border border-blue-500">
                    <div class="text-xs font-semibold mb-2 text-blue-100 uppercase tracking-widest" x-text="'Dibuka pada: ' + formattedStartDate"></div>
                    <div class="text-3xl font-black tracking-widest font-heading drop-shadow-md" x-text="countdownText">00 Hari 00:00:00</div>
                </div>
            </template>

            <button @click="showModal = false" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Informasi SPMB Popup (On Load) -->
    <div x-show="showInfoModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showInfoModal = false"></div>
        
        <div class="relative bg-white rounded-2xl w-full max-w-lg shadow-2xl z-10 overflow-hidden" @click.stop>
            
            <!-- Header -->
            <div class="bg-blue-600 text-white p-5 flex items-start justify-between relative">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-blue-500/50 border border-blue-400 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold tracking-widest uppercase text-emerald-200 block mb-0.5">Informasi SPMB Hari Ini</span>
                        <h3 class="text-xl font-bold font-heading">SMK BHAKTI PRAJA ADIWERNA</h3>
                    </div>
                </div>
                <button @click="showInfoModal = false" class="text-emerald-200 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-5">
                
                @php
                    // Helper logic for current status
                    $sekarang = \Carbon\Carbon::now();
                    
                    $tglTesMulai = !empty($settings['jadwal_tes_mulai']) ? \Carbon\Carbon::parse($settings['jadwal_tes_mulai']) : null;
                    $tglTesSelesai = !empty($settings['jadwal_tes_selesai']) ? \Carbon\Carbon::parse($settings['jadwal_tes_selesai'])->endOfDay() : null;

                    $tglPengumuman = !empty($settings['jadwal_pengumuman']) ? \Carbon\Carbon::parse($settings['jadwal_pengumuman'])->startOfDay() : null;
                    $tglDaftarUlang = !empty($settings['jadwal_daftar_ulang']) ? \Carbon\Carbon::parse($settings['jadwal_daftar_ulang'])->endOfDay() : null;

                    $statusJadwal = "Jadwal SPMB Belum Tersedia";
                    $deskripsiJadwal = "Silakan pantau website ini secara berkala untuk informasi terbaru.";

                    if ($activePeriod) {
                        $tglMulaiActive = \Carbon\Carbon::parse($activePeriod->tanggal_mulai)->startOfDay();
                        $tglSelesaiActive = \Carbon\Carbon::parse($activePeriod->tanggal_selesai)->endOfDay();
                        
                        if ($sekarang->lt($tglMulaiActive)) {
                            $statusJadwal = "Pendaftaran Belum Dibuka";
                            $deskripsiJadwal = "Pendaftaran {$activePeriod->gelombang} akan dibuka pada tanggal " . $tglMulaiActive->translatedFormat('d F Y') . ".";
                        } elseif ($sekarang->gt($tglSelesaiActive)) {
                            $statusJadwal = "Pendaftaran Telah Ditutup";
                            $deskripsiJadwal = "Masa pendaftaran {$activePeriod->gelombang} telah berakhir.";
                        } else {
                            $statusJadwal = "Pendaftaran Sedang Berlangsung";
                            $deskripsiJadwal = "Saat ini pendaftaran {$activePeriod->gelombang} sedang dibuka hingga " . $tglSelesaiActive->translatedFormat('d F Y') . ".";
                        }
                    } elseif ($jadwals->count() > 0) {
                        $upcoming = $jadwals->where('tanggal_mulai', '>', $sekarang)->first();
                        if ($upcoming) {
                            $statusJadwal = "Pendaftaran Belum Dibuka";
                            $deskripsiJadwal = "Pendaftaran {$upcoming->gelombang} akan segera dibuka pada tanggal " . \Carbon\Carbon::parse($upcoming->tanggal_mulai)->translatedFormat('d F Y') . ".";
                        } else {
                            $statusJadwal = "Pendaftaran Telah Ditutup";
                            $deskripsiJadwal = "Seluruh gelombang pendaftaran telah selesai.";
                        }
                    } elseif ($tglTesMulai && $tglTesSelesai && $sekarang->between($tglTesMulai, $tglTesSelesai)) {
                        $statusJadwal = "Masa Tes Seleksi";
                        $deskripsiJadwal = "Saat ini sedang berlangsung tahapan Tes Seleksi bagi peserta yang telah mendaftar.";
                    } elseif ($tglPengumuman && $sekarang->isSameDay($tglPengumuman)) {
                        $statusJadwal = "Pengumuman Kelulusan";
                        $deskripsiJadwal = "Hari ini adalah jadwal pengumuman hasil seleksi SPMB.";
                    } elseif ($tglDaftarUlang && $tglPengumuman && $sekarang->between($tglPengumuman, $tglDaftarUlang)) {
                        $statusJadwal = "Masa Daftar Ulang";
                        $deskripsiJadwal = "Bagi calon siswa yang LULUS, harap segera melakukan Daftar Ulang sebelum " . $tglDaftarUlang->translatedFormat('d F Y') . ".";
                    }
                @endphp

                <!-- Status Banner -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-5 relative overflow-hidden">
                    <h4 class="text-base font-bold text-slate-800 mb-1 relative z-10">{{ $statusJadwal }}</h4>
                    <p class="text-sm text-slate-500 relative z-10">{{ $deskripsiJadwal }}</p>

                    <template x-if="registrationStatus === 'not_started' && countdownActive">
                        <div class="mt-4 bg-blue-600 text-white rounded-xl p-4 shadow-inner border border-blue-500 text-center relative z-10">
                            <div class="text-[10px] font-semibold mb-1 text-blue-100 uppercase tracking-widest" x-text="'Hitung Mundur Pembukaan'"></div>
                            <div class="text-2xl font-black tracking-widest font-heading drop-shadow-md" x-text="countdownText">00 Hari 00:00:00</div>
                        </div>
                    </template>
                </div>

                <!-- Ringkasan -->
                <div class="bg-white border border-slate-200 rounded-xl p-5">
                    <h4 class="text-sm font-bold text-slate-900 mb-4">Ringkasan Jadwal SPMB</h4>
                    
                    <div class="space-y-3 text-sm">
                        <div class="mb-4">
                            <span class="text-slate-500 font-medium block mb-2">Pendaftaran (Gelombang):</span>
                            @if($jadwals->count() > 0)
                                @foreach($jadwals as $j)
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-1 border-b border-slate-100 last:border-0">
                                        <span class="text-slate-700 text-xs">{{ $j->gelombang }} @if($activePeriod && $activePeriod->id == $j->id) <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] ml-1">Aktif</span> @endif</span>
                                        <span class="text-slate-800 font-bold sm:text-right mt-1 sm:mt-0 text-xs">
                                            {{ \Carbon\Carbon::parse($j->tanggal_mulai)->translatedFormat('d M') }} - 
                                            {{ \Carbon\Carbon::parse($j->tanggal_selesai)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-slate-500 text-xs italic">Belum ada jadwal gelombang pendaftaran.</div>
                            @endif
                        </div>

                        @if(!empty($settings['jadwal_tes_mulai']) && !empty($settings['jadwal_tes_selesai']))
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-medium">Tes Seleksi</span>
                            <span class="text-slate-800 font-bold sm:text-right mt-1 sm:mt-0">
                                {{ \Carbon\Carbon::parse($settings['jadwal_tes_mulai'])->translatedFormat('d F Y') }} - 
                                {{ \Carbon\Carbon::parse($settings['jadwal_tes_selesai'])->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        @endif

                        @if(!empty($settings['jadwal_pengumuman']))
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-medium">Pengumuman</span>
                            <span class="text-slate-800 font-bold sm:text-right mt-1 sm:mt-0">
                                {{ \Carbon\Carbon::parse($settings['jadwal_pengumuman'])->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                        @endif

                        @if(!empty($settings['jadwal_daftar_ulang']))
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-1">
                            <span class="text-slate-500 font-medium">Daftar Ulang</span>
                            <span class="text-slate-800 font-bold sm:text-right mt-1 sm:mt-0">
                                {{ \Carbon\Carbon::parse($settings['jadwal_daftar_ulang'])->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        @endif
                        
                        @if(empty($settings['jadwal_pendaftaran_mulai']) && empty($settings['jadwal_pengumuman']) && empty($settings['jadwal_daftar_ulang']))
                            <div class="text-slate-400 text-xs italic text-center py-2">Belum ada rincian jadwal dari pengaturan.</div>
                        @endif
                    </div>
                </div>

            </div>

            
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-center gap-3">
        <!-- Back to Top Button -->
        <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
            x-data="{ show: false }" 
            @scroll.window="show = window.pageYOffset > 300"
            x-show="show" 
            x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4" 
            x-transition:enter-end="opacity-100 translate-y-0" 
            x-transition:leave="transition ease-in duration-300" 
            x-transition:leave-start="opacity-100 translate-y-0" 
            x-transition:leave-end="opacity-0 translate-y-4"
            class="w-12 h-12 bg-white text-blue-600 rounded-full shadow-lg shadow-slate-900/10 flex items-center justify-center border border-slate-100 hover:bg-blue-50 transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
        </button>

        <!-- WhatsApp Button -->
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['wa_center'] ?? '085866918641') }}" target="_blank" 
            class="w-14 h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-lg shadow-emerald-500/30 flex items-center justify-center transition-transform transform hover:-translate-y-1 focus:outline-none">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.03A10.02 10.02 0 002.03 12c0 1.76.46 3.42 1.28 4.88L2 21.99l5.25-1.37a9.98 9.98 0 004.76 1.18c5.52 0 10.02-4.5 10.02-10.02S17.53 2.03 12.01 2.03zm5.42 14.5c-.24.67-1.38 1.25-1.93 1.34-.52.09-1.21.15-3.52-.8-2.79-1.16-4.6-4.04-4.74-4.23-.14-.19-1.13-1.51-1.13-2.88 0-1.38.72-2.06 1-2.36.27-.29.59-.36.78-.36.2 0 .39 0 .56.01.19.01.44-.07.67.5.24.58.84 2.04.91 2.18.07.14.12.3.03.49-.09.19-.14.3-.29.47-.14.16-.31.36-.43.49-.14.14-.28.29-.12.56.16.27.7 1.16 1.51 1.88.75.66 1.62.87 1.84.97.23.09.36.08.49-.07.13-.15.58-.67.73-.9.15-.24.3-.2.52-.12.22.08 1.39.66 1.63.78.24.12.4.18.45.28.06.1.06.57-.18 1.24z"/></svg>
        </a>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS Animation
        AOS.init({
            once: true,
            offset: 50,
            duration: 600,
            easing: 'ease-out-cubic',
        });

        function landingPage() {
            return {
                openMobile: false,
                showModal: false,
                modalMessage: '',
                showInfoModal: false,
                registrationStatus: @json($registrationStatus ?? 'open'),
                startDateStr: @json($tanggalMulai ? $tanggalMulai->toIso8601String() : null),
                formattedStartDate: @json($tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') : ''),
                countdownText: '00 Hari 00:00:00',
                countdownActive: false,
                countdownInterval: null,

                init() {
                    // Tampilkan popup informasi saat halaman diload
                    setTimeout(() => {
                        this.showInfoModal = true;
                    }, 500);

                    if (this.registrationStatus === 'not_started' && this.startDateStr) {
                        this.countdownActive = true;
                        this.startCountdown();
                    }
                },

                startCountdown() {
                    const countDownDate = new Date(this.startDateStr).getTime();
                    this.updateCountdown(countDownDate);
                    this.countdownInterval = setInterval(() => {
                        this.updateCountdown(countDownDate);
                    }, 1000);
                },

                updateCountdown(countDownDate) {
                    const now = new Date().getTime();
                    const distance = countDownDate - now;

                    if (distance < 0) {
                        clearInterval(this.countdownInterval);
                        this.countdownActive = false;
                        this.registrationStatus = 'open';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    const pad = (n) => n.toString().padStart(2, '0');
                    this.countdownText = `${pad(days)} Hari ${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                },

                handleDaftarClick() {
                    if (this.registrationStatus === 'not_started') {
                        this.modalMessage = 'Pendaftaran SPMB SMK Bhakti Praja Adiwerna belum dibuka.';
                        this.showModal = true;
                    } else if (this.registrationStatus === 'closed') {
                        this.modalMessage = 'Pendaftaran SPMB SMK Bhakti Praja Adiwerna telah resmi ditutup.';
                        this.showModal = true;
                    } else {
                        window.location.href = '{{ route("pendaftaran") }}';
                    }
                }
            }
        }
    </script>
</body>
</html>