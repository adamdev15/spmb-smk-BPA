<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('admin.casis.index') }}" class="inline-flex items-center text-xs font-bold text-slate-400 hover:text-blue-600 transition gap-2 mb-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Pendaftar
                </a>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">
                    {{ isset($casis) ? 'Edit Data Pendaftar' : 'Tambah Pendaftar Offline / Manual' }}
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    {{ isset($casis) ? 'Perbarui informasi pendaftaran calon siswa.' : 'Tambahkan pendaftar offline langsung dari loket pendaftaran sekolah.' }}
                </p>
            </div>

            @if ($errors->any())
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pengisian form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ isset($casis) ? route('admin.casis.update', $casis->id) : route('admin.casis.store') }}" method="POST" id="casisForm" enctype="multipart/form-data">
                @csrf
                @if(isset($casis))
                    @method('PUT')
                @endif

                <!-- Section 1: Pengaturan Sumber & Jurusan -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-8 mb-8">
                    <h3 class="text-lg font-bold text-slate-900 font-heading mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">1</div>
                        Pilihan Program Keahlian (Jurusan) & Sumber Data
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Program Keahlian (Jurusan) <span class="text-red-500">*</span>
                            </label>
                            <select name="jurusan_id" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" required>
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($jurusans as $j)
                                <option value="{{ $j->id }}" {{ old('jurusan_id', $casis->jurusan_id ?? '') == $j->id ? 'selected' : '' }}>
                                    {{ $j->kode }} - {{ $j->nama }} (Sisa Kuota: {{ $j->sisa_kuota }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Program Keunggulan Industri
                            </label>
                            <select name="program_keunggulan_id" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                                <option value="">-- Pilih Program Keunggulan --</option>
                                @foreach($programs as $p)
                                <option value="{{ $p->id }}" {{ old('program_keunggulan_id', $casis->program_keunggulan_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Sumber Pendaftaran <span class="text-red-500">*</span>
                            </label>
                            <select name="sumber_pendaftaran" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" required>
                                <option value="offline" {{ old('sumber_pendaftaran', $casis->sumber_pendaftaran ?? 'offline') == 'offline' ? 'selected' : '' }}>OFFLINE (Loket Sekolah)</option>
                                <option value="online" {{ old('sumber_pendaftaran', $casis->sumber_pendaftaran ?? '') == 'online' ? 'selected' : '' }}>ONLINE (Website)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">
                                No. HP WhatsApp Siswa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa', $casis->no_hp_siswa ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Data Pribadi Siswa -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-8 mb-8">
                    <h3 class="text-lg font-bold text-slate-900 font-heading mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">2</div>
                        Data Pribadi Siswa
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $casis->nama_lengkap ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" placeholder="Nama Lengkap Siswa" required>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">NISN <span class="text-red-500">*</span></label>
                            <input type="text" name="nisn" value="{{ old('nisn', $casis->nisn ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" placeholder="10 Digit NISN" required>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">NIK Siswa</label>
                            <input type="text" name="nik" value="{{ old('nik', $casis->nik ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" placeholder="16 Digit NIK">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jk" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jk', $casis->jk ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk', $casis->jk ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $casis->tempat_lahir ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" placeholder="Kota Lahir">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $casis->tgl_lahir ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Agama <span class="text-red-500">*</span></label>
                            <select name="agama" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('agama', $casis->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama', $casis->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama', $casis->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama', $casis->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama', $casis->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama', $casis->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Lengkap Siswa</label>
                            <input type="text" name="alamat_siswa" value="{{ old('alamat_siswa', $casis->alamat_siswa ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" placeholder="Jalan, RT/RW, Desa, Kecamatan, Kabupaten">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Data Orang Tua & Sekolah -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-8 mb-8">
                    <h3 class="text-lg font-bold text-slate-900 font-heading mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">3</div>
                        Data Orang Tua & Sekolah Asal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Ayah</label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $casis->nama_ayah ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Ibu</label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $casis->nama_ibu ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Sekolah Asal (SMP/MTs)</label>
                            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $casis->nama_sekolah ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50" placeholder="SMPN 1 Adiwerna">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Sekolah Asal</label>
                            <input type="text" name="alamat_sekolah" value="{{ old('alamat_sekolah', $casis->alamat_sekolah ?? '') }}" class="w-full rounded-2xl border-slate-300 py-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Upload Dokumen -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-8 mb-8">
                    <h3 class="text-lg font-bold text-slate-900 font-heading mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">4</div>
                        Upload Dokumen Pendaftaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Pas Foto 3x4</label>
                            <input type="file" name="pas_foto" accept="image/jpeg,image/png,image/jpg" class="w-full rounded-2xl border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                            <p class="text-slate-400 mt-1" style="font-size: 11px;">Format: JPG, JPEG, PNG (Maks 2MB)</p>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Scan/Foto Kartu Keluarga (KK)</label>
                            <input type="file" name="fc_kk" accept="image/jpeg,image/png,image/jpg,application/pdf" class="w-full rounded-2xl border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                            <p class="text-slate-400 mt-1" style="font-size: 11px;">Format: JPG, PNG, PDF (Maks 2MB)</p>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Scan/Foto Akta Kelahiran</label>
                            <input type="file" name="fc_akta" accept="image/jpeg,image/png,image/jpg,application/pdf" class="w-full rounded-2xl border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                            <p class="text-slate-400 mt-1" style="font-size: 11px;">Format: JPG, PNG, PDF (Maks 2MB)</p>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Scan/Foto Ijazah / SKL</label>
                            <input type="file" name="fc_ijazah" accept="image/jpeg,image/png,image/jpg,application/pdf" class="w-full rounded-2xl border-slate-300 py-2.5 px-3 text-sm focus:border-blue-600 focus:ring-blue-100 bg-slate-50">
                            <p class="text-slate-400 mt-1" style="font-size: 11px;">Format: JPG, PNG, PDF (Maks 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.casis.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl text-xs transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-xs shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5">
                        {{ isset($casis) ? 'Simpan Perubahan' : 'Tambah Pendaftar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('casisForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: '{{ isset($casis) ? "Apakah Anda yakin ingin menyimpan perubahan data siswa ini?" : "Apakah Anda yakin ingin menambahkan pendaftar baru ini?" }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2491CA',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
</x-app-layout>