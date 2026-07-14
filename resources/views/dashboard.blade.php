<x-app-layout>

    <!-- Bento Dashboard Grid -->
    <div class="space-y-4">

        <!-- Row 1: Stats Grid (3 tiles) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <!-- Tile: Total Petugas -->
            <div class="bg-[#36656B] rounded-2xl p-6 flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between">
                    <p class="text-[#F0F8A4] text-xs font-semibold uppercase tracking-widest">Total Petugas</p>
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-5xl font-bold text-white mt-4">{{ $data['total_petugas'] }}</p>
            </div>

            <!-- Tile: Total Warga -->
            <div class="bg-[#75B06F] rounded-2xl p-6 flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between">
                    <p class="text-white/80 text-xs font-semibold uppercase tracking-widest">Total Warga</p>
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-5xl font-bold text-white mt-4">{{ $data['total_warga'] }}</p>
            </div>

            <!-- Tile: Total Meteran Bulan Ini (Pemakaian) -->
            <div class="bg-[#DAD887] rounded-2xl p-6 flex flex-col justify-between min-h-[140px]">
                <div class="flex items-start justify-between">
                    <p class="text-[#36656B]/80 text-xs font-semibold uppercase tracking-widest">Pemakaian Bulan Ini</p>
                    <div class="w-8 h-8 bg-[#36656B]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-5xl font-bold text-[#36656B] mt-4">{{ number_format($data['total_meteran']) }} <span class="text-xl font-normal">m³</span></p>
            </div>
        </div>

        <!-- Row 2: Welcome Banner + Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Welcome Tile (span 2) -->
            <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-[#DAD887]/50">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B] text-xl font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-[#36656B]">
                            Selamat datang, {{ Auth::user()->nama }}!
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">
                            Anda login sebagai
                            <span class="inline-block bg-[#F0F8A4] text-[#36656B] font-semibold text-xs px-2 py-0.5 rounded-md uppercase">
                                {{ Auth::user()->role }}
                            </span>
                        </p>
                        <p class="text-gray-400 text-sm mt-3">
                            Gunakan menu di atas untuk mengelola data warga dan pencatatan meteran air dusun.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Tile -->
            <div class="bg-[#F0F8A4] rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <p class="text-[#36656B] text-xs font-semibold uppercase tracking-widest mb-2">Periode</p>
                    <p class="text-[#36656B] text-2xl font-bold">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-[#DAD887]">
                    <p class="text-[#36656B]/70 text-xs">
                        Data periode berjalan. Pastikan semua pencatatan meteran sudah diinput sebelum akhir bulan.
                    </p>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>