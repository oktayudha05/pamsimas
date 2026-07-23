<x-app-layout>

    <!-- Row 1: Month Filter (Bento style header tile) -->
    <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#36656B]">Pencatatan Meteran Air</h1>
                <p class="text-xs text-gray-400">Pilih periode dan catat angka meteran warga</p>
            </div>
        </div>

        <form method="GET" action="{{ route('pencatatans.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <input type="month" name="bulan" value="{{ $bulan }}"
                   class="w-full sm:w-auto px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150"
                   onchange="this.form.submit()">

            <div class="relative flex items-center w-full sm:w-60">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau no. meteran..."
                    class="w-full px-4 py-2 pr-10 text-xs bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150">
                <button type="submit" class="absolute right-3 text-gray-400 hover:text-[#36656B]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
                @if(!empty($search))
                    <a href="{{ route('pencatatans.index', ['bulan' => $bulan]) }}" class="absolute right-8 text-[10px] text-red-500 hover:underline mr-1">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Error & Succes info -->
    @if(session('success'))
        <div class="mb-6 bg-[#75B06F]/20 text-[#36656B] text-sm font-semibold px-4 py-3 rounded-xl border border-[#75B06F]/30 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 text-red-700 text-sm font-semibold px-4 py-3 rounded-xl border border-red-200 shadow-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Row 2: Warga Recording Grid/Table Tile -->
    <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
        <h2 class="text-lg font-bold text-[#36656B] mb-4">Pengisian Meteran Bulanan</h2>

        <!-- Tabel Desktop (hidden md:block) -->
        <div class="hidden md:block overflow-x-auto rounded-xl border border-[#DAD887]/30">
            <table class="bento-table">
                <thead>
                    <tr>
                        <th>Warga / Meteran</th>
                        <th>Status Input</th>
                        <th>Angka Lalu</th>
                        <th>Angka Baru</th>
                        <th>Pemakaian</th>
                        <th class="w-1/4 text-center">Aksi / Pencatat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wargas as $warga)
                        <tr>
                            <!-- Warga details -->
                            <td>
                                <div class="font-semibold text-gray-900">{{ $warga->nama }}</div>
                                @if($warga->dusun === 'sragan')
                                <div class="text-xs text-gray-400 font-mono mt-0.5">
                                    No. Meter: {{ $warga->nomor_meteran }} | RT {{ sprintf('%02d', $warga->rt) }}/RW {{ sprintf('%02d', $warga->rw) }}
                                </div>
                                @else
                                <div class="text-xs text-gray-400 font-mono mt-0.5">
                                    No. Meter: {{ $warga->nomor_meteran }} | Luar Sragan
                                </div>
                                @endif
                            </td>

                            <!-- Input Status -->
                            <td>
                                @if($warga->pencatatan_sekarang)
                                    <span class="inline-flex items-center gap-1 bg-[#75B06F]/25 text-[#36656B] text-xs font-semibold px-2 py-0.5 rounded-lg border border-[#75B06F]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#75B06F]"></span>
                                        Sudah Diisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-lg border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-700"></span>
                                        Belum Diisi
                                    </span>
                                @endif
                            </td>

                            <!-- Angka Lalu -->
                            <td class="font-mono text-sm text-gray-600">
                                {{ $warga->pencatatan_lalu ? number_format($warga->pencatatan_lalu->angka_meteran) : 0 }}
                            </td>

                            <!-- Form or Value -->
                            <td>
                                @if($warga->pencatatan_sekarang)
                                    <span class="font-mono font-semibold text-[#36656B]">
                                        {{ number_format($warga->pencatatan_sekarang->angka_meteran) }}
                                    </span>
                                @else
                                    <!-- Inline Form -->
                                    <form id="record-{{ $warga->id }}" action="{{ route('pencatatans.store') }}" method="POST" class="flex items-center">
                                        @csrf
                                        <input type="hidden" name="warga_id" value="{{ $warga->id }}">
                                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                                        <input type="number" name="angka_meteran" min="0" placeholder="Input angka..."
                                               class="w-32 px-3 py-1.5 bg-[#F0F8A4]/30 border border-[#DAD887] text-gray-800 text-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-[#36656B]">
                                    </form>
                                @endif
                            </td>

                            <!-- Pemakaian -->
                            <td class="font-mono text-sm font-semibold">
                                @if($warga->pencatatan_sekarang)
                                    <span class="text-[#36656B]">+{{ number_format($warga->pencatatan_sekarang->pemakaian) }} m³</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <!-- Action or Logger -->
                            <td class="text-center">
                                @if($warga->pencatatan_sekarang)
                                    <div class="text-xs text-gray-400">
                                        Dicatat: {{ $warga->pencatatan_sekarang->user->nama ?? 'Sistem' }}
                                    </div>
                                @else
                                    <button type="submit" form="record-{{ $warga->id }}"
                                            class="inline-block bg-[#36656B] hover:bg-[#2a4f54] text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                                        Simpan
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-400 text-sm">Belum ada data warga terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- List Card Mobile (md:hidden) -->
        <div class="md:hidden space-y-3">
            @forelse($wargas as $warga)
                <div class="bg-[#F0F8A4]/10 border border-[#DAD887]/40 rounded-xl p-4 shadow-sm flex flex-col gap-3">
                    
                    <!-- Header: Nama & Status -->
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="font-semibold text-gray-900 text-base block">{{ $warga->nama }}</span>
                            @if($warga->dusun === 'sragan')
                                <span class="inline-block bg-[#F0F8A4] text-[#36656B] text-xs font-bold px-2 py-0.5 rounded-md">
                                    RT {{ sprintf('%02d', $warga->rt) }} / RW {{ sprintf('%02d', $warga->rw) }}
                                </span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-md">
                                    Luar Sragan
                                </span>
                            @endif
                            <span class="text-xs font-mono text-gray-400 mt-0.5 block">
                                No. Meter: {{ $warga->nomor_meteran }}
                            </span>
                        </div>
                        
                        <div>
                            @if($warga->pencatatan_sekarang)
                                <span class="inline-flex items-center gap-1 bg-[#75B06F]/25 text-[#36656B] text-xs font-semibold px-2 py-0.5 rounded-lg border border-[#75B06F]/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#75B06F]"></span>
                                    Sudah
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-lg border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-700"></span>
                                    Belum
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Grid: Lalu vs Baru vs Keluar -->
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg py-2 px-1">
                            <span class="text-[10px] text-gray-400 font-semibold uppercase block">Angka Lalu</span>
                            <span class="font-mono text-sm font-semibold text-gray-700 block mt-0.5">
                                {{ $warga->pencatatan_lalu ? number_format($warga->pencatatan_lalu->angka_meteran) : 0 }}
                            </span>
                        </div>
                        
                        <div class="bg-[#F0F8A4]/35 border border-[#DAD887]/30 rounded-lg py-2 px-1">
                            <span class="text-[10px] text-[#36656B]/70 font-semibold uppercase block">Angka Baru</span>
                            <span class="font-mono text-sm font-semibold text-[#36656B] block mt-0.5">
                                @if($warga->pencatatan_sekarang)
                                    {{ number_format($warga->pencatatan_sekarang->angka_meteran) }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        <div class="bg-gray-50 border border-gray-100 rounded-lg py-2 px-1">
                            <span class="text-[10px] text-gray-400 font-semibold uppercase block">Pemakaian</span>
                            <span class="font-mono text-sm font-semibold text-[#36656B] block mt-0.5">
                                @if($warga->pencatatan_sekarang)
                                    +{{ number_format($warga->pencatatan_sekarang->pemakaian) }} m³
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Input / Reporter Action -->
                    <div class="pt-2 border-t border-[#DAD887]/20 flex flex-col gap-2">
                        @if($warga->pencatatan_sekarang)
                            <div class="text-xs text-center text-gray-400 bg-gray-50/50 py-1.5 rounded-lg border border-gray-100">
                                Dicatat oleh: <span class="font-medium text-gray-600">{{ $warga->pencatatan_sekarang->user->nama ?? 'Sistem' }}</span>
                            </div>
                        @else
                            <form id="record-mobile-{{ $warga->id }}" action="{{ route('pencatatans.store') }}" method="POST" class="flex flex-col sm:flex-row gap-2 w-full">
                                @csrf
                                <input type="hidden" name="warga_id" value="{{ $warga->id }}">
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                
                                <div class="relative flex-1 min-w-0">
                                    <input type="number" name="angka_meteran" min="0" placeholder="Input angka baru..." required
                                        class="w-full px-3 py-2 bg-[#F0F8A4]/10 border border-[#DAD887] text-gray-800 text-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-[#36656B] focus:border-transparent transition">
                                </div>
                                
                                <button type="submit"
                                        class="bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition w-full sm:w-auto text-center shrink-0">
                                    Simpan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed rounded-xl">
                    Belum ada data warga terdaftar.
                </div>
            @endforelse
        </div>
    </div>

</x-app-layout>
