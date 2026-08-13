<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic capitalize">Jadwal SPMB</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola tahun ajaran dan periode gelombang pendaftaran.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button type="button" onclick="document.getElementById('modalTahunAjaran').classList.remove('hidden')" class="bg-white hover:bg-gray-50 text-gray-700 text-[10px] font-black capitalize tracking-widest px-6 py-4 rounded-2xl transition-all shadow-sm flex items-center gap-2 border border-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tahun Ajaran
                    </button>
                    <button type="button" onclick="document.getElementById('modalPeriode').classList.remove('hidden')" class="bg-blue-700 hover:bg-blue-900 text-white text-[10px] font-black capitalize tracking-widest px-6 py-4 rounded-2xl transition-all active:scale-95 shadow-sm hover:shadow-lg shadow-gray-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Periode
                    </button>
                </div>
            </div>


            <!-- Tabel Periode -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="flex items-center justify-between">
                    <div class="p-8 border-b border-gray-50">
                    <h3 class="text-lg font-extrabold text-gray-900">Daftar Gelombang / Periode</h3>
                    <p class="text-xs text-gray-500 font-medium mt-1">Riwayat dan daftar periode pendaftaran yang ada di sistem.</p>
                    </div>
                    <div class="p-8">
                    @if($activePeriod)
                    <div class="flex items-center gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="text-xl font-black text-gray-900">{{ $activePeriod->gelombang }}</h4>
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest border border-emerald-200">AKTIF</span>
                            </div>
                            <p class="text-sm font-bold text-blue-600 mb-1">Tahun Ajaran {{ $activePeriod->tahunAjaran->nama }}</p>
                            <p class="text-xs font-medium text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($activePeriod->tanggal_mulai)->translatedFormat('d F Y') }} &mdash; {{ \Carbon\Carbon::parse($activePeriod->tanggal_selesai)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center">
                        <div>
                            <h4 class="text-xl font-black text-gray-900 mb-1">Pendaftaran Ditutup</h4>
                            <p class="text-sm font-medium text-gray-500">Tidak ada gelombang yang aktif untuk tanggal hari ini. Silakan buat atau aktifkan periode baru.</p>
                        </div>
                    </div>
                    @endif
                </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[11px] text-gray-400 capitalize tracking-[0.2em] bg-gray-50/50 py-4 px-6 mb-5">
                            <tr>
                                <th class="px-8 py-5 font-black">Tahun Ajaran</th>
                                <th class="px-6 py-5 font-black">Gelombang</th>
                                <th class="px-6 py-5 font-black">Mulai</th>
                                <th class="px-6 py-5 font-black">Selesai</th>
                                <th class="px-6 py-5 font-black text-center">Pendaftar</th>
                                <th class="px-6 py-5 font-black text-center">Status</th>
                                <th class="px-8 py-5 font-black text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($periods as $period)
                            <tr class="hover:bg-gray-50/50 transition-all group border-b border-slate-500">
                                <td class="px-8 py-6">
                                    <span class="font-bold text-gray-900">{{ $period->tahunAjaran->nama }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="font-bold text-blue-600">{{ $period->gelombang }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="font-medium text-gray-700 font-mono text-xs">{{ \Carbon\Carbon::parse($period->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="font-medium text-gray-700 font-mono text-xs">{{ \Carbon\Carbon::parse($period->tanggal_selesai)->translatedFormat('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="bg-gray-100 text-gray-600 font-black px-3 py-1.5 rounded-xl border border-gray-200">{{ $period->casis_count }}</span>
                                </td>
                                <td class="px-6 py-6 text-center align-middle items-center justify-center">
                                    @if($period->status == 'aktif')
                                        <div class="flex items-center justify-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-100 w-fit mx-auto">
                                            <span class="text-[10px] font-black capitalize tracking-widest">Aktif</span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center gap-2 text-gray-500 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 w-fit mx-auto">
                                            <span class="text-[10px] font-black capitalize tracking-widest">Nonaktif</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick='editPeriod(@json($period))' class="bg-amber-400 hover:bg-amber-500 text-white text-[10px] font-black capitalize tracking-widest px-3 py-2 rounded-xl shadow-lg shadow-amber-200 transition-all active:scale-95">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.jadwals.destroy', $period->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus periode ini? Pendaftar yang ada di dalamnya akan kehilangan relasi periode.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-[10px] font-black capitalize tracking-widest px-3 py-2 rounded-xl border border-red-100 transition-all active:scale-95">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-sm font-bold capitalize tracking-widest">Belum ada data periode pendaftaran</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Periode -->
    <div id="modalPeriode" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModalPeriode()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form id="formPeriode" action="{{ route('admin.jadwals.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formPeriodeMethod" value="POST">
                    <div class="px-8 pt-8 pb-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight" id="modal-title-periode">Tambah Periode Baru</h3>
                                <p class="text-xs text-gray-500 font-medium">Isi detail gelombang pendaftaran.</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black capitalize tracking-widest text-gray-400 mb-2 px-1">Tahun Ajaran</label>
                                <select name="tahun_ajaran_id" id="tahun_ajaran_id" required class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @foreach($tahunAjarans as $ta)
                                        <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black capitalize tracking-widest text-gray-400 mb-2 px-1">Gelombang (Nama Periode)</label>
                                <input type="text" name="gelombang" id="gelombang" required class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20 placeholder:text-gray-400" placeholder="Contoh: Gelombang 1">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black capitalize tracking-widest text-gray-400 mb-2 px-1">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" required class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black capitalize tracking-widest text-gray-400 mb-2 px-1">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" required class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black capitalize tracking-widest text-gray-400 mb-2 px-1">Status</label>
                                <select name="status" id="status" required class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-6 bg-gray-50 flex gap-3 justify-end">
                        <button type="button" onclick="closeModalPeriode()" class="bg-white hover:bg-gray-100 text-gray-600 text-[10px] font-black capitalize tracking-widest px-6 py-3.5 rounded-2xl transition-all border border-gray-200">Batal</button>
                        <button type="submit" class="bg-blue-700 hover:bg-blue-900 text-white text-[10px] font-black capitalize tracking-widest px-6 py-3.5 rounded-2xl transition-all active:scale-95 shadow-sm hover:shadow-lg shadow-gray-200">Simpan Periode</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tahun Ajaran -->
    <div id="modalTahunAjaran" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('modalTahunAjaran').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form action="{{ route('admin.tahun-ajarans.store') }}" method="POST">
                    @csrf
                    <div class="px-8 pt-8 pb-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Tambah Tahun Ajaran</h3>
                                <p class="text-xs text-gray-500 font-medium">Buat tahun ajaran baru untuk SPMB.</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black capitalize tracking-widest text-gray-400 mb-2 px-1">Nama Tahun Ajaran</label>
                                <input type="text" name="nama" required class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20 placeholder:text-gray-400" placeholder="Contoh: 2026/2027">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black capitalize tracking-widest text-gray-400 mb-2 px-1">Status</label>
                                <select name="status" required class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-6 bg-gray-50 flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('modalTahunAjaran').classList.add('hidden')" class="bg-white hover:bg-gray-100 text-gray-600 text-[10px] font-black capitalize tracking-widest px-6 py-3.5 rounded-2xl transition-all border border-gray-200">Batal</button>
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-[10px] font-black capitalize tracking-widest px-6 py-3.5 rounded-2xl transition-all active:scale-95 shadow-sm">Simpan TA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editPeriod(period) {
            document.getElementById('modal-title-periode').innerText = 'Edit Periode (Gelombang)';
            document.getElementById('formPeriode').action = `/admin/jadwals/${period.id}`;
            document.getElementById('formPeriodeMethod').value = 'PUT';
            
            document.getElementById('tahun_ajaran_id').value = period.tahun_ajaran_id;
            document.getElementById('gelombang').value = period.gelombang;
            document.getElementById('tanggal_mulai').value = period.tanggal_mulai.split('T')[0];
            document.getElementById('tanggal_selesai').value = period.tanggal_selesai.split('T')[0];
            document.getElementById('status').value = period.status;
            
            document.getElementById('modalPeriode').classList.remove('hidden');
        }

        function closeModalPeriode() {
            document.getElementById('modalPeriode').classList.add('hidden');
            document.getElementById('modal-title-periode').innerText = 'Tambah Periode Baru';
            document.getElementById('formPeriode').action = "{{ route('admin.jadwals.store') }}";
            document.getElementById('formPeriodeMethod').value = 'POST';
            document.getElementById('formPeriode').reset();
        }
    </script>
</x-app-layout>
