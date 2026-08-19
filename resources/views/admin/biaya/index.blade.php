<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic uppercase">Master Data Biaya</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola rincian tagihan biaya (SPP, Daftar Ulang, dll) untuk setiap jurusan.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openModalTambahBiaya()" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Biaya
                    </button>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[11px] text-gray-400 tracking-[0.2em] bg-gray-50/50 py-4 px-6 mb-5">
                            <tr>
                                <th class="px-6 py-5 font-black uppercase">Nama Biaya</th>
                                <th class="px-6 py-5 font-black uppercase">Jenis (Kategori)</th>
                                <th class="px-6 py-5 font-black uppercase">Nominal</th>
                                <th class="px-6 py-5 font-black uppercase">Berlaku Untuk Jurusan</th>
                                <th class="px-6 py-5 font-black uppercase text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 mb-5">
                            @forelse($biayas as $biaya)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-6 py-6 font-bold text-gray-900">{{ $biaya->nama_biaya }}</td>
                                <td class="px-6 py-6">
                                    <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded-lg">{{ $biaya->jenis_biaya }}</span>
                                </td>
                                <td class="px-6 py-6 font-mono font-bold text-gray-600">Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($biaya->jurusans as $jurusan)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $jurusan->kode }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="editBiaya({{ $biaya->id }}, '{{ addslashes($biaya->nama_biaya) }}', '{{ addslashes($biaya->jenis_biaya) }}', {{ $biaya->nominal }}, {{ json_encode($biaya->jurusans->pluck('id')) }})" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        
                                        <form id="form-delete-biaya-{{ $biaya->id }}" action="{{ route('admin.biaya.destroy', $biaya->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteBiaya({{ $biaya->id }})" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="mt-11">
                                <td colspan="5" class="mt-11 px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Belum ada data master biaya.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $biayas->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Biaya -->
    <div id="modalBiaya" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex justify-center items-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg my-8 overflow-hidden transform transition-all">
            <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center">
                <h3 id="modalBiayaTitle" class="text-lg font-black text-gray-900 uppercase tracking-tight italic">Tambah Biaya Baru</h3>
                <button onclick="closeModalBiaya()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="formBiaya" action="{{ route('admin.biaya.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formBiayaMethod" value="POST">
                
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Nama Biaya</label>
                        <input type="text" name="nama_biaya" id="nama_biaya" class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-gray-300" placeholder="Contoh: SPP Bulan Juli, Biaya Seragam..." required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Jenis (Kategori)</label>
                            <div class="flex items-center gap-2">
                                <select name="jenis_biaya" id="jenis_biaya" class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisBiayaOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="document.getElementById('modalTambahJenis').classList.remove('hidden')" class="bg-blue-100 text-blue-700 p-3 rounded-2xl hover:bg-blue-200 transition" title="Tambah Jenis Baru">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" id="nominal" class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 transition-all" required min="0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Berlaku Untuk Jurusan (Pilih Semua yang Sesuai)</label>
                        <div class="bg-gray-50 rounded-2xl p-4 grid grid-cols-2 gap-3 max-h-40 overflow-y-auto">
                            @foreach($jurusans as $jurusan)
                            <label class="flex items-start gap-2 cursor-pointer p-2 hover:bg-white rounded-xl transition-all border border-transparent hover:border-gray-100">
                                <input type="checkbox" name="jurusans[]" value="{{ $jurusan->id }}" class="jurusan-checkbox mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="text-[11px] font-bold text-gray-900 block leading-tight">{{ $jurusan->kode }}</span>
                                    <span class="text-[9px] font-bold text-gray-400 leading-tight">{{ Str::limit($jurusan->nama, 20) }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" onclick="closeModalBiaya()" class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-gray-700">Batal</button>
                    <button type="submit" id="btnSubmitBiaya" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-2xl shadow-lg shadow-blue-500/20 transition-all active:scale-95">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Jenis Biaya -->
    <div id="modalTambahJenis" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex justify-center items-center p-4">
        <div class="bg-white rounded-[2rem] shadow-xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight italic">Tambah Jenis Biaya</h3>
                <button onclick="document.getElementById('modalTambahJenis').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.biaya.store_jenis') }}" method="POST">
                @csrf
                <div class="p-8">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 px-1">Nama Jenis Baru</label>
                    <input type="text" name="nama_jenis" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-gray-300" placeholder="Contoh: Seragam, Pramuka, Tes Kesehatan..." required>
                    <p class="text-[10px] text-gray-500 mt-3 font-medium">Jenis biaya ini nantinya bisa dipilih pada menu dropdown saat menambah Master Biaya baru.</p>
                </div>
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalTambahJenis').classList.add('hidden')" class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-gray-700">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-2xl shadow-lg shadow-blue-500/20 transition-all active:scale-95">Simpan Jenis</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openModalTambahBiaya() {
            document.getElementById('modalBiayaTitle').innerText = 'TAMBAH BIAYA BARU';
            document.getElementById('formBiaya').action = "{{ route('admin.biaya.store') }}";
            document.getElementById('formBiayaMethod').value = "POST";
            document.getElementById('nama_biaya').value = '';
            document.getElementById('jenis_biaya').value = '';
            document.getElementById('nominal').value = '';
            
            document.querySelectorAll('.jurusan-checkbox').forEach(cb => cb.checked = false);
            
            document.getElementById('modalBiaya').classList.remove('hidden');
        }

        function closeModalBiaya() {
            document.getElementById('modalBiaya').classList.add('hidden');
        }

        function editBiaya(id, nama, jenis, nominal, jurusans) {
            document.getElementById('modalBiayaTitle').innerText = 'EDIT BIAYA';
            document.getElementById('formBiaya').action = "/admin/biaya/" + id;
            document.getElementById('formBiayaMethod').value = "PUT";
            document.getElementById('nama_biaya').value = nama;
            document.getElementById('jenis_biaya').value = jenis;
            document.getElementById('nominal').value = nominal;
            
            document.querySelectorAll('.jurusan-checkbox').forEach(cb => {
                cb.checked = jurusans.includes(parseInt(cb.value));
            });
            
            document.getElementById('modalBiaya').classList.remove('hidden');
        }
        function confirmDeleteBiaya(id) {
            Swal.fire({
                title: 'Hapus Biaya?',
                text: "Yakin ingin menghapus biaya ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'rounded-xl',
                    cancelButton: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-biaya-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
