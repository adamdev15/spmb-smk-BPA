<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">
            {{ __('Pengaturan Sistem') }}
        </h2>
        <p class="text-gray-500 text-sm mt-1">Pengaturan sistem untuk menampilkan informasi pada halaman landing dan sistem.</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-6">

            @if(session('success'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Landing Page Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupedSettings['Landing Page'] as $setting)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting->name }}
                                    @if(in_array($setting->key, ['logo', 'nama_sekolah', 'tagline_sekolah', 'landing_hero', 'deskripsi_hero', 'tahun_ajaran']))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                {{-- FILE INPUT --}}
                                @if($setting->type == 'file')
                                    <div class="space-y-3">
                                        @if($setting->value)
                                            <div class="mb-3 flex flex-wrap gap-2">
                                                @if(str_ends_with(strtolower($setting->value), '.pdf'))
                                                    <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-900">File PDF</p>
                                                            <a href="{{ asset('storage/' . $setting->value) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat File</a>
                                                        </div>
                                                    </div>
                                                @elseif($setting->key === 'landing_hero' && is_array(json_decode($setting->value, true)))
                                                    @foreach(json_decode($setting->value, true) as $heroImg)
                                                        <img src="{{ asset('storage/' . $heroImg) }}" 
                                                            alt="{{ $setting->name }}" 
                                                            class="w-24 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                                    @endforeach
                                                @else
                                                    <img src="{{ asset('storage/' . $setting->value) }}" 
                                                        alt="{{ $setting->name }}" 
                                                        class="w-24 h-auto rounded-lg border border-gray-200 shadow-sm">
                                                @endif
                                            </div>
                                        @endif

                                        <input type="file" 
                                            name="{{ $setting->key }}{{ $setting->key === 'landing_hero' ? '[]' : '' }}" 
                                            {{ $setting->key === 'landing_hero' ? 'multiple' : '' }}
                                            accept="{{ $setting->key === 'brosur' ? 'application/pdf,image/png,image/jpeg,image/jpg' : 'image/png,image/jpeg,image/jpg' }}"
                                            class="block w-full text-sm text-gray-500
                                                    file:mr-4 file:py-2 file:px-4 file:rounded-md
                                                    file:border-0 file:text-sm file:font-semibold
                                                    file:bg-blue-50 file:text-blue-700
                                                    hover:file:bg-blue-100">

                                        <p class="text-xs text-gray-500">
                                            @if($setting->key === 'brosur')
                                                Format: PDF, PNG, JPG, JPEG (Max 5MB)
                                            @else
                                                Format: PNG, JPG, JPEG (Max 2MB)
                                            @endif
                                        </p>
                                    </div>

                                {{-- TEXTAREA --}}
                                @elseif($setting->type == 'longtext')
                                    <textarea name="{{ $setting->key }}" rows="5"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $setting->value }}</textarea>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Mendukung HTML tags
                                    </p>

                                @elseif($setting->type == 'boolean' || $setting->key == 'fonnte_status')
                                    <div class="mt-1" x-data="{ checked: {{ $setting->value == '1' || $setting->value == 'true' ? 'true' : 'false' }} }">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="{{ $setting->key }}" value="0">
                                            <input type="checkbox" name="{{ $setting->key }}" value="1" x-model="checked" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-900" x-text="checked ? 'Aktif' : 'Tidak Aktif'"></span>
                                        </label>
                                    </div>

                                {{-- DATE --}}
                                @elseif($setting->type == 'date')
                                    <input type="date" 
                                        name="{{ $setting->key }}" 
                                        value="{{ $setting->value }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                {{-- TEXT --}}
                                @else
                                    <input type="text" 
                                        name="{{ $setting->key }}" 
                                        value="{{ $setting->value }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                    {{-- Keterangan khusus untuk tagline --}}
                                    @if($setting->key === 'tagline_sekolah')
                                        <p class="text-xs text-gray-500 mt-2">
                                            Gunakan tanda <span class="font-semibold text-blue-600">|</span> untuk bagian tengah (warna biru). <br>
                                            Contoh: <br>
                                            <span class="italic text-gray-600">
                                                Membangun Generasi|Cerdas & Berakhlak
                                            </span>
                                        </p>
                                    @endif

                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Jadwal Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Jadwal Pendaftaran</h3>
                        <p class="text-sm text-gray-500 mt-1">Atur jadwal kegiatan pendaftaran</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $jadwalSettings = $groupedSettings['Jadwal']->sortBy(function($item) {
                                    $order = [
                                        'jadwal_pendaftaran_mulai' => 1,
                                        'jadwal_pendaftaran_selesai' => 2,
                                        'jadwal_seleksi' => 3,
                                        'jadwal_seleksi_selesai' => 4,
                                        'jadwal_pengumuman' => 5,
                                        'jadwal_daftar_ulang' => 6,
                                        'jadwal_daftar_ulang_selesai' => 7,
                                    ];
                                    return $order[$item->key] ?? 99;
                                });
                            @endphp
                            @foreach($jadwalSettings as $setting)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting->name }}
                                </label>
                                <input type="date" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Kontak Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Kontak Panitia</h3>
                        <p class="text-sm text-gray-500 mt-1">Data kontak yang akan ditampilkan di halaman landing</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @for($i = 1; $i <= 2; $i++)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Kontak {{ $i }}</h4>
                                <div class="space-y-4">
                                    @php
                                    $namaSetting = $groupedSettings['Kontak']->firstWhere('key', 'kontak_nama_' . $i);
                                    $nomorSetting = $groupedSettings['Kontak']->firstWhere('key', 'kontak_nomor_' . $i);
                                    @endphp
                                    
                                    @if($namaSetting)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                        <input type="text" name="kontak_nama_{{ $i }}" value="{{ $namaSetting->value }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    @endif
                                    
                                    @if($nomorSetting)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                                        <input type="text" name="kontak_nomor_{{ $i }}" value="{{ $nomorSetting->value }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="08xxxxxxxxxx">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Alur Pendaftaran Settings -->
                @if(isset($groupedSettings['Alur Pendaftaran']) && $groupedSettings['Alur Pendaftaran']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Alur Pendaftaran</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan alur pendaftaran yang ditampilkan di halaman landing</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupedSettings['Alur Pendaftaran'] as $setting)
                                @if(in_array($setting->key, ['alur_spmb_gambar', 'alur_spmb_konten']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->name }}</label>
                                    @if($setting->type == 'longtext')
                                        <textarea name="{{ $setting->key }}" rows="6" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $setting->value }}</textarea>
                                    @elseif($setting->type == 'file')
                                        <div class="mb-2" id="preview-container-{{ $setting->key }}" style="{{ $setting->value ? '' : 'display: none;' }}">
                                            <img id="preview-img-{{ $setting->key }}" src="{{ $setting->value ? asset('storage/' . $setting->value) : '#' }}" alt="Preview" class="h-32 object-contain rounded-md border border-gray-200 bg-white shadow-sm">
                                        </div>
                                        <input type="file" 
                                            name="{{ $setting->key }}" 
                                            id="file-{{ $setting->key }}"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            accept="image/*"
                                            onchange="previewImage(this, '{{ $setting->key }}')"
                                        >
                                    @else
                                        <input type="text" 
                                            name="{{ $setting->key }}" 
                                            value="{{ $setting->value }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Alur Daftar Ulang Settings -->
                @if(isset($groupedSettings['Alur Daftar Ulang']) && $groupedSettings['Alur Daftar Ulang']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Alur Daftar Ulang</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan alur daftar ulang yang ditampilkan di modal panduan daftar ulang</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @for($i = 1; $i <= 4; $i++)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Step {{ $i }}</h4>
                                <div class="space-y-4">
                                    @php
                                    $titleSetting = $groupedSettings['Alur Daftar Ulang']->firstWhere('key', 'alur_daftar_ulang_step' . $i . '_title');
                                    $descSetting = $groupedSettings['Alur Daftar Ulang']->firstWhere('key', 'alur_daftar_ulang_step' . $i . '_desc');
                                    @endphp
                                    
                                    @if($titleSetting)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                                        <input type="text" name="alur_daftar_ulang_step{{ $i }}_title" value="{{ $titleSetting->value }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    @endif
                                    
                                    @if($descSetting)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                        <input type="text" name="alur_daftar_ulang_step{{ $i }}_desc" value="{{ $descSetting->value }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
                @endif

                <!-- WhatsApp Settings -->
                @if(isset($groupedSettings['WhatsApp']) && $groupedSettings['WhatsApp']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaturan WhatsApp</h3>
                        <p class="text-sm text-gray-500 mt-1">Template pesan untuk notifikasi WhatsApp</p>
                    </div>
                    <div class="p-6">
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Test Koneksi WhatsApp</h4>
                            <p class="text-xs text-gray-600 mb-4">Pastikan Bablast API Token (Secret Token) sudah tersimpan (dan di-refresh) sebelum melakukan test.</p>
                            <button type="button" onclick="document.getElementById('modalTestWhatsapp').classList.remove('hidden')" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-xs font-bold transition shadow-sm">
                                Mulai Test Koneksi
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupedSettings['WhatsApp'] as $setting)
                            <div class="{{ in_array($setting->key, ['fonnte_token', 'fonnte_status']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting->name }}
                                </label>
                                @if($setting->type == 'longtext')
                                <textarea name="{{ $setting->key }}" rows="5"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $setting->value }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($setting->key == 'template_pesan_pendaftaran')
                                        Variabel: [NOMOR_DAFTAR], [NAMA], [JURUSAN], [PROGRAM_KEUNGGULAN], [PASSWORD], [LINK_WA_GROUP]
                                    @elseif($setting->key == 'wa_pesan_ingatkan' || $setting->key == 'wa_pesan_daftar_ulang')
                                        Variabel: [NAMA]
                                    @elseif($setting->key == 'wa_pesan_kelulusan')
                                        Variabel: [NAMA], [STATUS]
                                    @elseif($setting->key == 'wa_pesan_tagihan_daftar_ulang')
                                        Variabel: [NAMA], [NOMOR_DAFTAR], [JURUSAN], [PROGRAM_KEUNGGULAN], [NOMINAL], [JATUH_TEMPO], [LINK_DASHBOARD]
                                    @elseif($setting->key == 'wa_pesan_pembayaran_sukses')
                                        Variabel: [NAMA], [NOMOR_PEMBAYARAN], [NOMINAL], [METODE], [TANGGAL_BAYAR]
                                    @endif
                                </p>
                                @elseif($setting->type == 'boolean' || $setting->key == 'fonnte_status')
                                <div class="mt-1" x-data="{ checked: {{ $setting->value == '1' || $setting->value == 'true' ? 'true' : 'false' }} }">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="{{ $setting->key }}" value="0">
                                        <input type="checkbox" name="{{ $setting->key }}" value="1" x-model="checked" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-900" x-text="checked ? 'Aktif' : 'Tidak Aktif'"></span>
                                    </label>
                                </div>
                                @else
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Midtrans Settings -->
                @if(isset($groupedSettings['Midtrans']) && $groupedSettings['Midtrans']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50 flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Konfigurasi Midtrans</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Pengaturan Gateway Pembayaran untuk Daftar Ulang</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupedSettings['Midtrans'] as $setting)
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $setting->name }}
    </label>

    {{-- MIDTRANS PRODUCTION TOGGLE --}}
    @if(
        $setting->key == 'midtrans_production'
        || str_contains(strtolower($setting->name), 'production')
    )
        <div class="mt-1"
             x-data="{
                checked: {{ $setting->value == '1' || $setting->value == 'true' ? 'true' : 'false' }}
             }">

            <label class="relative inline-flex items-center cursor-pointer">
                <input
                    type="hidden"
                    name="{{ $setting->key }}"
                    value="0"
                >

                <input
                    type="checkbox"
                    name="{{ $setting->key }}"
                    value="1"
                    x-model="checked"
                    class="sr-only peer"
                >

                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>

                <span
                    class="ml-3 text-sm font-medium text-gray-900"
                    x-text="checked ? 'Production' : 'Sandbox'"
                ></span>
            </label>

        </div>

    {{-- BOOLEAN BIASA --}}
    @elseif($setting->type == 'boolean')

        <div class="mt-1"
             x-data="{
                checked: {{ $setting->value == '1' || $setting->value == 'true' ? 'true' : 'false' }}
             }">

            <label class="relative inline-flex items-center cursor-pointer">
                <input
                    type="hidden"
                    name="{{ $setting->key }}"
                    value="0"
                >

                <input
                    type="checkbox"
                    name="{{ $setting->key }}"
                    value="1"
                    x-model="checked"
                    class="sr-only peer"
                >

                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>

                <span
                    class="ml-3 text-sm font-medium text-gray-900"
                    x-text="checked ? 'Aktif' : 'Tidak Aktif'"
                ></span>
            </label>

        </div>

    {{-- LONGTEXT --}}
    @elseif($setting->type == 'longtext')

        <textarea
            name="{{ $setting->key }}"
            rows="5"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >{{ $setting->value }}</textarea>

    {{-- INPUT BIASA --}}
    @else

        <input
            type="text"
            name="{{ $setting->key }}"
            value="{{ $setting->value }}"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >

    @endif
</div>
@endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tanda Tangan & Surat Settings -->
                @if(isset($groupedSettings['Tanda Tangan & Surat']) && $groupedSettings['Tanda Tangan & Surat']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50 flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Tanda Tangan & Surat</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Pengaturan Tanda Tangan untuk Cetakan Bukti dan Kop Surat</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupedSettings['Tanda Tangan & Surat'] as $setting)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting->name }}
                                </label>

                                @if($setting->type == 'file')
                                    <div class="mb-2" id="preview-container-{{ $setting->key }}" style="{{ $setting->value ? '' : 'display: none;' }}">
                                        <img id="preview-img-{{ $setting->key }}" src="{{ $setting->value ? asset('storage/' . $setting->value) : '#' }}" alt="{{ $setting->name }}" class="h-20 object-contain rounded border border-gray-200 bg-white shadow-sm">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="file"
                                            name="{{ $setting->key }}"
                                            id="file-{{ $setting->key }}"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            accept="image/*"
                                            onchange="previewImage(this, '{{ $setting->key }}')"
                                        >
                                        @if(in_array($setting->key, ['pengumuman_ttd_kepsek', 'pengumuman_ttd_ketua', 'kwitansi_ttd_panitia']))
                                        <button type="button" onclick="openSignatureCanvas('{{ $setting->key }}', '{{ $setting->name }}')" class="shrink-0 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md text-xs font-bold transition flex items-center gap-1 border border-slate-300">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            Buat TTD
                                        </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Other Settings -->
                @if($groupedSettings['Lainnya']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaturan Lainnya</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupedSettings['Lainnya'] as $setting)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting->name }}
                                </label>

                                @if($setting->type == 'longtext')
                                <textarea name="{{ $setting->key }}" rows="5"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $setting->value }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Mendukung HTML tags</p>
                                @elseif($setting->type == 'boolean' || $setting->key == 'fonnte_status')
                                <div class="mt-1" x-data="{ checked: {{ $setting->value == '1' || $setting->value == 'true' ? 'true' : 'false' }} }">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="{{ $setting->key }}" value="0">
                                        <input type="checkbox" name="{{ $setting->key }}" value="1" x-model="checked" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-900" x-text="checked ? 'Aktif' : 'Tidak Aktif'"></span>
                                    </label>
                                </div>
                                @elseif($setting->type == 'date')
                                <input type="date" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @else
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex justify-end gap-4">
                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-2.5 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-lg">
                            Simpan Pengaturan
                        </button>
                    </div>
                <!-- End Form Settings -->
            </form>
        </div>
    </div>

    <!-- Modal Test Whatsapp -->
    <div id="modalTestWhatsapp" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="document.getElementById('modalTestWhatsapp').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.settings.test-whatsapp') }}" method="POST">
                    @csrf
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Test Koneksi WhatsApp
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Kirim pesan uji coba menggunakan template WABA yang sudah disetujui (APPROVED) di dashboard Bablast/Meta.</p>
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <p class="text-xs text-yellow-700"><strong>⚠ Catatan:</strong> Template <code>hello_world</code> hanya bisa dikirim dari nomor Test Publik Meta, <strong>bukan dari nomor WABA production</strong>. Gunakan template Anda sendiri yang sudah APPROVED.</p>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Nama Template WABA</label>
                                <input type="text" name="test_template" value="hello_world" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="spmb_pendaftaran_berhasil">
                                <p class="mt-1 text-xs text-gray-400">Isi dengan nama template yang sudah APPROVED di Bablast/Meta.</p>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp Tujuan</label>
                                <input type="text" name="test_nomor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="08xxxxxxxx">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Pesan Test
                        </button>
                        <button type="button" onclick="document.getElementById('modalTestWhatsapp').classList.add('hidden')" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Signature Canvas Modal -->
    <div id="modalSignature" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800" id="signatureModalTitle">Buat Tanda Tangan</h3>
                <button type="button" onclick="closeSignatureCanvas()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 bg-slate-100 flex-grow flex justify-center items-center">
                <canvas id="signatureCanvas" class="bg-white rounded-xl shadow-inner border border-slate-300 cursor-crosshair" width="400" height="200"></canvas>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-between items-center">
                <button type="button" onclick="clearSignature()" class="text-sm font-semibold text-red-500 hover:text-red-700">Bersihkan Canvas</button>
                <div class="flex gap-3">
                    <button type="button" onclick="closeSignatureCanvas()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900">Batal</button>
                    <button type="button" onclick="saveSignature()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">Gunakan TTD</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        let signaturePad = null;
        let currentSignatureTarget = null;

        function openSignatureCanvas(targetKey, title) {
            currentSignatureTarget = targetKey;
            document.getElementById('signatureModalTitle').innerText = 'TTD: ' + title;
            document.getElementById('modalSignature').classList.remove('hidden');
            
            if (!signaturePad) {
                const canvas = document.getElementById('signatureCanvas');
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });
            } else {
                signaturePad.clear();
            }
        }

        function closeSignatureCanvas() {
            document.getElementById('modalSignature').classList.add('hidden');
            currentSignatureTarget = null;
        }

        function clearSignature() {
            if (signaturePad) signaturePad.clear();
        }

        function previewImage(input, targetKey) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('preview-img-' + targetKey);
                    const container = document.getElementById('preview-container-' + targetKey);
                    if (img && container) {
                        img.src = e.target.result;
                        container.style.display = 'block';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function saveSignature() {
            if (signaturePad.isEmpty()) {
                alert("Harap buat tanda tangan terlebih dahulu.");
                return;
            }

            const dataURL = signaturePad.toDataURL('image/png');
            
            // Show Live Preview immediately
            const img = document.getElementById('preview-img-' + currentSignatureTarget);
            const container = document.getElementById('preview-container-' + currentSignatureTarget);
            if (img && container) {
                img.src = dataURL;
                container.style.display = 'block';
            }
            
            // Convert DataURL to File object
            fetch(dataURL)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], currentSignatureTarget + '_signature.png', { type: 'image/png' });
                    
                    // Attach file to the targeted input[type="file"] using DataTransfer
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    
                    const fileInput = document.getElementById('file-' + currentSignatureTarget);
                    if (fileInput) {
                        fileInput.files = dataTransfer.files;
                        // Show success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'TTD Diterapkan',
                            text: 'Tanda tangan berhasil dibuat. Jangan lupa klik Simpan Pengaturan untuk menyimpannya.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeSignatureCanvas();
                });
        }
    </script>
</x-app-layout>







