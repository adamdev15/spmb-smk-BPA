<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col text-slate-800">
    
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-xl font-bold text-blue-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                {{ config('app.name', 'SPMB') }}
            </a>
            <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition">Kembali ke Beranda</a>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-slate-200">
            <h1 class="text-3xl font-black text-slate-900 mb-8">@yield('title')</h1>
            <div class="prose prose-slate prose-blue max-w-none">
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-8 mt-auto">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
            <div>&copy; {{ date('Y') }} {{ config('app.name') }}. Hak Cipta Dilindungi.</div>
            <div class="flex gap-4">
                <a href="{{ route('pages.syarat') }}" class="hover:text-blue-600 transition">Syarat & Ketentuan</a>
                <a href="{{ route('pages.privasi') }}" class="hover:text-blue-600 transition">Privasi</a>
                <a href="{{ route('pages.terms') }}" class="hover:text-blue-600 transition">Terms of Service</a>
            </div>
        </div>
    </footer>
</body>
</html>
