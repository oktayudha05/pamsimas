<x-app-layout>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Bento Tile: Tambah Petugas -->
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm h-fit">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#36656B]">Tambah Petugas</h2>
                    <p class="text-xs text-gray-400">Buat akun petugas baru</p>
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

            <form action="{{ route('petugases.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="nama" value="Nama Lengkap" />
                    <x-text-input id="nama" name="nama" type="text" value="{{ old('nama') }}" required placeholder="Nama petugas..." />
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="username" value="Username" />
                    <x-text-input id="username" name="username" type="text" value="{{ old('username') }}" required placeholder="username_petugas" />
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
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
                    Buat Akun Petugas
                </button>
            </form>
        </div>

        <!-- Bento Tile: Daftar Petugas -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#75B06F]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#36656B]">Daftar Petugas</h2>
                    <p class="text-xs text-gray-400">Total: {{ $petugases->count() }} akun aktif</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-[#DAD887]/30">
                <table class="bento-table">
                    <thead>
                        <tr>
                            <th>Nama Petugas</th>
                            <th>Username</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($petugases as $petugas)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-[#75B06F]/20 rounded-lg flex items-center justify-center text-[#36656B] text-sm font-bold shrink-0">
                                            {{ strtoupper(substr($petugas->nama, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $petugas->nama }}</span>
                                    </div>
                                </td>
                                <td class="font-mono text-xs text-gray-500">{{ $petugas->username }}</td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('petugases.edit', $petugas) }}"
                                           class="inline-flex items-center gap-1 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        @if($petugas->id !== Auth::id())
                                            <form action="{{ route('petugases.destroy', $petugas) }}" method="POST"
                                                  onsubmit="return confirm('Hapus akun {{ $petugas->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
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
                                <td colspan="3" class="text-center py-8 text-gray-400 text-sm">Belum ada akun petugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-app-layout>
