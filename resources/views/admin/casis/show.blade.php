<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Navigation -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Detail Peserta</h1>
                    <p class="text-gray-500 text-sm mt-1">Review data lengkap dan berkas pendaftaran peserta.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.casis.index') }}"
                        class="bg-white hover:bg-gray-50 text-gray-600 text-[10px] font-black capitalize tracking-widest px-6 py-3 rounded-2xl border border-gray-100 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali Ke Daftar
                    </a>
                    <a href="{{ route('admin.casis.edit', $casis->id) }}"
                        class="bg-amber-400 hover:bg-amber-500 text-white text-[10px] font-black capitalize tracking-widest px-6 py-3 rounded-2xl shadow-lg shadow-amber-200 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Data
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column: Profile & Docs -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Basic Info Card -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                Profil Peserta
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <section>
                                    <p
                                        class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-4 scale-90 origin-left">
                                        Identitas Siswa</p>
                                    <dl class="space-y-4">
                                        <div>
                                            <dt class="text-xs text-gray-400">Nama Lengkap</dt>
                                            <dd class="text-sm font-bold text-gray-900 capitalize">{{
                                                $casis->nama_lengkap }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-400">NISN / NIK</dt>
                                            <dd class="text-sm font-bold text-gray-900 text-mono">{{ $casis->nisn }} /
                                                {{ $casis->nik ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-400">Jenis Kelamin</dt>
                                            <dd class="text-sm font-bold text-gray-900">{{ $casis->jk == 'L' ?
                                                'LAKI-LAKI' : 'PEREMPUAN' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-400">Tempat, Tanggal Lahir</dt>
                                            <dd class="text-sm font-bold text-gray-900 capitalize">{{
                                                $casis->tempat_lahir }}, {{
                                                \Carbon\Carbon::parse($casis->tgl_lahir)->translatedFormat('d F Y') }}
                                            </dd>
                                        </div>
                                    </dl>
                                </section>
                                <section>
                                    <p
                                        class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-4 scale-90 origin-left">
                                        Sekolah & Kontak</p>
                                    <dl class="space-y-4">
                                        <div>
                                            <dt class="text-xs text-gray-400">Sekolah Asal</dt>
                                            <dd class="text-sm font-bold text-gray-900 capitalize italic">{{
                                                $casis->nama_sekolah }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-400">No. HP Siswa</dt>
                                            <dd class="text-sm font-bold text-gray-900">{{ $casis->no_hp_siswa }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-400">Nama Ayah / Ibu</dt>
                                            <dd class="text-sm font-bold text-gray-900 capitalize">{{ $casis->nama_ayah
                                                ?? '-' }} / {{ $casis->nama_ibu ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-400">Jurusan Keahlian</dt>
                                            <dd class="text-sm font-bold text-gray-900">{{ $casis->jurusan->nama ?? '-' }}</dd>
                                        </div>
                                    </dl>
                                </section>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                Berkas Terunggah
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                $required_docs = [
                                'Pas Foto 3x4' => '1. Pas Foto 3x4',
                                'FC Kartu Keluarga' => '2. FC Kartu Keluarga',
                                'FC Akta Kelahiran' => '3. FC Akta Kelahiran',
                                'FC Ijazah / SKL' => '4. FC Ijazah / SKL'
                                ];
                                @endphp

                                @foreach($required_docs as $key => $label)
                                @php
                                $file = $casis->berkas->where('nama_berkas', $key)->first();
                                $is_mandatory = ($key === 'Pas Foto 3x4');
                                @endphp
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-blue-200 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl {{ $file ? 'bg-blue-100 text-blue-600' : ($is_mandatory ? 'bg-red-50 text-red-300' : 'bg-gray-100 text-gray-300') }} flex items-center justify-center transition-colors">
                                            @if($file)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                            </svg>
                                            @else
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p
                                                    class="text-[11px] font-black text-gray-900 capitalize tracking-tight">
                                                    {{ $label }}</p>
                                                @if($is_mandatory)
                                                <span
                                                    class="text-[8px] font-black text-white bg-red-500 px-1.5 py-0.5 rounded capitalize tracking-widest">Wajib</span>
                                                @endif
                                            </div>
                                            <p
                                                class="text-[9px] font-bold {{ $file ? 'text-blue-600' : 'text-gray-400' }} capitalize tracking-widest mt-0.5">
                                                {{ $file ? 'Berhasil Diunggah' : ($is_mandatory ? 'Belum Ada Berkas' :
                                                'Opsional') }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($file)
                                    <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                                        class="bg-white hover:bg-blue-600 hover:text-white text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center shadow-sm border border-blue-500/10 transition-all active:scale-90">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            @if($casis->status_verifikasi != 'Diverifikasi')
                            <div
                                class="mt-8 p-10 rounded-[2.5rem] bg-amber-50 border-2 border-dashed border-amber-200 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-amber-200/20 rounded-full blur-2xl">
                                </div>
                                <div class="flex items-center gap-6 text-center md:text-left relative">
                                    <div
                                        class="w-14 h-14 bg-amber-400 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200 animate-pulse">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-black text-amber-900 capitalize tracking-tight">Menunggu
                                            Verifikasi</h4>
                                        <p
                                            class="text-xs text-amber-700 font-bold mt-1 max-w-xs leading-relaxed italic">
                                            Pastikan seluruh berkas wajib telah sesuai dengan data asli pendaftar.</p>
                                    </div>
                                </div>
                                <form action="{{ route('admin.verify', $casis->id) }}" method="POST" class="relative">
                                    @csrf
                                    <button type="submit"
                                        class="bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-black capitalize tracking-widest px-10 py-5 rounded-2xl shadow-xl shadow-amber-200 transition-all active:scale-95 group flex items-center gap-3">
                                        <span>Verifikasi Sekarang</span>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @else
                            <div
                                class="mt-8 p-10 rounded-[2.5rem] bg-blue-50 border border-blue-200 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-blue-200/20 rounded-full blur-2xl">
                                </div>
                                <div class="flex items-center gap-6 relative">
                                    <div
                                        class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-black text-blue-900 capitalize tracking-tighter italic">
                                            Akun Terverifikasi</h4>
                                        <p class="text-[10px] text-blue-700 font-bold capitalize tracking-widest mt-1">
                                            Diproses pada {{ $casis->updated_at->format('d/m/Y H:i') }} WIB
                                        </p>
                                    </div>
                                </div>

                                @if(Auth::user()->role === 'admin')
                                <form action="{{ route('admin.unverify', $casis->id) }}" method="POST" class="relative"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan verifikasi ini? Data akan kembali ke status pendaftaran.')">
                                    @csrf
                                    <button type="submit"
                                        class="bg-white hover:bg-red-50 text-red-600 text-[10px] font-black capitalize tracking-widest px-8 py-4 rounded-2xl border-2 border-red-100 transition-all active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Batal Verifikasi
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Results & Status -->
                <div class="space-y-8">

                    @if(Auth::user()->role === 'admin')
                    <!-- Selection Card -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                Hasil Pendaftaran Siswa
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="mb-8 space-y-3">
                                <!-- Kartu Pendaftaran Siswa -->
                                <a href="{{ route('admin.casis.print.kartu', $casis->id) }}" target="_blank" class="w-full flex items-center justify-between bg-white border border-gray-200 hover:border-blue-500 hover:shadow-md text-gray-700 hover:text-blue-600 px-5 py-4 rounded-2xl transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-gray-50 group-hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-bold">Kartu Pendaftaran</p>
                                            <p class="text-[10px] text-gray-400">Download bukti registrasi</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>

                                <!-- Formulir Pendaftaran -->
                                <a href="{{ route('admin.casis.print.formulir', $casis->id) }}" target="_blank" class="w-full flex items-center justify-between bg-white border border-gray-200 hover:border-blue-500 hover:shadow-md text-gray-700 hover:text-blue-600 px-5 py-4 rounded-2xl transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-gray-50 group-hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-bold">Formulir Siswa</p>
                                            <p class="text-[10px] text-gray-400">Download formulir lengkap</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>

                                <!-- Hasil Kelulusan Siswa, dengan Download Surat Pengumuman -->
                                @if($casis->status_kelulusan === 'Lulus' || $casis->status_kelulusan === 'Tidak Lulus' || $casis->status_kelulusan === 'Cadangan')
                                <a href="{{ route('admin.casis.print.pengumuman', $casis->id) }}" target="_blank" class="w-full flex items-center justify-between bg-white border border-gray-200 hover:border-blue-500 hover:shadow-md text-gray-700 hover:text-blue-600 px-5 py-4 rounded-2xl transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-gray-50 group-hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-bold">Surat Pengumuman</p>
                                            <p class="text-[10px] text-gray-400">Download hasil kelulusan</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>
                                @endif
                            </div>

                            <form action="{{ route('admin.casis.selection', $casis->id) }}" method="POST"
                                class="space-y-6">
                                @csrf
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-gray-400 capitalize tracking-widest mb-2 px-1">Status
                                        Kelulusan</label>
                                    <select name="status_kelulusan"
                                        class="bg-gray-50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition-all">
                                        <option value="Proses" {{ $casis->status_kelulusan == 'Proses' ? 'selected' : ''
                                            }}>PROSES</option>
                                        <option value="Lulus" {{ $casis->status_kelulusan == 'Lulus' ? 'selected' : ''
                                            }}>LULUS</option>
                                        <option value="Tidak Lulus" {{ $casis->status_kelulusan == 'Tidak Lulus' ?
                                            'selected' : '' }}>TIDAK LULUS</option>
                                        <option value="Cadangan" {{ $casis->status_kelulusan == 'Cadangan' ? 'selected'
                                            : '' }}>CADANGAN</option>
                                    </select>
                                </div>

                                <button type="submit"
                                    class="w-full bg-blue-500 hover:bg-blue-700 text-white text-[10px] font-black capitalize tracking-widest py-4 rounded-2xl shadow-xl transition-all active:scale-95">
                                    Simpan Hasil Kelulusan
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Pembayaran Card -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-50 bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                Informasi Daftar Ulang
                            </h3>
                        </div>
                        <div class="p-4">
                            @if($pembayaran)
                            <div class="flex justify-between items-center">
                                <div class="mb-2">
                                    <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-1">Status</p>
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-lg 
                                        {{ $pembayaran->transaction_status === 'settlement' || $casis->status_daftar_ulang === 'Sudah' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $pembayaran->transaction_status === 'settlement' || $casis->status_daftar_ulang === 'Sudah' ? 'LUNAS' : strtoupper($pembayaran->transaction_status) }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-1">Nominal</p>
                                    <p class="text-sm font-bold text-gray-900">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="mb-2">
                                    <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-1">Metode</p>
                                    <p class="text-xs font-bold text-gray-900">{{ $pembayaran->tipe_pembayaran === 'offline' ? 'Offline (Kasir)' : 'Online (' . ($pembayaran->payment_type ?? 'Midtrans') . ')' }}</p>
                                </div>
                                <div class="mb-2">
                                    <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-1">Tanggal bayar</p>
                                    <p class="text-xs font-bold text-gray-900">{{ $pembayaran->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-xs text-gray-500 mb-4">Belum ada tagihan daftar ulang.</p>
                                    <a href="{{ route('admin.pembayaran.show', $casis->id) }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black capitalize tracking-widest px-6 py-3 rounded-xl transition-all shadow-md shadow-blue-500/20">
                                        Kelola Pembayaran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Stats -->
                    <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white/10 rounded-full blur-3xl">
                        </div>
                        <p class="text-[10px] font-black capitalize tracking-[0.2em] text-white/90 mb-6">Informasi
                            Tambahan</p>
                        <ul class="space-y-6 relative">
                            <li class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-white/50 capitalize tracking-tight">Terdaftar Sejak</p>
                                    <p class="text-xs font-bold">{{ $casis->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </li>
                            <li class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-white/50 capitalize tracking-tight">Keterampilan Dipilih
                                    </p>
                                    <p class="text-xs font-bold">{{ $casis->jurusan->nama ?? '-' }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
