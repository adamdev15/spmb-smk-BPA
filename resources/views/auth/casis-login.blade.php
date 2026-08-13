<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Calon Siswa - {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}</title>
    <link rel="icon" href="{{ asset('images/logo_smk_bpa.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .font-heading { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-slate-100/80" x-data="{ showPass: false }">
        
        <!-- Top Logo & Brand Row -->
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 flex items-center justify-center font-extrabold text-lg text-slate-900 group-hover:scale-105 transition transform">
                    @if(!empty($settings['logo']))
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        BPA
                    @endif
                </div>
                <span class="text-lg font-bold text-slate-900 tracking-tight font-heading">
                    {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}
                </span>
            </a>
        </div>

        <!-- Title & Subtitle -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading mb-2">
                Selamat Datang Kembali!
            </h1>
            <p class="text-sm text-slate-500 leading-relaxed">
                Masuk untuk mengecek status pendaftaran & daftar ulang di <strong class="text-blue-600 font-semibold">{{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}</strong>.
            </p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-600 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('casis.login.post') }}" method="POST" class="space-y-5">
            @csrf

            <!-- NISN Input -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NISN</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6" />
                        </svg>
                    </div>
                    <input type="text" name="nisn" required autofocus
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none transition font-medium placeholder-slate-400"
                        placeholder="Masukkan NISN Anda">
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input :type="showPass ? 'text' : 'password'" name="password" required
                        class="w-full pl-11 pr-11 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 text-sm focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none transition font-medium placeholder-slate-400"
                        placeholder="Masukkan kata sandi Anda">
                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg class="w-5 h-5" x-show="!showPass" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="w-5 h-5" x-show="showPass" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.682-.763c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-4.692-4.692a3 3 0 00-4.243-4.243" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <div class="flex justify-between mt-1">
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">*Default password: NISN</p>
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Lupa kata sandi? <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['no_hp'] ?? '') }}" target="_blank" class="text-green-600 font-bold hover:underline">Hubungi Admin</a></p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-base rounded-2xl shadow-xl shadow-blue-500/25 transition transform hover:-translate-y-0.5 active:translate-y-0 mt-2">
                Masuk
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-8 text-center text-xs text-slate-500 border-t border-slate-100 pt-6 space-y-3">
            <p>Belum punya akun? <a href="{{ route('pendaftaran') }}" class="text-blue-600 font-bold hover:underline">Daftar sekarang</a></p>
        </div>
        <div class="flex mt-4 justify-between items-center text-xs">
            <p><a href="{{ route('landing') }}" class="text-slate-600 font-bold hover:underline">← Kembali ke Beranda</a></p>
            <p><a href="{{ route('login') }}" class="text-green-600 font-bold hover:underline">Login sebagai Admin →</a></p>
        </div>

        <!-- Copyright Footer -->
        <div class="mt-6 text-center text-[11px] text-slate-400">
            © {{ date('Y') }} {{ $settings['nama_sekolah'] ?? 'SMK Bhakti Praja Adiwerna' }}. Semua hak cipta dilindungi.
        </div>

    </div>

</body>
</html>