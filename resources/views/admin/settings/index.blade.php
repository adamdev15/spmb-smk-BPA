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
                            @for($i = 1; $i <= 4; $i++)
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
                        <div class="space-y-6">
                            @foreach($groupedSettings['Alur Pendaftaran'] as $setting)
                                @if(in_array($setting->key, ['alur_spmb_gambar', 'alur_spmb_konten']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->name }}</label>
                                    @if($setting->type == 'longtext')
                                        <textarea name="{{ $setting->key }}" rows="6" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $setting->value }}</textarea>
                                    @elseif($setting->type == 'file')
                                        @if($setting->value)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $setting->value) }}" alt="Preview" class="h-32 object-contain rounded-md border border-gray-200">
                                            </div>
                                        @endif
                                        <input type="file" 
                                            name="{{ $setting->key }}" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                            <p class="text-xs text-gray-600 mb-4">Pastikan token Fonnte sudah tersimpan (dan di-refresh) sebelum melakukan test.</p>
                            <button type="button" onclick="document.getElementById('modalTestFonnte').classList.remove('hidden')" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-xs font-bold transition shadow-sm">
                                Mulai Test Koneksi
                            </button>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($groupedSettings['WhatsApp'] as $setting)
                            <div>
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
                                    @endif
                                </p>
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
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($groupedSettings['Midtrans'] as $setting)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting->name }}
                                </label>

                                @if($setting->type == 'longtext')
                                <textarea name="{{ $setting->key }}" rows="5"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $setting->value }}</textarea>
                                @elseif($setting->type == 'boolean')
                                <select name="{{ $setting->key }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="1" {{ $setting->value == '1' || $setting->value == 'true' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $setting->value == '0' || $setting->value == 'false' ? 'selected' : '' }}>Tidak</option>
                                </select>
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
                                @elseif($setting->type == 'boolean')
                                <select name="{{ $setting->key }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="1" {{ $setting->value == '1' || $setting->value == 'true' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $setting->value == '0' || $setting->value == 'false' ? 'selected' : '' }}>Tidak</option>
                                </select>
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

    <!-- Modal Test Fonnte -->
    <div id="modalTestFonnte" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="document.getElementById('modalTestFonnte').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.settings.test-fonnte') }}" method="POST">
                    @csrf
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Test Koneksi Fonnte
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Masukkan nomor WhatsApp aktif (awali dengan 08 atau 62) untuk mengirimkan pesan test dari sistem.</p>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp Tujuan</label>
                                <input type="text" name="test_nomor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="08xxxxxxxx">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Pesan Test
                        </button>
                        <button type="button" onclick="document.getElementById('modalTestFonnte').classList.add('hidden')" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
