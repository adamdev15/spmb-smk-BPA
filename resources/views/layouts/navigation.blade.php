<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-lg shadow-blue-500/20 font-heading overflow-hidden">
                            @if(!empty($settings['logo']))
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain p-1">
                            @else
                                <img src="{{ asset('images/logo_smk_bpa.png') }}" alt="Logo" class="w-full h-full object-contain p-1" onerror="this.outerHTML='{{ $settings['singkatan_sekolah'] ?? 'BPA' }}'">
                            @endif
                        </div>
                        <span class="text-lg font-bold text-slate-900 tracking-tight font-heading group-hover:text-blue-600 transition">
                            {{ $settings['singkatan_sekolah'] ?? 'SMK BPA' }}
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition text-slate-700 hover:text-blue-600">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.casis.index')" :active="request()->routeIs('admin.casis.*')"
                        class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition text-slate-700 hover:text-blue-600">
                        {{ __('Pendaftar') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.pembayaran.index')" :active="request()->routeIs('admin.pembayaran.*')"
                        class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition text-slate-700 hover:text-blue-600">
                        {{ __('Pembayaran') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-1 px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition text-slate-700 hover:text-blue-600 focus:outline-none">
                                    Master Data
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="p-2">
                                    <x-dropdown-link :href="route('admin.jurusans.index')" :active="request()->routeIs('admin.jurusans.*')" class="rounded-xl text-xs font-bold">
                                        {{ __('Master Jurusan') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.program-keunggulan.index')" :active="request()->routeIs('admin.program-keunggulan.*')" class="rounded-xl text-xs font-bold mt-1">
                                        {{ __('Program Keunggulan') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.jadwals.index')" :active="request()->routeIs('admin.jadwals.*')" class="rounded-xl text-xs font-bold mt-1">
                                        {{ __('Jadwal SPMB') }}
                                    </x-dropdown-link>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Global Search -->
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
                <div class="relative mr-4" x-data="globalSearch()">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input x-model="query" @input.debounce.300ms="search" @focus="open = true" @click.away="open = false" type="text" class="block w-64 pl-10 pr-3 py-2 border border-slate-200 rounded-2xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all text-xs font-bold" placeholder="Cari Siswa (Nama/NISN)..." autocomplete="off">
                    </div>
                    
                    <!-- Dropdown Results -->
                    <div x-show="open && (query.length > 0)" class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" style="display: none;">
                        <div class="max-h-60 overflow-y-auto">
                            <template x-if="isLoading">
                                <div class="p-4 text-center text-xs text-slate-500 font-medium">Mencari...</div>
                            </template>
                            
                            <template x-if="!isLoading && results.length === 0 && query.length >= 2">
                                <div class="p-4 text-center text-xs text-slate-500 font-medium">Siswa tidak ditemukan</div>
                            </template>

                            <template x-if="!isLoading && results.length > 0">
                                <ul>
                                    <template x-for="casis in results" :key="casis.id">
                                        <li>
                                            <a :href="`/admin/casis/${casis.id}`" class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-50 transition-colors">
                                                <p class="text-xs font-bold text-slate-900 uppercase tracking-tight" x-text="casis.nama_lengkap"></p>
                                                <p class="text-[10px] text-slate-500 mt-0.5">NISN: <span x-text="casis.nisn"></span> <template x-if="casis.no_pendaftaran"><span x-text="` • No: ${casis.no_pendaftaran}`"></span></template></p>
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
                
                <script>
                    function globalSearch() {
                        return {
                            query: '',
                            results: [],
                            isLoading: false,
                            open: false,
                            search() {
                                if (this.query.length < 2) {
                                    this.results = [];
                                    return;
                                }
                                this.isLoading = true;
                                fetch(`/admin/casis/search/api?q=${encodeURIComponent(this.query)}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.results = data;
                                        this.isLoading = false;
                                        this.open = true;
                                    })
                                    .catch(() => {
                                        this.isLoading = false;
                                        this.results = [];
                                    });
                            }
                        }
                    }
                </script>
                @endif

                <!-- Notifications Dropdown -->
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="relative p-2 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors mr-2">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-red-500 rounded-full">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2 w-80 max-h-96 overflow-y-auto">
                            <div class="px-2 py-2 mb-2 text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 flex justify-between items-center">
                                <span>Notifikasi</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('admin.notifications.readAll') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-blue-600 hover:underline normal-case">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>

                            @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-3 py-3 hover:bg-slate-50 rounded-xl transition {{ is_null($notification->read_at) ? 'bg-blue-50/50' : '' }}">
                                    <p class="text-xs font-bold text-slate-800">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                    <p class="text-[9px] text-slate-400 mt-2 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-xs text-slate-500">
                                    Tidak ada notifikasi baru.
                                </div>
                            @endforelse
                        </div>
                    </x-slot>
                </x-dropdown>

                <div class="h-8 w-px bg-slate-200 mx-4"></div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition">
                            <div class="flex flex-col items-end">
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-tight">{{ Auth::user()->name }}</span>
                                <span class="text-[9px] font-extrabold text-blue-600 uppercase tracking-wider">{{ Auth::user()->role }}</span>
                            </div>
                            @if(!empty($settings['logo']))
                                <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 shrink-0">
                                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-200 shrink-0">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl flex items-center gap-3 text-xs font-bold py-2.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('Profil Saya') }}
                            </x-dropdown-link>
                            
                            @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.users.index')"
                                class="rounded-xl flex items-center gap-3 text-xs font-bold py-2.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ __('Kelola User') }}
                            </x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="route('admin.settings')"
                                class="rounded-xl flex items-center gap-3 text-xs font-bold py-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Pengaturan') }}
                            </x-dropdown-link>
                            <div class="h-px bg-slate-100 my-1"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-xl flex items-center gap-3 text-xs font-bold py-2.5 text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Keluar Sistem') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu for Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-100 bg-white">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl font-bold text-xs">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.casis.index')" :active="request()->routeIs('admin.casis.*')" class="rounded-xl font-bold text-xs">
                {{ __('Pendaftar') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.pembayaran.index')" :active="request()->routeIs('admin.pembayaran.*')" class="rounded-xl font-bold text-xs">
                {{ __('Pembayaran') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 'admin')
            <div x-data="{ masterDataOpen: false }">
                <button @click="masterDataOpen = !masterDataOpen" class="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:text-slate-800 focus:bg-slate-50 focus:border-slate-300 transition duration-150 ease-in-out rounded-xl font-bold text-xs">
                    {{ __('Master Data') }}
                    <svg class="h-4 w-4 transform transition-transform duration-200" :class="{'rotate-180': masterDataOpen}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="masterDataOpen" class="pl-4 mt-1 space-y-1">
                    <x-responsive-nav-link :href="route('admin.jurusans.index')" :active="request()->routeIs('admin.jurusans.*')" class="rounded-xl font-bold text-xs">
                        {{ __('Master Jurusan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.program-keunggulan.index')" :active="request()->routeIs('admin.program-keunggulan.*')" class="rounded-xl font-bold text-xs">
                        {{ __('Program Keunggulan') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="rounded-xl font-bold text-xs">
                {{ __('Kelola User') }}
            </x-responsive-nav-link>
            @endif
        </div>
    </div>
</nav>