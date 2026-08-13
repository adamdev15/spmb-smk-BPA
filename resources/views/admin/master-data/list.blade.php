<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Master Data</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola data master untuk formulir pendaftaran.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($types as $key => $type)
                <a href="{{ route('admin.master-data.show', $key) }}"
                    class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group cursor-pointer">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
    
    @if($key === 'cita')
        <!-- Target Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

    @elseif($key === 'hobi')
        <!-- Palette Icon -->
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                d="M5 3v4M3 5h4m10-2v6m-3-3h6M4 17l5-5 5 5M14 14l6 6" />
        </svg>

    @elseif($key === 'pekerjaan')
    <!-- Office Building -->
    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            d="M3 21h18M9 8h6M9 12h6M9 16h6M4 21V7a2 2 0 012-2h12a2 2 0 012 2v14" />
    </svg>


    @elseif($key === 'orientasi')
    <!-- Users -->
    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            d="M17 20h5v-2a4 4 0 00-5-3.87M7 20H2v-2a4 4 0 015-3.87m5-3.13a4 4 0 100-8 4 4 0 000 8z" />
    </svg>
    
    @elseif($key === 'ketrampilan')
    <!-- Academic Cap -->
    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-4-3a4 4 0 008 0" />
    </svg>

    @endif

</div>

                            <svg class="w-6 h-6 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $type['label'] }}</h3>
                        <p class="text-sm text-gray-500">Total: <span class="font-bold text-green-500">{{ $type['count'] }}</span> data</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

