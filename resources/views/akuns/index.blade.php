<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form Tambah Akun -->
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm h-fit">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#36656B]">Tambah Akun</h2>
                    <p class="text-xs text-gray-400">Buat akun pengelola atau petugas</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-[#75B06F]/20 text-[#36656B] text-xs font-semibold px-4 py-3 rounded-xl border border-[#75B06F]/30">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-50 text-red-700 text-xs font-semibold px-4 py-3 rounded-xl border border-red-200">
                    @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                </div>
            @endif

            <form action="{{ route('akuns.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="nama" value="Nama Lengkap" />
                    <x-text-input id="nama" name="nama" type="text" value="{{ old('nama') }}" required placeholder="Nama lengkap..." />
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="username" value="Username" />
                    <x-text-input id="username" name="username" type="text" value="{{ old('username') }}" required placeholder="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
                </div>
                
                <div>
                    <x-input-label for="role" value="Hak Akses" />
                    <select id="role" name="role" required class="w-full px-4 py-2.5 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150">
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="pengelola" {{ old('role') == 'pengelola' ? 'selected' : '' }}>Pengelola</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" name="password" type="password" required placeholder="Min 8 karakter" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" required />
                </div>
                <button type="submit" class="w-full bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-all duration-150 shadow-sm">
                    Buat Akun
                </button>
            </form>
        </div>

        <!-- Daftar Akun -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#75B06F]/20 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#36656B]">Manajemen Akun</h2>
                    <p class="text-xs text-gray-400">Total: {{ $akuns->count() }} akun terdaftar</p>
                </div>
            </div>

            <!-- Tabel Desktop -->
            <div class="hidden md:block overflow-x-auto rounded-xl border border-[#DAD887]/30">
                <table class="bento-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($akuns as $akun)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-[#75B06F]/20 rounded-lg flex items-center justify-center text-[#36656B] text-sm font-bold shrink-0">
                                            {{ strtoupper(substr($akun->nama, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $akun->nama }}</span>
                                    </div>
                                </td>
                                <td class="font-mono text-xs text-gray-500">{{ $akun->username }}</td>
                                <td class="text-center">
                                    @if($akun->role === 'pengelola')
                                        <span class="inline-block bg-[#DAD887] text-[#36656B] text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider">Pengelola</span>
                                    @else
                                        <span class="inline-block bg-[#75B06F]/20 text-[#36656B] text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider">Petugas</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('akuns.edit', $akun) }}"
                                           class="inline-flex items-center gap-1 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                            Edit
                                        </a>
                                        @if($akun->id !== Auth::id())
                                            <form action="{{ route('akuns.destroy', $akun) }}" method="POST"
                                                  onsubmit="return confirm('Hapus akun {{ $akun->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-300 px-3 py-1.5">(Anda)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-400 text-sm">Belum ada akun terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Card List Mobile -->
            <div class="md:hidden space-y-3">
                @forelse($akuns as $akun)
                    <div class="bg-[#F0F8A4]/10 border border-[#DAD887]/40 rounded-xl p-4 shadow-sm flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#75B06F]/20 rounded-xl flex items-center justify-center text-[#36656B] text-base font-bold shrink-0">
                                {{ strtoupper(substr($akun->nama, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <span class="font-semibold text-gray-900 text-base block">{{ $akun->nama }}</span>
                                <span class="text-xs font-mono text-gray-400 block mt-0.5">{{ $akun->username }}</span>
                            </div>
                            @if($akun->role === 'pengelola')
                                <span class="inline-block bg-[#DAD887] text-[#36656B] text-[10px] font-bold px-2 py-1 rounded-lg uppercase">Pengelola</span>
                            @else
                                <span class="inline-block bg-[#75B06F]/20 text-[#36656B] text-[10px] font-bold px-2 py-1 rounded-lg uppercase">Petugas</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-[#DAD887]/30">
                            <a href="{{ route('akuns.edit', $akun) }}"
                               class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold py-2.5 rounded-lg transition">
                                Edit
                            </a>
                            @if($akun->id !== Auth::id())
                                <form action="{{ route('akuns.destroy', $akun) }}" method="POST"
                                      onsubmit="return confirm('Hapus akun {{ $akun->nama }}?')" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full inline-flex justify-center items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold py-2.5 rounded-lg transition">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <div class="flex-1 text-center text-xs text-gray-400 py-2.5 bg-gray-50 border border-gray-100 rounded-lg">
                                    (Akun Anda)
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed rounded-xl">
                        Belum ada akun terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>