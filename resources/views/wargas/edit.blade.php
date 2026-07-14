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
                    <h1 class="text-lg font-bold text-[#36656B]">Edit Data Warga</h1>
                    <p class="text-xs text-gray-400">Update data warga: {{ $warga->nama }}</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-red-50 text-red-700 text-xs font-semibold px-4 py-3 rounded-xl border border-red-200">
                    @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                </div>
            @endif

            <form action="{{ route('wargas.update', $warga) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <x-input-label for="nama" value="Nama Kepala Keluarga" />
                    <x-text-input id="nama" name="nama" type="text" value="{{ old('nama', $warga->nama) }}" required />
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="rt" value="No. RT" />
                        <x-text-input id="rt" name="rt" type="number" min="1" value="{{ old('rt', $warga->rt) }}" required />
                        <x-input-error :messages="$errors->get('rt')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="rw" value="No. RW" />
                        <x-text-input id="rw" name="rw" type="number" min="1" value="{{ old('rw', $warga->rw) }}" required />
                        <x-input-error :messages="$errors->get('rw')" class="mt-1" />
                    </div>
                </div>

                <div>
                    <x-input-label for="nomor_meteran" value="Nomor Meteran Air" />
                    <x-text-input id="nomor_meteran" name="nomor_meteran" type="text" value="{{ old('nomor_meteran', $warga->nomor_meteran) }}" required />
                    <x-input-error :messages="$errors->get('nomor_meteran')" class="mt-1" />
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('wargas.index') }}"
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
