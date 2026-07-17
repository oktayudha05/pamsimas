<x-app-layout>
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#DAD887]/50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-[#36656B]">Edit Akun</h1>
                    <p class="text-xs text-gray-400">Update data akun: {{ $akun->nama }}</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-red-50 text-red-700 text-xs font-semibold px-4 py-3 rounded-xl border border-red-200">
                    @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                </div>
            @endif

            <form action="{{ route('akuns.update', $akun) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <x-input-label for="nama" value="Nama Lengkap" />
                    <x-text-input id="nama" name="nama" type="text" value="{{ old('nama', $akun->nama) }}" required />
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="username" value="Username" />
                    <x-text-input id="username" name="username" type="text" value="{{ old('username', $akun->username) }}" required />
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="role" value="Hak Akses (Role)" />
                    <select id="role" name="role" required class="w-full px-4 py-2.5 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150">
                        <option value="petugas" {{ old('role', $akun->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="pengelola" {{ old('role', $akun->role) == 'pengelola' ? 'selected' : '' }}>Pengelola</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div class="border-t border-[#DAD887]/30 pt-4">
                    <p class="text-xs text-gray-400 mb-3">Kosongkan jika tidak ingin mengganti password.</p>
                    <div class="space-y-3">
                        <div>
                            <x-input-label for="password" value="Password Baru (Opsional)" />
                            <x-text-input id="password" name="password" type="password" placeholder="Biarkan kosong jika tidak diubah" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" />
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('akuns.index') }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>