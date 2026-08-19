<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen" x-data="{ openPaymentModal: false, paymentTab: 'offline' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumb & Top Bar -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.pembayaran.index') }}"
                        class="p-2.5 bg-white border border-gray-200 text-gray-600 hover:text-blue-600 rounded-2xl transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-400">Pembayaran</span>
                            <span class="text-xs font-bold text-gray-300">/</span>
                            <span class="text-xs font-bold text-blue-600">Detail Tagihan Siswa</span>
                        </div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 capitalize tracking-tight mt-0.5">
                            {{ $casis->nama_lengkap }}
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($pembayaran && ($pembayaran->transaction_status === 'settlement' || $pembayaran->transaction_status === 'success'))
                        <button type="button" disabled
                            class="px-6 py-3 bg-emerald-600 text-white opacity-70 cursor-not-allowed rounded-2xl text-xs font-black uppercase tracking-wider shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Pembayaran Berhasil
                        </button>
                    @else
                        <button type="button" @click="openPaymentModal = true"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Proses Pembayaran
                        </button>
                    @endif

                    @if($casis->no_hp_siswa && $casis->status_daftar_ulang !== 'Sudah')
                    <form action="{{ route('admin.pembayaran.reminder', $casis->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-5 py-3 bg-green-500 hover:bg-green-600 text-white rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-green-500/20 flex items-center gap-2 active:scale-95" onclick="event.preventDefault(); Swal.fire({title: 'Kirim Notifikasi WhatsApp?', text: 'Kirim rincian tagihan ke {{ addslashes($casis->nama_lengkap) }}?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Kirim', confirmButtonColor: '#10B981', cancelButtonText: 'Batal'}).then((result) => { if(result.isConfirmed) { Swal.fire({title: 'Memproses...', text: 'Mengirim pesan WhatsApp...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }}); this.closest('form').submit(); } })">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.03A10.02 10.02 0 002.03 12c0 1.76.46 3.42 1.28 4.88L2 21.99l5.25-1.37a9.98 9.98 0 004.76 1.18c5.52 0 10.02-4.5 10.02-10.02S17.53 2.03 12.01 2.03zm5.42 14.5c-.24.67-1.38 1.25-1.93 1.34-.52.09-1.21.15-3.52-.8-2.79-1.16-4.6-4.04-4.74-4.23-.14-.19-1.13-1.51-1.13-2.88 0-1.38.72-2.06 1-2.36.27-.29.59-.36.78-.36.2 0 .39 0 .56.01.19.01.44-.07.67.5.24.58.84 2.04.91 2.18.07.14.12.3.03.49-.09.19-.14.3-.29.47-.14.16-.31.36-.43.49-.14.14-.28.29-.12.56.16.27.7 1.16 1.51 1.88.75.66 1.62.87 1.84.97.23.09.36.08.49-.07.13-.15.58-.67.73-.9.15-.24.3-.2.52-.12.22.08 1.39.66 1.63.78.24.12.4.18.45.28.06.1.06.57-.18 1.24z"/></svg>
                            Kirim WA
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Student Details -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg font-mono">
                                {{ strtoupper(substr($casis->nama_lengkap, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $casis->nama_lengkap }}</h3>
                                <p class="text-xs text-gray-500 font-mono mt-0.5">NISN: {{ $casis->nisn }}</p>
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md inline-block mt-1.5">
                                    {{ $casis->no_pendaftaran }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4 text-xs">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">Jurusan</span>
                                <span class="font-bold text-gray-900 text-right">{{ $casis->jurusan ? $casis->jurusan->nama : '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">Program</span>
                                <span class="font-bold text-gray-900 text-right">{{ $casis->programKeunggulan ? $casis->programKeunggulan->nama : '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">Status Verifikasi</span>
                                <span>
                                    @if($casis->status_verifikasi === 'Diverifikasi')
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded font-bold text-[10px]">Diverifikasi</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded font-bold text-[10px]">Pending</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">Status Kelulusan</span>
                                <span>
                                    @if($casis->status_kelulusan === 'Lulus')
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded font-bold text-[10px]">Lulus</span>
                                    @elseif($casis->status_kelulusan === 'Tidak Lulus')
                                        <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded font-bold text-[10px]">Tidak Lulus</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-50 text-gray-700 rounded font-bold text-[10px]">Proses</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">Daftar Ulang</span>
                                <span>
                                    @if($casis->status_daftar_ulang === 'Sudah')
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded font-bold text-[10px]">Sudah (Lunas)</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded font-bold text-[10px]">Belum</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">WhatsApp Siswa</span>
                                <span class="font-mono font-bold text-gray-900">{{ $casis->no_hp_siswa ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">Asal Sekolah</span>
                                <span class="font-bold text-gray-900 text-right">{{ $casis->nama_sekolah ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Column: Tagihan & Payment History -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Tagihan Utama Card -->
                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md inline-block mb-1">
                                    Tagihan Utama
                                </span>
                                <h3 class="text-lg font-bold text-gray-900">Pembayaran Daftar Ulang</h3>
                                <p class="text-xs text-gray-500">Invoice: <span class="font-mono font-bold text-gray-700">{{ $pembayaran ? $pembayaran->order_id : '-' }}</span></p>
                            </div>

                            <div class="flex items-center gap-3">
                                @if($pembayaran && ($pembayaran->transaction_status === 'settlement' || $pembayaran->transaction_status === 'success'))
                                    <span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold uppercase tracking-wider flex items-center gap-1.5 shadow-sm border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        LUNAS (SETTLEMENT)
                                    </span>
                                    
                                    <a href="{{ route('admin.pembayaran.print_kwitansi', $casis->id) }}" target="_blank" 
                                       class="px-4 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-full text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Cetak Kwitansi
                                    </a>
                                @elseif($pembayaran && $pembayaran->transaction_status === 'expire')
                                    <span class="px-4 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs font-extrabold uppercase tracking-wider">
                                        EXPIRED
                                    </span>
                                @elseif($pembayaran && $pembayaran->transaction_status === 'failed')
                                    <span class="px-4 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-extrabold uppercase tracking-wider">
                                        GAGAL
                                    </span>
                                @else
                                    <span class="px-4 py-1.5 bg-amber-100 text-amber-800 rounded-full text-xs font-extrabold uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                        MENUNGGU PEMBAYARAN
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Nominal Tagihan</span>
                                <span class="text-2xl font-black text-gray-900 font-mono">
                                    Rp {{ number_format($pembayaran ? $pembayaran->nominal : 1500000, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Tipe / Metode Terakhir</span>
                                <span class="text-sm font-bold text-gray-800 block capitalize">
                                    @if($pembayaran)
                                        {{ $pembayaran->tipe_pembayaran === 'offline' ? 'Manual (Kasir ' . ($pembayaran->payment_type ?: 'Offline') . ')' : 'Online (' . ($pembayaran->payment_type ? str_replace('_', ' ', strtoupper($pembayaran->payment_type)) : 'Payment Gateway') . ')' }}
                                    @else
                                        -
                                    @endif
                                </span>
                                @if($pembayaran && $pembayaran->admin)
                                    <span class="text-[10px] text-gray-500 block mt-0.5">Petugas: {{ $pembayaran->admin->name }}</span>
                                @endif
                            </div>

                            <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Jatuh Tempo</span>
                                <span class="text-sm font-bold text-gray-800 block">
                                    {{ $pembayaran && $pembayaran->tgl_jatuh_tempo ? \Carbon\Carbon::parse($pembayaran->tgl_jatuh_tempo)->translatedFormat('d F Y') : 'Sesuai Jadwal' }}
                                </span>
                                @if($pembayaran && $pembayaran->settlement_time)
                                    <span class="text-[10px] text-emerald-600 font-bold block mt-0.5">Lunas: {{ \Carbon\Carbon::parse($pembayaran->settlement_time)->translatedFormat('d M Y H:i') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($pembayaran && $pembayaran->catatan_admin)
                        <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 text-xs text-blue-900">
                            <span class="font-bold block mb-1 text-[10px] uppercase tracking-wider text-blue-600">Catatan Petugas / Kasir:</span>
                            <p class="italic">{{ $pembayaran->catatan_admin }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Riwayat Transaksi Siswa Table -->
                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100">
                        <h4 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Riwayat Lengkap Pembayaran Siswa
                        </h4>

                        <div class="overflow-x-auto rounded-2xl border border-gray-100">
                            <table class="w-full text-left text-xs whitespace-nowrap">
                                <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                                    <tr>
                                        <th class="px-4 py-3">Order ID / Invoice</th>
                                        <th class="px-4 py-3">Tipe & Metode</th>
                                        <th class="px-4 py-3">Nominal</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Diproses Oleh</th>
                                        <th class="px-4 py-3">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @forelse($casis->pembayaran as $history)
                                    <tr class="hover:bg-gray-50/80 transition">
                                        <td class="px-4 py-3 font-mono font-bold text-gray-900">{{ $history->order_id }}</td>
                                        <td class="px-4 py-3 capitalize">
                                            @if($history->tipe_pembayaran === 'offline')
                                                <span class="px-2 py-0.5 bg-orange-50 text-orange-700 border border-orange-200 rounded text-[10px] font-bold">Manual / {{ $history->payment_type ?: 'Kasir' }}</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[10px] font-bold">Payment{{ str_replace('_', ' ', $history->payment_type) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-mono font-bold text-gray-900">Rp {{ number_format($history->nominal, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            @if($history->transaction_status === 'settlement' || $history->transaction_status === 'success')
                                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-md font-extrabold text-[10px] uppercase">LUNAS</span>
                                            @elseif($history->transaction_status === 'pending')
                                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-md font-extrabold text-[10px] uppercase">PENDING</span>
                                            @elseif($history->transaction_status === 'expire')
                                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md font-extrabold text-[10px] uppercase">EXPIRED</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-md font-extrabold text-[10px] uppercase">GAGAL</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 font-medium">
                                            {{ $history->admin ? $history->admin->name : ($history->tipe_pembayaran === 'online' ? 'Siswa (Online)' : 'Kasir Sekolah') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 font-mono text-[11px]">
                                            {{ $history->created_at ? $history->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 font-medium">
                                            Belum ada catatan riwayat transaksi pembayaran.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- MODAL PROSES PEMBAYARAN ADMIN -->
        <div x-show="openPaymentModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" @click="openPaymentModal = false"></div>

                <div class="relative inline-block w-full max-w-lg p-6 md:p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl sm:my-8 z-10">
                    
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Proses Pembayaran Siswa</h3>
                            <p class="text-xs text-gray-500">{{ $casis->nama_lengkap }} ({{ $casis->no_pendaftaran }})</p>
                        </div>
                        <button type="button" @click="openPaymentModal = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Modal Navigation Tabs -->
                    <div class="flex border-b border-gray-100 mt-4 mb-6">
                        <button type="button" @click="paymentTab = 'offline'" 
                            :class="paymentTab === 'offline' ? 'border-b-2 border-blue-600 text-blue-600 font-black' : 'text-gray-400 font-bold hover:text-gray-600'"
                            class="flex-1 py-3 text-xs uppercase tracking-wider text-center transition">
                            Manual / Offline (Kasir)
                        </button>
                        <button type="button" @click="paymentTab = 'online'" 
                            :class="paymentTab === 'online' ? 'border-b-2 border-blue-600 text-blue-600 font-black' : 'text-gray-400 font-bold hover:text-gray-600'"
                            class="flex-1 py-3 text-xs uppercase tracking-wider text-center transition">
                            Online (Payment Gateway)
                        </button>
                    </div>

                    <!-- TAB 1: MANUAL / OFFLINE FORM -->
                    <div x-show="paymentTab === 'offline'">
                        <form action="{{ route('admin.pembayaran.process', $casis->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="payment_mode" value="offline">

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 px-1">Metode Pembayaran Kasir</label>
                                <select name="payment_type" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="Tunai">Tunai / Cash di Sekolah</option>
                                    <option value="Transfer Bank Manual">Transfer Bank Manual</option>
                                    <option value="QRIS Manual">QRIS Kasir Manual</option>
                                    <option value="Debit Card">Debit / EDC Sekolah</option>
                                    <option value="Lainnya">Metode Lainnya</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 px-1">Nominal Pembayaran (Rp)</label>
                                    <input type="number" name="nominal" value="{{ (int)($pembayaran ? $pembayaran->nominal : 1500000) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold font-mono text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 px-1">Tanggal Pembayaran</label>
                                    <input type="datetime-local" name="paid_at" value="{{ now()->format('Y-m-d\TH:i') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 px-1">Nomor Referensi / Bukti Transfer (Opsional)</label>
                                <input type="text" name="nomor_referensi" placeholder="Contoh: REF-BCA-123456" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 px-1">Catatan Kasir / Keterangan</label>
                                <textarea name="catatan_admin" rows="2" placeholder="Catatan opsional..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-2.5 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">Pembayaran Kasir Sekolah (Lunas)</textarea>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                <button type="button" @click="openPaymentModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black tracking-wider shadow-lg shadow-blue-500/20 transition active:scale-95">
                                    Simpan Pembayaran Kasir
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: ONLINE / MIDTRANS FORM -->
                    <div x-show="paymentTab === 'online'">
                        <form action="{{ route('admin.pembayaran.process', $casis->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="payment_mode" value="online">

                            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 text-xs text-blue-900 space-y-2">
                                <p class="font-bold">Opsi Pembayaran Online Siswa:</p>
                                <p class="text-blue-700">Siswa dapat membayar secara mandiri melalui portal siswa menggunakan Payment Gateway (QRIS/VA/E-Wallet). Anda juga dapat mengirimkan tautan rincian tagihan langsung ke WhatsApp siswa.</p>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 px-1">Nominal Tagihan</label>
                                <input type="number" name="nominal" value="{{ (int)($pembayaran ? $pembayaran->nominal : 1500000) }}" readonly class="w-full bg-gray-100 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold font-mono text-gray-700 cursor-not-allowed">
                            </div>

                            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-200 text-xs text-gray-600">
                                <div class="flex items-center justify-between">
                                    <span>Nomor Order:</span>
                                    <span class="font-mono font-bold text-gray-900">{{ $pembayaran ? $pembayaran->order_id : '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span>Status Gateway:</span>
                                    <span class="font-bold text-blue-600 uppercase">{{ $pembayaran ? $pembayaran->transaction_status : 'Pending' }}</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                                <button type="submit" name="send_wa" value="1" class="w-full sm:w-auto px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl text-xs font-black tracking-wider shadow-md shadow-green-500/20 transition flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.03A10.02 10.02 0 002.03 12c0 1.76.46 3.42 1.28 4.88L2 21.99l5.25-1.37a9.98 9.98 0 004.76 1.18c5.52 0 10.02-4.5 10.02-10.02S17.53 2.03 12.01 2.03zm5.42 14.5c-.24.67-1.38 1.25-1.93 1.34-.52.09-1.21.15-3.52-.8-2.79-1.16-4.6-4.04-4.74-4.23-.14-.19-1.13-1.51-1.13-2.88 0-1.38.72-2.06 1-2.36.27-.29.59-.36.78-.36.2 0 .39 0 .56.01.19.01.44-.07.67.5.24.58.84 2.04.91 2.18.07.14.12.3.03.49-.09.19-.14.3-.29.47-.14.16-.31.36-.43.49-.14.14-.28.29-.12.56.16.27.7 1.16 1.51 1.88.75.66 1.62.87 1.84.97.23.09.36.08.49-.07.13-.15.58-.67.73-.9.15-.24.3-.2.52-.12.22.08 1.39.66 1.63.78.24.12.4.18.45.28.06.1.06.57-.18 1.24z"/></svg>
                                    Kirim Tagihan
                                </button>
                                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black tracking-wider transition">
                                    Proses Pembayaran Online
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

    @if(session('open_snap_token'))
        @php
            $isProduction = config('services.midtrans.is_production');
            $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
        @endphp
        <script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                snap.pay('{{ session('open_snap_token') }}', {
                    onSuccess: function(result) { window.location.reload(); },
                    onPending: function(result) { window.location.reload(); },
                    onError: function(result) { window.location.reload(); },
                    onClose: function() { window.location.reload(); }
                });
            });
        </script>
    @endif
</x-app-layout>