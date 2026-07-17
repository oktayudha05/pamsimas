<x-app-layout>

    <!-- Print styling -->
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            nav, .no-print {
                display: none !important;
            }
            .print-container {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            th, td {
                border: 1px solid #ddd !important;
                padding: 8px !important;
            }
            th {
                background-color: #f2f2f2 !important;
                color: black !important;
            }
        }
    </style>

    <div class="print-container space-y-6">

        <!-- Row 1: Filter & Action (Bento Tile) -->
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32-2v-2a4 4 0 00-4-4h-2a4 4 0 00-4 4v2m-6-10a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#36656B]">Rekap Bulanan</h1>
                    <p class="text-xs text-gray-400">Hub rekapitulasi data air desa</p>
                </div>
            </div>

            <!-- Gunakan flex-wrap agar bisa turun ke bawah di HP, dan w-full agar rapi -->
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                
                <!-- Month Filter (Full width di HP, auto di desktop) -->
                <form method="GET" action="{{ route('rekap.index') }}" class="flex items-center w-full sm:w-auto">
                    <input type="month" name="bulan" value="{{ $bulan }}"
                        class="w-full sm:w-auto px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B]"
                        onchange="this.form.submit()">
                </form>

                <!-- Download Excel Button -->
                <a href="{{ route('rekap.excel', ['bulan' => $bulan]) }}"
                    class="flex-1 sm:flex-none bg-[#75B06F] hover:bg-[#62a15c] text-white text-sm font-semibold px-4 py-2 rounded-xl flex items-center justify-center sm:justify-start gap-2 shadow-sm transition whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download Excel</span>
                </a>

                <!-- Print Button -->
                <!-- <button onclick="window.print()"
                    class="flex-1 sm:flex-none bg-[#75B06F] hover:bg-[#62a15c] text-white text-sm font-semibold px-4 py-2 rounded-xl flex items-center justify-center sm:justify-start gap-2 shadow-sm transition whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Laporan</span>
                </button> -->
            </div>
        </div>

        <!-- Print-only Header -->
        <div class="hidden print:block text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">REKAPITULASI PENGGUNAAN AIR PASIMAS</h1>
            <p class="text-sm text-gray-500 font-mono mt-1">Periode Laporan: {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</p>
            <div class="border-b-2 border-gray-900 mt-4"></div>
        </div>

        <!-- Row 2: Summary Stats and Overall Consumption (Bento Cards) -->


        <!-- Row 3: Report Table -->
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
            <div class="flex items-center justify-between mb-4 no-print">
                <h2 class="text-lg font-bold text-[#36656B]">Laporan Rincian Pemakaian</h2>
                <div class="text-xs text-gray-400">Menampilkan seluruh data warga</div>
            </div>

            <!-- Tabel Desktop / Mode Cetak (hidden md:block print:block) -->
            <div class="hidden md:block print:block overflow-x-auto rounded-xl border border-[#DAD887]/30">
                <table class="bento-table w-full">
                    <thead>
                        <tr>
                            <th>Nama Kepala Keluarga</th>
                            <th class="text-center">RT / RW</th>
                            <th>No. Meteran</th>
                            <th class="text-center">Angka Meteran</th>
                            <th class="text-center">Pemakaian (Selisih)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wargas as $warga)
                            <tr>
                                <td class="font-medium text-gray-900">{{ $warga->nama }}</td>
                                <td class="text-center">RT {{ sprintf('%02d', $warga->rt) }} / RW {{ sprintf('%02d', $warga->rw) }}</td>
                                <td class="font-mono text-xs text-gray-500">{{ $warga->nomor_meteran }}</td>
                                <td class="text-right font-mono">
                                    {{ $warga->pencatatan ? number_format($warga->pencatatan->angka_meteran) : '-' }}
                                </td>
                                <td class="text-right font-semibold font-mono text-[#36656B]">
                                    @if($warga->pencatatan)
                                        {{ number_format($warga->pencatatan->pemakaian) }} m³
                                    @else
                                        <span class="text-red-500 text-xs font-semibold">Belum Diisi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-400 text-sm">Belum ada data warga terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- List Card Mobile (md:hidden print:hidden no-print) -->
            <div class="md:hidden print:hidden no-print space-y-3">
                @forelse($wargas as $warga)
                    <div class="bg-[#F0F8A4]/10 border border-[#DAD887]/40 rounded-xl p-4 shadow-sm flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-gray-900 text-base block">{{ $warga->nama }}</span>
                                <span class="text-xs font-mono text-gray-400 mt-0.5 block">
                                    No. Meter: {{ $warga->nomor_meteran }}
                                </span>
                            </div>
                            <span class="inline-block bg-[#F0F8A4] text-[#36656B] text-xs font-bold px-2 py-0.5 rounded-md">
                                RT {{ sprintf('%02d', $warga->rt) }} / RW {{ sprintf('%02d', $warga->rw) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-center">
                            <div class="bg-gray-50 border border-gray-100 rounded-lg py-2">
                                <span class="text-[10px] text-gray-400 font-semibold uppercase block">Angka Meteran</span>
                                <span class="font-mono text-sm font-semibold text-gray-700 block mt-0.5">
                                    {{ $warga->pencatatan ? number_format($warga->pencatatan->angka_meteran) : '-' }}
                                </span>
                            </div>
                            
                            <div class="bg-[#F0F8A4]/20 border border-[#DAD887]/20 rounded-lg py-2">
                                <span class="text-[10px] text-[#36656B]/70 font-semibold uppercase block">Pemakaian Air</span>
                                <span class="font-mono text-sm font-semibold text-[#36656B] block mt-0.5">
                                    @if($warga->pencatatan)
                                        {{ number_format($warga->pencatatan->pemakaian) }} m³
                                    @else
                                        <span class="text-red-500 text-xs font-bold">Belum Diisi</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed rounded-xl">
                        Belum ada data warga terdaftar.
                    </div>
                @endforelse
            </div>

            <!-- Print Signature Panel -->
            <div class="hidden print:flex justify-between mt-12 text-sm">
                <div></div>
                <div class="text-center">
                    <p class="mb-16">Pengelola/Petugas PAMSIMAS</p>
                    <p class="font-bold underline">{{ Auth::user()->nama }}</p>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
