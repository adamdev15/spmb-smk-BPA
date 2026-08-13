<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic uppercase">Daftar Calon Siswa
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">Manajemen data calon siswa dan status verifikasi berkas.</p>
                </div>
                <div>
                    <a href="{{ route('admin.casis.export', request()->all()) }}"
                        class="bg-green-600 hover:bg-green-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-2xl shadow-lg shadow-green-200 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>

            <!-- Filter & Search Card -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 mb-8">
                <form action="{{ route('admin.casis.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center mb-10">
                    <div class="flex-1 w-full">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Cari
                            Peserta</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()"
                                class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-gray-300"
                                placeholder="Masukkan Nama atau NISN...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Periode Pendaftaran</label>
                        <select name="spmb_period_id" onchange="this.form.submit()" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            <option value="">Semua Periode</option>
                            @foreach($tahunAjarans as $ta)
                                <optgroup label="{{ $ta->nama }}">
                                    @foreach($ta->spmbPeriods as $period)
                                        <option value="{{ $period->id }}" {{ ($selectedSpmbPeriodId == $period->id || (request('spmb_period_id') == $period->id)) ? 'selected' : '' }}>
                                            Gelombang {{ $period->gelombang }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Status
                            Verifikasi</label>
                        <select name="status" onchange="this.form.submit()"
                            class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="Belum Diverifikasi" {{ request('status')=='Belum Diverifikasi' ? 'selected'
                                : '' }}>Pending (Belum Cek)</option>
                            <option value="Diverifikasi" {{ request('status')=='Diverifikasi' ? 'selected' : '' }}>Sudah
                                Terverifikasi</option>
                        </select>
                    </div>

                    <div>
                    <form id="bulkVerifyForm" action="{{ route('admin.verify-bulk') }}" method="POST" class="hidden">
                        @csrf
                        <div id="bulkVerifyInputs"></div>
                    </form>

                    <div class="mb-4 flex items-center justify-between">
                        <button type="button" onclick="submitBulkVerify()" id="btnBulkVerify" class="flex opacity-50 cursor-not-allowed bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black capitalize tracking-widest px-6 py-3 rounded-2xl transition-all shadow-sm items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Verifikasi Terpilih(<span id="bulkCount">0</span>)
                        </button>
                    </div>
                </div>

                    <div class="flex gap-2">
                        @if(request()->anyFilled(['search', 'status', 'spmb_period_id']))
                        <a href="{{ route('admin.casis.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest px-6 py-4 rounded-2xl transition-all flex items-center mt-6">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[11px] text-gray-400 tracking-[0.2em] bg-gray-50/50 py-4 px-6 mb-5">
                            <tr>
                                <th class="px-6 py-5 font-black w-12 text-center">
                                    <input type="checkbox" onclick="toggleSelectAll(this)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="px-2 py-5 font-black w-12">No</th>
                                <th class="px-6 py-5 font-black">No. Daftar</th>
                                <th class="px-6 py-5 font-black">Siswa</th>
                                <th class="px-6 py-5 font-black">Asal Sekolah</th>
                                <th class="px-6 py-5 font-black">Status Berkas</th>
                                <th class="px-8 py-5 font-black text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 mb-5">
                            @forelse($casis as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition-all group border-b border-slate-500">
                                <td class="px-6 py-6 text-center">
                                    <input type="checkbox" value="{{ $item->id }}" class="casis-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-2 py-6">
                                    <span class="text-xs font-bold text-gray-400">{{ $casis->firstItem() + $index }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span
                                        class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded-lg">{{
                                        $item->no_pendaftaran }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-gray-900 uppercase tracking-tight group-hover:text-blue-600 transition-colors">{{
                                                $item->nama_lengkap }}</span>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] text-gray-400 font-mono tracking-wider">NISN:
                                                    {{ $item->nisn }}</span>
                                                <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 font-medium text-gray-600 italic text-xs uppercase tracking-tight">
                                    {{ $item->nama_sekolah }}
                                </td>
                                <td class="px-6 py-6 text-center align-middle items-center justify-center">
                                    @if($item->status_verifikasi == 'Diverifikasi')
                                    <div
                                        class="flex items-center gap-2 text-blue-600 bg-blue-50 px-3 py-2 rounded-xl border border-blue-100 w-fit">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span
                                            class="text-[10px] font-black tracking-widest">Terverifikasi</span>
                                    </div>
                                    @else
                                    <div class="flex flex-col items-center gap-2">
                                        <div
                                            class="flex items-center gap-2 text-amber-600 bg-amber-50 px-3 py-2 rounded-xl border border-amber-100 w-fit">
                                            <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Pending</span>
                                        </div>
                                        <form action="{{ route('admin.casis.reminder', $item->id) }}" method="POST" class="inline-block"
                                            onsubmit="return confirm('Kirim notifikasi pengingat ke WhatsApp {{ $item->nama_lengkap }}?')">
                                            @csrf
                                            <button type="submit" title="Kirim notifikasi pengingat ke WhatsApp"
                                                class="bg-green-500 hover:bg-green-600 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg transition-all active:scale-95 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.casis.show', $item->id) }}"
                                            class="bg-white hover:bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest px-2 py-1.5 rounded-xl border border-gray-100 transition-all active:scale-95 shadow-sm">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.casis.edit', $item->id) }}"
                                            class="bg-amber-400 hover:bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest px-2 py-1.5 rounded-xl shadow-lg shadow-amber-200 transition-all active:scale-95">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.casis.destroy', $item->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Hapus data {{ $item->nama_lengkap }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-[10px] font-black uppercase tracking-widest px-2 py-1.5 rounded-xl border border-red-100 transition-all active:scale-95">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="mt-11">
                                <td colspan="6" class="mt-11 px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center text-gray-200 mb-4">
                                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-400">Data Tidak Ditemukan</h4>
                                        <p
                                            class="text-sm text-gray-300 mt-1 uppercase tracking-widest font-black text-[10px]">
                                            Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-8 bg-gray-50/30 border-t border-gray-50">
                    {{ $casis->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.casis-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateBulkVerifyButton();
        }
        
        function updateBulkVerifyButton() {
            const checkedCount = document.querySelectorAll('.casis-checkbox:checked').length;
            const btn = document.getElementById('btnBulkVerify');
            const countSpan = document.getElementById('bulkCount');
            
            countSpan.innerText = checkedCount;
            if(checkedCount > 0) {
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        function submitBulkVerify() {
            const checkboxes = document.querySelectorAll('.casis-checkbox:checked');
            if(checkboxes.length === 0) return;
            
            Swal.fire({
                title: 'Konfirmasi Verifikasi',
                text: 'Apakah Anda yakin ingin memverifikasi ' + checkboxes.length + ' siswa yang dipilih?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Verifikasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('bulkVerifyForm');
                    const inputsContainer = document.getElementById('bulkVerifyInputs');
                    inputsContainer.innerHTML = '';
                    
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'casis_ids[]';
                        input.value = cb.value;
                        inputsContainer.appendChild(input);
                    });
                    
                    form.submit();
                }
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.casis-checkbox');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkVerifyButton);
            });
        });
    </script>
</x-app-layout>