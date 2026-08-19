<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen" x-data="programCrud()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic uppercase">Master Program Keunggulan</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola kelas binaan industri dan program khusus SPMB.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="openModal('create')" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Program
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[11px] text-gray-400 tracking-[0.2em] bg-gray-50/50 py-4 px-6 mb-5">
                            <tr>
                                <th class="px-6 py-5 font-black uppercase">Logo</th>
                                <th class="px-6 py-5 font-black uppercase">Nama Program / Kelas Binaan</th>
                                <th class="px-6 py-5 font-black uppercase text-center">Status</th>
                                <th class="px-6 py-5 font-black uppercase text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 mb-5">
                            @forelse($programs as $p)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-6 py-6">
                                    <div class="w-13 h-12 flex items-center justify-center overflow-hidden">
                                        @if($p->logo)
                                            <img src="{{ asset('storage/' . $p->logo) }}" alt="{{ $p->nama }}" class="w-full h-full object-contain p-1">
                                        @else
                                            <span class="text-gray-400 text-xs font-bold">No Logo</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="font-bold text-gray-900">{{ $p->nama }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs mt-1" title="{{ $p->deskripsi }}">{{ $p->deskripsi ?: '-' }}</div>
                                    @if($p->jurusan_id && $p->jurusan)
                                        <div class="mt-2"><span class="bg-blue-50 text-blue-700 text-[10px] font-black px-2 py-1 rounded-lg border border-blue-100 uppercase tracking-widest">{{ $p->jurusan->kode }}</span></div>
                                    @endif
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $p->status_aktif ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                                        {{ $p->status_aktif ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openModal('edit', {{ $p->toJson() }})" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteData({{ $p->id }})" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="mt-11">
                                <td colspan="4" class="mt-11 px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Belum ada data program keunggulan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                {{ $programs->links() }}
            </div>
        </div>

        <!-- Form Modal -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl" @click.stop>
                    
                    <div class="bg-white px-8 pt-8 pb-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 font-heading" x-text="modalMode === 'create' ? 'Tambah Program Keunggulan' : 'Edit Program Keunggulan'"></h3>
                            <p class="text-xs text-slate-500 mt-1">Lengkapi data kelas binaan/program unggulan.</p>
                        </div>
                        <button @click="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form @submit.prevent="submitForm()" x-ref="form" enctype="multipart/form-data">
                        @csrf
                        <div class="px-8 py-6 space-y-5 bg-slate-50/50">
                            <!-- Show errors -->
                            <div x-show="Object.keys(errors).length > 0" class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-600">
                                <ul class="list-disc pl-5 space-y-1">
                                    <template x-for="(err, field) in errors" :key="field">
                                        <li x-text="err[0]"></li>
                                    </template>
                                </ul>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Program / Kelas Binaan <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" x-model="formData.nama" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                                <textarea name="deskripsi" x-model="formData.deskripsi" rows="3" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Terkait Jurusan (Opsional)</label>
                                <select name="jurusan_id" x-model="formData.jurusan_id" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                    <option value="">-- Berlaku untuk Semua Jurusan --</option>
                                    @foreach($jurusans as $j)
                                        <option value="{{ $j->id }}">{{ $j->nama }} ({{ $j->kode }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="modalMode === 'edit'">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Aktif <span class="text-red-500">*</span></label>
                                <select name="status_aktif" x-model="formData.status_aktif" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Logo Kelas Binaan / Mitra</label>
                                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                <p class="text-[11px] text-slate-400 mt-1">Abaikan jika tidak ingin mengubah/mengupload logo baru.</p>
                            </div>
                        </div>

                        <div class="px-8 py-5 bg-white border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl">
                            <button type="button" @click="closeModal()" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                            <button type="submit" :disabled="loading" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition flex items-center gap-2">
                                <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="modalMode === 'create' ? 'Simpan Data' : 'Update Data'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function programCrud() {
            return {
                showModal: false,
                modalMode: 'create',
                editId: null,
                loading: false,
                errors: {},
                formData: {
                    nama: '',
                    deskripsi: '',
                    jurusan_id: '',
                    status_aktif: 1
                },
                init() {},
                openModal(mode, data = null) {
                    this.modalMode = mode;
                    this.errors = {};
                    this.$refs.form.reset();

                    if (mode === 'edit' && data) {
                        this.editId = data.id;
                        this.formData = {
                            nama: data.nama,
                            deskripsi: data.deskripsi || '',
                            jurusan_id: data.jurusan_id || '',
                            status_aktif: data.status_aktif ? 1 : 0
                        };
                    } else {
                        this.editId = null;
                        this.formData = {
                            nama: '',
                            deskripsi: '',
                            jurusan_id: '',
                            status_aktif: 1
                        };
                    }
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                },
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    
                    let url = '{{ route("admin.program-keunggulan.store") }}';
                    let formElement = this.$refs.form;
                    let submitData = new FormData(formElement);
                    
                    if (this.modalMode === 'edit') {
                        url = `/admin/program-keunggulan/${this.editId}`;
                        submitData.append('_method', 'PUT');
                    }

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            body: submitData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            if (response.status === 422) {
                                this.errors = result.errors;
                            } else {
                                Swal.fire('Error', result.message || 'Terjadi kesalahan sistem', 'error');
                            }
                        } else {
                            this.closeModal();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    } catch (err) {
                        Swal.fire('Error', 'Terjadi kesalahan jaringan', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                deleteData(id) {
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/program-keunggulan/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => window.location.reload());
                                }
                            });
                        }
                    })
                }
            }
        }
    </script>
</x-app-layout>
