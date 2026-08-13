<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic capitalize">Daftar Pembayaran</h1>
                    <p class="text-gray-500 text-sm mt-1">Monitoring pembayaran daftar ulang siswa terverifikasi.</p>
                </div>

                <!-- Export Button -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.pembayaran.export', request()->all()) }}" class="bg-emerald-500 uppercase hover:bg-emerald-600 text-white text-[10px] font-black capitalize tracking-widest px-6 py-4 rounded-2xl transition-all shadow-sm hover:shadow-lg shadow-emerald-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
                <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="p-8">
                    <div class="grid grid-cols-2 md:grid-cols-5 lg:grid-cols-5 gap-4 mb-6">
                        
                        <!-- Search -->
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, nisn, order id..."
                                class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20 placeholder:text-slate-400 placeholder:font-medium">
                        </div>

                        <!-- Status Transaksi -->
                        <div>
                            <select name="status_transaksi" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Semua Status Transaksi</option>
                                <option value="pending" {{ request('status_transaksi') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="settlement" {{ request('status_transaksi') == 'settlement' ? 'selected' : '' }}>Berhasil/Lunas (Settlement)</option>
                                <option value="expire" {{ request('status_transaksi') == 'expire' ? 'selected' : '' }}>Expired</option>
                                <option value="cancel" {{ request('status_transaksi') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                                <option value="failed" {{ request('status_transaksi') == 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>

                        <!-- Jurusan -->
                        <div>
                            <select name="jurusan_id" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}" {{ request('jurusan_id') == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->singkatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Periode Pendaftaran -->
                        <div>
                            <select name="spmb_period_id" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 px-5 py-4 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Semua Periode</option>
                                @foreach($tahunAjarans as $ta)
                                    <optgroup label="{{ $ta->nama }}">
                                        @foreach($ta->spmbPeriods as $period)
                                            <option value="{{ $period->id }}" {{ ($selectedSpmbPeriodId == $period->id || request('spmb_period_id') == $period->id) ? 'selected' : '' }}>
                                                Gelombang {{ $period->gelombang }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                        @if(request()->anyFilled(['search', 'status_daftar_ulang', 'tipe_pembayaran', 'status_transaksi', 'jurusan_id', 'spmb_period_id']))
                        <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-[10px] font-black capitalize tracking-widest px-6 py-4 rounded-2xl transition-all flex items-center">
                            Reset
                        </a>
                        @endif
                    </div>

                    </div>


                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[11px] text-gray-400 capitalize tracking-[0.2em] bg-gray-50/50 py-4 px-6 mb-5">
                            <tr>
                                <th class="px-6 py-5 font-black w-16">No</th>
                                <th class="px-6 py-5 font-black">Siswa</th>
                                <th class="px-6 py-5 font-black text-center">Status Daftar Ulang</th>
                                <th class="px-6 py-5 font-black">Nomor Pembayaran</th>
                                <th class="px-6 py-5 font-black text-right">Nominal</th>
                                <th class="px-6 py-5 font-black text-center">Type Pembayaran</th>
                                <th class="px-6 py-5 font-black text-center">Status Transaksi</th>
                                <th class="px-8 py-5 font-black text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 mb-5">
                            @forelse($casis as $index => $item)
                            @php
                                $pembayaran = $item->pembayaranTerakhir;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-all group border-b border-slate-500">
                                <td class="px-6 py-6">
                                    <span class="text-xs font-bold text-gray-400">{{ $casis->firstItem() + $index }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 capitalize tracking-tight group-hover:text-blue-600 transition-colors">{{ $item->nama_lengkap }}</span>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[10px] text-gray-400 font-mono tracking-wider">NISN: {{ $item->nisn }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[10px] font-bold text-blue-500">{{ $item->no_pendaftaran }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center align-middle items-center justify-center">
                                    @if($item->status_daftar_ulang == 'Sudah')
                                    <div class="flex items-center justify-center gap-2 text-green-600 bg-green-50 px-3 py-2 rounded-xl border border-green-100 w-fit mx-auto mb-2">
                                        <span class="text-[10px] font-black capitalize tracking-widest">Sudah</span>
                                    </div>
                                    @else
                                    <div class="flex items-center justify-center gap-2 text-gray-500 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 w-fit mx-auto mb-2">
                                        <span class="text-[10px] font-black capitalize tracking-widest">Belum</span>
                                    </div>
                                    @if($item->no_hp_siswa)
                                    <form action="{{ route('admin.pembayaran.reminder', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center justify-center gap-1 text-white bg-green-500 hover:bg-green-600 px-3 py-2 rounded-xl transition w-fit mx-auto shadow-sm" onclick="return confirm('Kirim pengingat WhatsApp ke {{ $item->nama_lengkap }}?')">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            <span class="text-[9px] font-black capitalize tracking-widest">Kirim WA</span>
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                </td>
                                <td class="px-6 py-6">
                                    <span class="font-mono text-xs font-bold text-gray-700">{{ $pembayaran ? $pembayaran->order_id : '-' }}</span>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <span class="font-mono text-xs font-bold text-gray-900">{{ $pembayaran ? 'Rp ' . number_format($pembayaran->nominal, 0, ',', '.') : '-' }}</span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    @if($pembayaran)
                                        @if($pembayaran->payment_type)
                                            <span class="text-[10px] font-black capitalize tracking-widest text-blue-600 bg-blue-50 px-2 py-1 rounded-md">{{ str_replace('_', ' ', $pembayaran->payment_type) }}</span>
                                        @elseif($pembayaran->tipe_pembayaran == 'offline')
                                            <span class="text-[10px] font-black capitalize tracking-widest text-orange-600 bg-orange-50 px-2 py-1 rounded-md">Offline</span>
                                        @else
                                            <span class="text-[10px] font-black capitalize tracking-widest text-blue-600 bg-blue-50 px-2 py-1 rounded-md">Online</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-6 text-center">
                                    @if($pembayaran)
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
                                    @else
                                        <span class="text-[10px] font-black capitalize tracking-widest text-gray-400 bg-gray-50 px-2 py-1 rounded-md">Belum Ada</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.pembayaran.show', $item->id) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all group-hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-sm font-bold capitalize tracking-widest">Tidak ada data pembayaran</p>
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
</x-app-layout>
