<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Manajemen Pengguna</h1>
                    <p class="text-gray-500 text-sm mt-1">Kelola data administrator dan petugas verifikasi sistem.</p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah User Baru
                </a>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="p-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Daftar Pengguna</h3>
                        <p class="text-sm text-gray-500 mt-1">Total terdapat {{ $users->total() }} pengguna terdaftar.
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[10px] text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-4 font-black w-16">No</th>
                                <th class="px-6 py-4 font-black">User</th>
                                <th class="px-6 py-4 font-black">Email</th>
                                <th class="px-6 py-4 font-black">Role</th>
                                <th class="px-8 py-4 font-black text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($users as $index => $user)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold text-gray-400">{{ $users->firstItem() + $index
                                        }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 font-bold group-hover:bg-green-100 group-hover:text-green-600 transition-colors">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-gray-900 uppercase">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="text-xs font-medium text-gray-600">{{ $user->email }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    @if($user->role == 'admin')
                                    <span
                                        class="bg-purple-50 text-purple-600 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl border border-purple-100">
                                        Administrator
                                    </span>
                                    @else
                                    <span
                                        class="bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl border border-blue-100">
                                        Petugas
                                    </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="bg-amber-50 hover:bg-amber-400 hover:text-white text-amber-600 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl border border-amber-100 transition-all">
                                            Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl border border-red-100 transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-8 bg-gray-50/30 border-t border-gray-50">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>