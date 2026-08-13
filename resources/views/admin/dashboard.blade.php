<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Welcome Header -->
            <div class="mb-8 p-8 bg-white rounded-3xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50 rounded-full opacity-50 blur-3xl"></div>
                <div class="relative flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-blue-500/20 font-heading overflow-hidden">
                        @if(!empty($settings['logo']))
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain p-1">
                        @else
                            <img src="{{ asset('images/logo_smk_bpa.png') }}" alt="Logo" class="w-full h-full object-contain p-1" onerror="this.outerHTML='{{ $settings['singkatan_sekolah'] ?? 'BPA' }}'">
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-heading">
                            Dashboard Admin SPMB {{ $settings['singkatan_sekolah'] ?? 'SMK BPA' }}
                        </h1>
                        <p class="text-slate-500 text-xs mt-1 flex items-center gap-2">
                            Selamat datang kembali, <span class="font-bold text-blue-600">{{ Auth::user()->name }}</span> • 
                            <span class="bg-slate-100 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest text-slate-600">{{ Auth::user()->role ?? 'Admin' }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 relative z-10">
                    <form id="filterForm" method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row items-center gap-2">
                        <select name="tahun_ajaran_id" class="text-xs font-bold rounded-xl border-slate-200 py-2 sm:py-2.5 pr-8 pl-3 bg-white text-slate-700 cursor-pointer shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" onchange="document.getElementById('spmb_period_id').value=''; this.form.submit()">
                            <option value="">Semua TA</option>
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ $selectedTahunAjaranId == $ta->id ? 'selected' : '' }}>TA {{ $ta->nama }}</option>
                            @endforeach
                        </select>

                        <select id="spmb_period_id" name="spmb_period_id" class="text-xs font-bold rounded-xl border-slate-200 py-2 sm:py-2.5 pr-8 pl-3 bg-white text-slate-700 cursor-pointer shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" onchange="this.form.submit()">
                            <option value="">Semua Gelombang</option>
                            @php
                                $activeTa = $tahunAjarans->firstWhere('id', $selectedTahunAjaranId);
                            @endphp
                            @if($activeTa)
                                @foreach($activeTa->spmbPeriods as $period)
                                    <option value="{{ $period->id }}" {{ $selectedSpmbPeriodId == $period->id ? 'selected' : '' }}>Gelombang {{ $period->gelombang }}</option>
                                @endforeach
                            @endif
                        </select>
                    </form>

                    <a href="{{ route('admin.casis.create') }}" class="hidden lg:block px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition transform hover:-translate-y-0.5 whitespace-nowrap">
                        + Tambah
                    </a>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Total Pendaftar -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/80 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Pendaftar</span>
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 font-heading">{{ number_format($stats['total'] ?? 0) }}</h3>
                    <div class="mt-2 text-xs text-slate-500 flex gap-3 font-semibold">
                        <span class="text-blue-600">Online: {{ $stats['online'] ?? 0 }}</span>
                        <span class="text-emerald-600">Offline: {{ $stats['offline'] ?? 0 }}</span>
                    </div>
                </div>

                <!-- Lulus Seleksi -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/80 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Lulus Seleksi</span>
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 font-heading">{{ number_format($stats['lulus'] ?? 0) }}</h3>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Siswa memenuhi kualifikasi seleksi</p>
                </div>

                <!-- Sudah Daftar Ulang -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/80 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Sudah Daftar Ulang</span>
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 font-heading">{{ number_format($stats['sudah_daftar_ulang'] ?? 0) }}</h3>
                    <p class="text-xs text-indigo-600 font-semibold mt-2">Mengurangi kuota aktif jurusan</p>
                </div>

                <!-- Total Biaya Terkumpul -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/80 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Pembayaran</span>
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 font-heading">Rp {{ number_format($stats['total_pembayaran'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Lunas via Midtrans & Kasir</p>
                </div>

            </div>

            <!-- Jurusan Quota Progress Cards -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900 font-heading">Status Kuota Per Program Keahlian</h3>
                    <a href="{{ route('admin.jurusans.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Kelola Kuota Jurusan →</a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($jurusans as $j)
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-extrabold tracking-wider uppercase font-heading">
                                {{ $j->kode }}
                            </span>
                            <span class="text-xs font-bold {{ $j->sisa_kuota > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                Sisa: {{ $j->sisa_kuota }} Kursi
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 truncate mb-1" title="{{ $j->nama }}">{{ $j->nama }}</h4>
                        <div class="flex justify-between text-xs text-slate-500 mb-2">
                            <span>Total Kuota: <strong>{{ $j->kuota }}</strong></span>
                            <span>Daftar Ulang: <strong class="text-blue-600">{{ $j->jumlah_daftar_ulang }}</strong></span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            @php
                                $percent = $j->kuota > 0 ? min(100, round(($j->jumlah_daftar_ulang / $j->kuota) * 100)) : 0;
                            @endphp
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8" x-data="dashboardCharts()">
                <!-- Chart 1: Pendaftar per Jurusan -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <h4 class="text-base font-bold text-slate-900 font-heading mb-1">Grafik Pendaftar per Jurusan</h4>
                    <p class="text-xs text-slate-500 mb-4">Jumlah calon siswa berdasarkan pilihan program keahlian</p>
                    <div class="h-64 relative">
                        <canvas id="jurusanChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Pendaftar 7 Hari Terakhir -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <h4 class="text-base font-bold text-slate-900 font-heading mb-1">Tren Pendaftaran 7 Hari Terakhir</h4>
                    <p class="text-xs text-slate-500 mb-4">Jumlah pendaftar harian ke sistem SPMB</p>
                    <div class="h-64 relative">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts Section 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8" x-data="dashboardChartsRow2()">
                <!-- Chart 3: Pembayaran Daftar Ulang -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <h4 class="text-base font-bold text-slate-900 font-heading mb-1">Pembayaran Daftar Ulang 7 Hari Terakhir</h4>
                    <p class="text-xs text-slate-500 mb-4">Jumlah transaksi daftar ulang per hari</p>
                    <div class="h-64 relative">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>

                <!-- Chart 4: Total Pendaftar (Pie) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-base font-bold text-slate-900 font-heading mb-1">Total Pendaftar</h4>
                            <p class="text-xs text-slate-500">Berdasarkan kategori terpilih</p>
                        </div>
                        <select x-model="pieType" @change="updatePieChart()" class="bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-700 px-3 py-1.5 focus:ring-2 focus:ring-blue-500/20 cursor-pointer">
                            <option value="gender">Jenis Kelamin</option>
                            <option value="jurusan">Jurusan Keahlian</option>
                        </select>
                    </div>
                    <div class="flex-1 relative flex justify-center items-center">
                        <div class="w-full h-56 relative">
                            <canvas id="totalPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Data Calon Peserta Terbaru -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Pendaftar Terbaru</h3>
                        <p class="text-xs text-slate-500">Daftar calon siswa yang baru mendaftar (Online & Offline)</p>
                    </div>
                    <a href="{{ route('admin.casis.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Lihat Semua Data Pendaftar →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">No. Pendaftaran</th>
                                <th class="px-6 py-4">Sumber</th>
                                <th class="px-6 py-4">Nama Lengkap / NISN</th>
                                <th class="px-6 py-4">Jurusan</th>
                                <th class="px-6 py-4">Status Kelulusan</th>
                                <th class="px-6 py-4">Daftar Ulang</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($casis as $c)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-blue-600">
                                    {{ $c->no_pendaftaran }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $c->sumber_pendaftaran == 'offline' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ strtoupper($c->sumber_pendaftaran ?? 'online') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900 block text-sm">{{ $c->nama_lengkap }}</span>
                                    <span class="text-slate-400">NISN: {{ $c->nisn }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $c->jurusan ? $c->jurusan->kode : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $c->status_kelulusan == 'Lulus' ? 'bg-emerald-100 text-emerald-700' : ($c->status_kelulusan == 'Tidak Lulus' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $c->status_kelulusan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $c->status_daftar_ulang == 'Sudah' ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $c->status_daftar_ulang == 'Sudah' ? 'SUDAH' : 'BELUM' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.casis.show', $c->id) }}" class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded-xl transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400 italic">Belum ada data pendaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $casis->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        function dashboardCharts() {
            return {
                init() {
                    this.initJurusanChart();
                    this.initTrendChart();
                },

                initJurusanChart() {
                    const ctx = document.getElementById('jurusanChart');
                    if (!ctx) return;

                    const labels = @json($jurusanChartData['labels'] ?? []);
                    const data = @json($jurusanChartData['data'] ?? []);

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: data,
                                backgroundColor: '#2491CA',
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                        }
                    });
                },

                initTrendChart() {
                    const ctx = document.getElementById('trendChart');
                    if (!ctx) return;

                    const last7DaysData = @json(array_values($last7Days ?? []));
                    const last7DaysLabels = @json(array_map(function($d) { return \Carbon\Carbon::parse($d)->format('d M'); }, array_keys($last7Days ?? [])));

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: last7DaysLabels,
                            datasets: [{
                                label: 'Pendaftar Baru',
                                data: last7DaysData,
                                borderColor: '#2491CA',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.3,
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                        }
                    });
                }
            }
        }

        function dashboardChartsRow2() {
            let pieChartInstance = null;
            let paymentChartInstance = null;

            return {
                pieType: 'gender',

                init() {
                    this.initPaymentChart();
                    this.initPieChart();
                },

                initPaymentChart() {
                    const ctx = document.getElementById('paymentChart');
                    if (!ctx) return;

                    const paymentDataRaw = @json($last7DaysPayments ?? []);
                    const formatDate = (dateStr) => {
                        const d = new Date(dateStr);
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                    };
                    const labels = Object.keys(paymentDataRaw).map(formatDate);
                    const data = Object.values(paymentDataRaw);

                    paymentChartInstance = new Chart(ctx, {
                        type: 'line',
                        plugins: typeof ChartDataLabels !== 'undefined' ? [ChartDataLabels] : [],
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Transaksi Pembayaran',
                                data: data,
                                borderColor: '#2491CA',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                fill: true,
                                tension: 0.3,
                                borderWidth: 3,
                                pointBackgroundColor: '#2491CA',
                                pointRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { 
                                legend: { display: false },
                                datalabels: {
                                    display: function(context) {
                                        return context.dataset.data[context.dataIndex] > 0;
                                    },
                                    align: 'top',
                                    anchor: 'end',
                                    color: '#1e293b',
                                    font: { weight: 'bold', size: 11 },
                                    formatter: function(value) {
                                        return value;
                                    }
                                }
                            },
                            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                            layout: { padding: { top: 20 } }
                        }
                    });
                },

                initPieChart() {
                    const ctx = document.getElementById('totalPieChart');
                    if (!ctx) return;

                    pieChartInstance = new Chart(ctx, {
                        type: 'pie',
                        plugins: typeof ChartDataLabels !== 'undefined' ? [ChartDataLabels] : [],
                        data: this.getPieData('gender'),
                        options: this.getPieOptions()
                    });
                },

                updatePieChart() {
                    if (pieChartInstance) {
                        pieChartInstance.destroy();
                        const ctx = document.getElementById('totalPieChart');
                        pieChartInstance = new Chart(ctx, {
                            type: 'pie',
                            plugins: typeof ChartDataLabels !== 'undefined' ? [ChartDataLabels] : [],
                            data: this.getPieData(this.pieType),
                            options: this.getPieOptions()
                        });
                    }
                },

                getPieData(type) {
                    let labels = [];
                    let data = [];
                    // Using primary blues palette for pie chart
                    const bgColors = ['#3162e9ff', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#3c72e8ff'];

                    if (type === 'gender') {
                        const genderData = @json($genderPieData ?? ['labels'=>[], 'data'=>[]]);
                        labels = genderData.labels;
                        data = genderData.data;
                    } else if (type === 'jurusan') {
                        const jurusanData = @json($jurusanChartData ?? ['labels'=>[], 'data'=>[]]);
                        labels = jurusanData.labels;
                        data = jurusanData.data;
                    }

                    return {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: bgColors.slice(0, data.length > 0 ? data.length : bgColors.length),
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    };
                },

                getPieOptions() {
                    return {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, font: {size: 10} } },
                            datalabels: {
                                color: '#ffffff',
                                font: { weight: 'bold', size: 10 },
                                formatter: (value, ctx) => {
                                    if (value === 0) return '';
                                    let sum = 0;
                                    let dataArr = ctx.chart.data.datasets[0].data;
                                    dataArr.forEach(data => { sum += Number(data); });
                                    let percentage = (value * 100 / sum).toFixed(0) + "%";
                                    let label = ctx.chart.data.labels[ctx.dataIndex];
                                    
                                    if(label === 'Laki-Laki') label = 'L';
                                    if(label === 'Perempuan') label = 'P';
                                    
                                    return label + ' ' + value + ' ' + percentage;
                                }
                            }
                        }
                    };
                }
            }
        }
    </script>
</x-app-layout>