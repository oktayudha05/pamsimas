<x-app-layout>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Bento Tile: Form Tambah Warga -->
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm h-fit">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#36656B]">Tambah Warga</h2>
                    <p class="text-xs text-gray-400">Daftarkan kepala keluarga baru</p>
                </div>
            </div>

            <!-- Toast Success -->
            @if (session('success'))
                <div class="mb-4 bg-[#75B06F]/20 text-[#36656B] text-xs font-semibold px-4 py-3 rounded-xl border border-[#75B06F]/30">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('wargas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="nama" value="Nama Kepala Keluarga" />
                    <x-text-input id="nama" name="nama" type="text" value="{{ old('nama') }}" required placeholder="Nama Lengkap..." />
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="rt" value="No. RT" />
                        <x-text-input id="rt" name="rt" type="number" min="1" value="{{ old('rt') }}" required placeholder="01" />
                        <x-input-error :messages="$errors->get('rt')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="rw" value="No. RW" />
                        <x-text-input id="rw" name="rw" type="number" min="1" value="{{ old('rw') }}" required placeholder="02" />
                        <x-input-error :messages="$errors->get('rw')" class="mt-1" />
                    </div>
                </div>

                <div>
                    <x-input-label for="nomor_meteran" value="Nomor Meteran Air" />
                    <x-text-input id="nomor_meteran" name="nomor_meteran" type="text" value="{{ old('nomor_meteran') }}" required placeholder="MTR-XXXXXX" />
                    <x-input-error :messages="$errors->get('nomor_meteran')" class="mt-1" />
                </div>

                <button type="submit" class="w-full bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-all duration-150 shadow-sm">
                    Simpan Data
                </button>
            </form>
        </div>

        <!-- Bento Tile: Daftar Warga -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#75B06F]/20 rounded-xl flex items-center justify-center text-[#36656B]">
                            <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-[#36656B]">Daftar Warga</h2>
                            <p class="text-xs text-gray-400">Total terdata: {{ $wargas->count() }} orang</p>
                        </div>
                    </div>
                </div>

                <!-- Bento Table -->
                <div class="overflow-x-auto rounded-xl border border-[#DAD887]/30">
                    <table class="bento-table">
                        <thead>
                            <tr>
                                <th>Nama Warga</th>
                                <th class="text-center">RT / RW</th>
                                <th>No. Meteran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wargas as $warga)
                                <tr>
                                    <td class="font-medium text-gray-900">{{ $warga->nama }}</td>
                                    <td class="text-center">
                                        <span class="inline-block bg-[#F0F8A4] text-[#36656B] text-xs font-bold px-2 py-0.5 rounded-md">
                                            RT {{ sprintf('%02d', $warga->rt) }} / RW {{ sprintf('%02d', $warga->rw) }}
                                        </span>
                                    </td>
                                    <td class="font-mono text-xs text-gray-500">{{ $warga->nomor_meteran }}</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('wargas.edit', $warga) }}"
                                               class="inline-flex items-center gap-1 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold px-2.5 py-1.5 rounded-lg transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('wargas.destroy', $warga) }}" method="POST"
                                                  onsubmit="return confirm('Hapus data warga {{ $warga->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400 text-sm">Belum ada data warga terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer information -->
            <div class="mt-6 pt-4 border-t border-[#DAD887]/30 flex justify-between items-center text-xs text-gray-400">
                <span>Diurutkan berdasarkan RT & RW</span>
                <span>PAMSIMAS Dusun</span>
            </div>
        </div>

    </div>

</x-app-layout>
