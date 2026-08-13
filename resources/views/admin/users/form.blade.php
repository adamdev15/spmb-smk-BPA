<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">

            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center text-xs font-black uppercase tracking-widest text-gray-400 hover:text-green-600 transition-colors gap-2 mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">
                    {{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    {{ isset($user) ? 'Perbarui data akun pengguna yang sudah ada.' : 'Buat akun administrator atau
                    petugas verifikasi baru.' }}
                </p>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
                    method="POST" class="p-10">
                    @csrf
                    @if(isset($user))
                    @method('PUT')
                    @endif

                    <div class="space-y-8">
                        <!-- Group: Informasi Dasar -->
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                                    class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-500/20 transition-all placeholder:text-gray-300"
                                    placeholder="Contoh: Ahmad Sulaiman">
                                @error('name') <p class="mt-2 text-[10px] font-bold text-red-500 ml-1 uppercase">{{
                                    $message }}</p> @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-1">Alamat
                                    Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                                    class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-500/20 transition-all placeholder:text-gray-300"
                                    placeholder="email@instansi.com">
                                @error('email') <p class="mt-2 text-[10px] font-bold text-red-500 ml-1 uppercase">{{
                                    $message }}</p> @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-1">Hak
                                    Akses (Role)</label>
                                <select name="role" required
                                    class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-500/20 transition-all">
                                    <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : ''
                                        }}>Administrator (Akses Penuh)</option>
                                    <option value="petugas" {{ old('role', $user->role ?? '') == 'petugas' ? 'selected'
                                        : '' }}>Petugas Verifikasi (Cek Berkas)</option>
                                </select>
                                @error('role') <p class="mt-2 text-[10px] font-bold text-red-500 ml-1 uppercase">{{
                                    $message }}</p> @enderror
                            </div>
                        </div>

                        <hr class="border-gray-50">

                        <!-- Group: Keamanan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-1">
                                    {{ isset($user) ? 'Ganti Password' : 'Password' }}
                                </label>
                                <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                                    class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-500/20 transition-all placeholder:text-gray-300"
                                    placeholder="••••••••">
                                @if(isset($user))
                                <p class="mt-2 text-[9px] font-bold text-gray-400 ml-1 uppercase italic">Kosongkan jika
                                    tidak diubah</p>
                                @endif
                                @error('password') <p class="mt-2 text-[10px] font-bold text-red-500 ml-1 uppercase">{{
                                    $message }}</p> @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-1">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
                                    class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-500/20 transition-all placeholder:text-gray-300"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('admin.users.index') }}"
                                class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors px-6">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white text-[10px] font-black uppercase tracking-widest px-10 py-4 rounded-2xl shadow-xl shadow-green-200 transition-all active:scale-95">
                                {{ isset($user) ? 'Simpan Perubahan' : 'Buat Akun' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>