<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic capitalize">Detail Daftar Ulang</h1>
                    <p class="text-gray-500 text-sm mt-1">Informasi lengkap calon siswa dan data pembayarannya.</p>
                </div>
                <a href="{{ route('admin.pembayaran.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold px-5 py-3 rounded-xl border border-gray-200 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Validation Error/Success Messages -->
            @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <!-- Main Content Card -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    
                    <!-- KIRI: DATA SISWA -->
                    <div class="p-8 md:border-r border-gray-100">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 tracking-tight capitalize">Data Siswa</h2>
                                <p class="text-xs text-gray-500 font-medium">Informasi biodata pendaftar</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Nama Lengkap</p>
                                <p class="text-base font-bold text-gray-900">{{ $casis->nama_lengkap }}</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">NISN</p>
                                    <p class="text-sm font-mono font-bold text-gray-800">{{ $casis->nisn }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">No. Pendaftaran</p>
                                    <p class="text-sm font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md w-fit">{{ $casis->no_pendaftaran }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Asal Sekolah</p>
                                <p class="text-sm font-medium text-gray-700 italic">{{ $casis->nama_sekolah }}</p>
                            </div>

                            <div>
                                <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Pilihan Jurusan</p>
                                <p class="text-sm font-bold text-gray-900">{{ $casis->jurusan ? $casis->jurusan->nama : '-' }}</p>
                            </div>

                            <div>
                                <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Program Keunggulan</p>
                                <p class="text-sm font-bold text-gray-900">{{ $casis->programKeunggulan ? $casis->programKeunggulan->nama : '-' }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Nomor WhatsApp</p>
                                    <p class="text-sm font-mono font-bold text-gray-800">{{ $casis->no_hp_siswa ?: '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Status Verifikasi</p>
                                    <span class="text-[10px] font-black capitalize tracking-widest text-blue-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-100">
                                        {{ $casis->status_verifikasi }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: DATA PEMBAYARAN -->
                    <div class="p-8 bg-gray-50/50">
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight capitalize">Data Pembayaran</h2>
                                    <p class="text-xs text-gray-500 font-medium">Status dan riwayat daftar ulang</p>
                                </div>
                            </div>
                            
                            @if($casis->status_daftar_ulang == 'Sudah')
                            <div class="text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-200">
                                <span class="text-xs font-black capitalize tracking-widest">Sudah Daftar Ulang</span>
                            </div>
                            @else
                            <div class="text-gray-500 bg-white shadow-sm px-4 py-2 rounded-xl border border-gray-200">
                                <span class="text-xs font-black capitalize tracking-widest">Belum Daftar Ulang</span>
                            </div>
                            @endif
                        </div>

                        @php $pembayaran = $casis->pembayaranTerakhir; @endphp

                        @if($pembayaran)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b border-dashed border-gray-200">
                                <div>
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Order ID / Nomor Pembayaran</p>
                                    <p class="text-sm font-mono font-bold text-gray-900">{{ $pembayaran->order_id }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Status</p>
                                    @if($pembayaran->transaction_status == 'settlement' || $pembayaran->transaction_status == 'success')
                                        <span class="text-[10px] font-black capitalize tracking-widest text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">Berhasil</span>
                                    @elseif($pembayaran->transaction_status == 'pending')
                                        <span class="text-[10px] font-black capitalize tracking-widest text-amber-600 bg-amber-50 px-2 py-1 rounded-md">Pending</span>
                                    @elseif($pembayaran->transaction_status == 'expire')
                                        <span class="text-[10px] font-black capitalize tracking-widest text-gray-600 bg-gray-50 px-2 py-1 rounded-md">Expired</span>
                                    @elseif($pembayaran->transaction_status == 'cancel')
                                        <span class="text-[10px] font-black capitalize tracking-widest text-gray-600 bg-gray-50 px-2 py-1 rounded-md">Cancel</span>
                                    @else
                                        <span class="text-[10px] font-black capitalize tracking-widest text-red-600 bg-red-50 px-2 py-1 rounded-md">Gagal</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Nominal Pembayaran</p>
                                <p class="text-2xl font-black text-gray-900">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Metode (Type)</p>
                                    @if($pembayaran->tipe_pembayaran == 'online')
                                        <span class="text-xs font-bold text-blue-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                            Online
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-orange-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            Offline (Manual)
                                        </span>
                                    @endif
                                    @if($pembayaran->payment_type)
                                    <p class="text-[10px] text-gray-500 mt-1 capitalize">{{ str_replace('_', ' ', $pembayaran->payment_type) }}</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Waktu Transaksi</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $pembayaran->created_at ? $pembayaran->created_at->format('d M Y, H:i') : '-' }}</p>
                                    @if($pembayaran->settlement_time)
                                    <p class="text-[10px] text-emerald-600 mt-1">Lunas: {{ \Carbon\Carbon::parse($pembayaran->settlement_time)->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($pembayaran->catatan_admin)
                            <div class="pt-4 border-t border-dashed border-gray-200">
                                <p class="text-[10px] font-black capitalize tracking-widest text-gray-400 mb-1">Catatan Admin</p>
                                <p class="text-sm font-medium text-gray-700 italic bg-gray-50 p-3 rounded-xl border border-gray-100">{{ $pembayaran->catatan_admin }}</p>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-700 mb-1">Belum Ada Transaksi</h3>
                            <p class="text-xs text-gray-400">Siswa belum melakukan proses daftar ulang maupun pembayaran.</p>
                            
                            @if($casis->no_hp_siswa && $casis->status_daftar_ulang != 'Sudah')
                            <form action="{{ route('admin.pembayaran.reminder', $casis->id) }}" method="POST" class="mt-6">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-lg shadow-green-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    Kirim Pengingat Daftar Ulang (WA)
                                </button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
