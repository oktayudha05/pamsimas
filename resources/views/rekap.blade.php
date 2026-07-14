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

            <div class="flex items-center gap-3">
                <!-- Month Filter -->
                <form method="GET" action="{{ route('rekap.index') }}" class="flex items-center">
                    <input type="month" name="bulan" value="{{ $bulan }}"
                           class="px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B]"
                           onchange="this.form.submit()">
                </form>

                <!-- Print Button -->
                <button onclick="window.print()"
                        class="bg-[#75B06F] hover:bg-[#62a15c] text-white text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        <!-- Print-only Header -->
        <div class="hidden print:block text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">REKAPITULASI PENGGUNAAN AIR PASIMAS</h1>
            <p class="text-sm text-gray-500 font-mono mt-1">Periode Laporan: {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</p>
            <div class="border-b-2 border-gray-900 mt-4"></div>
        </div>

        <!-- Row 2: Summary Stats and Overall Consumption (Bento Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Total Pemakaian Air -->
            <div class="bg-[#36656B] rounded-2xl p-6 flex flex-col justify-between min-h-[120px] text-white">
                <p class="text-[#F0F8A4] text-xs font-semibold uppercase tracking-widest">Total Konsumsi Air</p>
                <div class="flex items-baseline justify-between mt-4">
                    <p class="text-4xl font-bold">{{ number_format($totalPemakaian) }} <span class="text-xl font-normal">m³</span></p>
                    <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"/>
                    </svg>
                </div>
            </div>

            <!-- Total Terdata -->
            <div class="bg-[#DAD887] rounded-2xl p-6 flex flex-col justify-between min-h-[120px] text-[#36656B]">
                <p class="text-[#36656B]/70 text-xs font-semibold uppercase tracking-widest">Warga Terdaftar</p>
                <div class="flex items-baseline justify-between mt-4">
                    <p class="text-4xl font-bold">{{ $wargas->count() }} <span class="text-xl font-normal">KK</span></p>
                    <svg class="w-8 h-8 opacity-20 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Row 3: Report Table -->
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
            <div class="flex items-center justify-between mb-4 no-print">
                <h2 class="text-lg font-bold text-[#36656B]">Laporan Rincian Pemakaian</h2>
                <div class="text-xs text-gray-400">Menampilkan seluruh data warga</div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-[#DAD887]/30">
                <table class="bento-table w-full">
                    <thead>
                        <tr>
                            <th>Nama Kepala Keluarga</th>
                            <th class="text-center">RT / RW</th>
                            <th>No. Meteran</th>
                            <th class="text-right">Angka Meteran</th>
                            <th class="text-right">Pemakaian (Selisih)</th>
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
