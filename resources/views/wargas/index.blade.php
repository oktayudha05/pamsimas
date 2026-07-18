<x-app-layout>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Form Tambah Rumah -->
<div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm h-fit">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-[#36656B]">Tambah Rumah</h2>
            <p class="text-xs text-gray-400">Daftarkan kepala keluarga baru</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-[#75B06F]/20 text-[#36656B] text-xs font-semibold px-4 py-3 rounded-xl border border-[#75B06F]/30">
            {{ session('success') }}
        </div>
    @endif

    {{-- ⬇️ x-data buat Alpine.js, default dusun = sragan --}}
    <form action="{{ route('wargas.store') }}" method="POST" class="space-y-4"
          x-data="{ dusun: '{{ old('dusun', 'sragan') }}' }">
        @csrf
        
        <div>
            <x-input-label for="nama" value="Nama Kepala Keluarga" />
            <x-text-input id="nama" name="nama" type="text" value="{{ old('nama') }}" required placeholder="Nama Lengkap..." />
            <x-input-error :messages="$errors->get('nama')" class="mt-1" />
        </div>

        {{-- ⬇️ DROPDOWN DUSUN --}}
        <div>
            <x-input-label for="dusun" value="Dusun" />
            <select id="dusun" name="dusun" x-model="dusun" required
                class="w-full px-4 py-2.5 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150">
                <option value="sragan">Sragan</option>
                <option value="luar_sragan">Luar Sragan</option>
            </select>
            <x-input-error :messages="$errors->get('dusun')" class="mt-1" />
        </div>

        {{-- ⬇️ RT/RW cuma muncul kalau dusun = sragan --}}
        <div x-show="dusun === 'sragan'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="rt" value="No. RT" />
                <x-text-input id="rt" name="rt" type="number" min="1" value="{{ old('rt') }}" placeholder="01"
                    :disabled="false" />
                <x-input-error :messages="$errors->get('rt')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="rw" value="No. RW" />
                <x-text-input id="rw" name="rw" type="number" min="1" value="{{ old('rw') }}" placeholder="02" />
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
        <!-- Daftar Rumah (kolom kanan / bawah di mobile) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#75B06F]/20 rounded-xl flex items-center justify-center text-[#36656B] shrink-0">
                        <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-[#36656B]">Daftar Rumah</h2>
                        <p class="text-xs text-gray-400">Total terdata: {{ $wargas->count() }} rumah</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Desktop (sembunyikan di HP) -->
            <div class="hidden md:block overflow-x-auto rounded-xl border border-[#DAD887]/30">
                <table class="bento-table">
                <thead>
                    <tr>
                        <th>Nama Warga</th>
                        <th class="text-center">Lokasi</th>
                        <th>No. Meteran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wargas as $warga)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-[#75B06F]/20 rounded-lg flex items-center justify-center text-[#36656B] text-sm font-bold shrink-0">
                                        {{ strtoupper(substr($warga->nama, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $warga->nama }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($warga->dusun === 'sragan')
                                    <span class="inline-block bg-[#F0F8A4] text-[#36656B] text-xs font-bold px-2 py-0.5 rounded-md">
                                        RT {{ sprintf('%02d', $warga->rt) }} / RW {{ sprintf('%02d', $warga->rw) }}
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-md">
                                        Luar Sragan
                                    </span>
                                @endif
                            </td>
                            <td class="font-mono text-xs text-gray-500">{{ $warga->nomor_meteran }}</td>
                            <td class="text-center">
                                {{-- (biar sama kayak sebelumnya) --}}
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

            <!-- Card List Mobile (hanya di HP) -->
            <div class="md:hidden space-y-3">
                @forelse($wargas as $warga)
                    <div class="bg-[#F0F8A4]/10 border border-[#DAD887]/40 rounded-xl p-4 shadow-sm flex flex-col gap-3">
                        
                        {{-- BARIS 1: Avatar, Nama, No. Meteran, dan Badge RT/RW --}}
                        <div class="flex items-center gap-3">
                            {{-- Avatar --}}
                            <span class="w-10 h-10 bg-[#75B06F]/20 rounded-xl flex items-center justify-center text-[#36656B] text-base font-bold shrink-0">
                                {{ strtoupper(substr($warga->nama, 0, 1)) }}
                            </span>
                            
                            {{-- Nama + No. Meteran --}}
                            <div class="flex-1 min-w-0">
                                <span class="font-semibold text-gray-900 text-base block truncate">{{ $warga->nama }}</span>
                                <span class="text-xs font-mono text-gray-400 block mt-0.5">No. Meter: {{ $warga->nomor_meteran }}</span>
                            </div>
                            
                            {{-- Badge RT/RW --}}
                            <span class="inline-block bg-[#F0F8A4] text-[#36656B] text-xs font-bold px-2 py-1 rounded-md shrink-0">
                                RT {{ sprintf('%02d', $warga->rt) }} / RW {{ sprintf('%02d', $warga->rw) }}
                            </span>
                        </div>

                        {{-- BARIS 2: Tombol Aksi --}}
                        <div class="flex items-center gap-2 pt-2 border-t border-[#DAD887]/30">
                            <a href="{{ route('wargas.edit', $warga) }}"
                            class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold py-2.5 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('wargas.destroy', $warga) }}" method="POST"
                                onsubmit="return confirm('Hapus data warga {{ $warga->nama }}?')"
                                class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full inline-flex justify-center items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold py-2.5 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed rounded-xl">
                        Belum ada data warga terdaftar.
                    </div>
                @endforelse
            </div>

            <!-- Footer -->
            <div class="mt-6 pt-4 border-t border-[#DAD887]/30 flex justify-between items-center text-xs text-gray-400">
                <span>Diurutkan berdasarkan RT &amp; RW</span>
                <span>TIRTA ANUGERAH</span>
            </div>
        </div>
    </div>

</x-app-layout>
