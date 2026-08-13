<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen" x-data="masterData()" x-init="init()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                        <a href="{{ route('admin.master-data.index') }}" class="hover:text-green-600 transition">Master Data</a>
                        <span>/</span>
                        <span class="text-gray-900 font-semibold">{{ $label }}</span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Master Data {{ $label }}</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola data {{ strtolower($label) }} untuk formulir pendaftaran.</p>
                </div>
                <button @click="openModal('create')"
                    class="bg-green-600 hover:bg-green-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-2xl shadow-lg shadow-green-200 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Data
                </button>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Daftar {{ $label }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Total terdapat {{ count($data) }} data terdaftar.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[10px] text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-4 font-black w-16">No</th>
                                <th class="px-6 py-4 font-black">Nama</th>
                                @if($type === 'ketrampilan')
                                <th class="px-6 py-4 font-black">Jenis Kelamin</th>
                                @endif
                                @if($type === 'pekerjaan')
                                <th class="px-6 py-4 font-black">Target</th>
                                @endif
                                <th class="px-8 py-4 font-black text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($data as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold text-gray-400">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="font-bold text-gray-900 uppercase">{{ $item->nama }}</span>
                                </td>
                                @if($type === 'ketrampilan')
                                <td class="px-6 py-6">
                                    <span class="text-xs font-medium text-gray-600">
                                        {{ $item->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                @endif
                                @if($type === 'pekerjaan')
                                <td class="px-6 py-6">
                                    <span class="bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl border border-blue-100">
                                        {{ ucfirst($item->target) }}
                                    </span>
                                </td>
                                @endif
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openModal('edit', {{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $type === 'ketrampilan' ? "'{$item->jk}'" : 'null' }}, {{ $type === 'pekerjaan' ? "'{$item->target}'" : 'null' }})"
                                            class="bg-amber-50 hover:bg-amber-400 hover:text-white text-amber-600 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl border border-amber-100 transition-all">
                                            Edit
                                        </button>
                                        <button @click="deleteData({{ $item->id }})"
                                            class="bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl border border-red-100 transition-all">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $type === 'ketrampilan' || $type === 'pekerjaan' ? '4' : '3' }}" class="px-8 py-12 text-center">
                                    <p class="text-gray-400 text-sm">Belum ada data</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="closeModal()"
            style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" style="opacity:0.4" @click="closeModal()"></div>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    @click.stop
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-xl font-bold text-gray-900 font-playfair">
                                <span x-text="modalMode === 'create' ? 'Tambah' : 'Edit'"></span> {{ $label }}
                            </h3>
                            <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="submitForm()">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="formData.nama" required
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 bg-gray-50 focus:bg-white transition"
                                        placeholder="Masukkan nama">
                                </div>

                                @if($type === 'ketrampilan')
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <select x-model="formData.jk" required
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 bg-gray-50 focus:bg-white transition">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                @endif

                                @if($type === 'pekerjaan')
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Target <span class="text-red-500">*</span></label>
                                    <select x-model="formData.target" required
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 bg-gray-50 focus:bg-white transition">
                                        <option value="">Pilih Target</option>
                                        <option value="ayah">Ayah</option>
                                        <option value="ibu">Ibu</option>
                                        <option value="wali">Wali</option>
                                        <option value="semua">Semua</option>
                                    </select>
                                </div>
                                @endif
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" @click="closeModal()"
                                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition text-sm">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition shadow-md hover:shadow-lg text-sm">
                                    <span x-text="modalMode === 'create' ? 'Simpan' : 'Update'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function masterData() {
            return {
                showModal: false,
                modalMode: 'create',
                formData: {
                    nama: '',
                    jk: '',
                    target: ''
                },
                editId: null,
                type: @json($type),

                init() {
                    // Initialize
                },

                openModal(mode, id = null, nama = '', jk = null, target = null) {
                    this.modalMode = mode;
                    this.formData = {
                        nama: nama || '',
                        jk: jk || '',
                        target: target || ''
                    };
                    this.editId = id;
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                    this.formData = { nama: '', jk: '', target: '' };
                    this.editId = null;
                },

                async submitForm() {
                    const url = this.modalMode === 'create' 
                        ? `/admin/master-data/${this.type}`
                        : `/admin/master-data/${this.type}/${this.editId}`;
                    
                    const method = this.modalMode === 'create' ? 'POST' : 'PUT';

                    try {
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#16a34a'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                },

                async deleteData(id) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`/admin/master-data/${this.type}/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                });

                                const data = await response.json();

                                if (data.success) {
                                    Swal.fire({
                                        title: 'Dihapus!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonColor: '#16a34a'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                                }
                            } catch (error) {
                                console.error(error);
                                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                            }
                        }
                    });
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>

