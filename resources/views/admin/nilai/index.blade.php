<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Rekap Nilai Siswa</h1>
                            <p class="text-gray-500 text-sm mt-1">Rekap nilai siswa dari semua calon siswa yang telah mendaftar.</p>
                        </div>
                        <a href="{{ route('admin.nilai.download') }}"
                            class="bg-green-600 hover:bg-green-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-2xl shadow-lg shadow-green-200 transition-all active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Unduh CSV
                        </a>
                    </div>


            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between align-center gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Rekap Nilai Siswa</h3>
                        <p class="text-sm text-gray-500 mt-1">Total terdapat {{ $casis->total() }} siswa terdaftar.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="text" placeholder="Cari Nama/NISN..."
                                class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl focus:ring-green-500 focus:border-green-500 block w-full pl-10 p-2.5 transition-all"
                                    value="{{ request('search') }}"
                                    name="search">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[12px] text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                                        <tr>
                                            <th class="px-8 py-4 font-black w-16">No</th>
                                            <th class="px-6 py-4 font-black">Nama Lengkap</th>
                                            <th class="px-6 py-4 font-black">NISN</th>
                                            <th class="px-6 py-4 font-black">Matematika</th>
                                            <th class="px-6 py-4 font-black">IPA</th>
                                            <th class="px-6 py-4 font-black">B. Indo</th>
                                            <th class="px-6 py-4 font-black">B. Ing</th>
                                            <th class="px-8 py-4 font-black text-right">Rata-rata</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($casis as $index => $item)
                                        @php
                                        $nilais = $item->nilaiRapor;
                                        if ($nilais->count() > 0) {
                                        $mtk = $nilais->avg('matematika');
                                        $ipa = $nilais->avg('ipa');
                                        $bind = $nilais->avg('bind');
                                        $bing = $nilais->avg('bing');
                                        $avg = ($mtk + $ipa + $bind + $bing) / 4;
                                        } else {
                                        $mtk = $ipa = $bind = $bing = $avg = 0;
                                        }
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $casis->firstItem() + $index }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->nisn }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($mtk,
                                                1) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($ipa,
                                                1) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                number_format($bind, 1) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                number_format($bing, 1) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ number_format($avg, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $casis->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>