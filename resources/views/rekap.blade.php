<x-app-layout>
    <!-- Print styling -->
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            nav, .no-print { display: none !important; }
            .print-container { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; }
            table { width: 100% !important; border-collapse: collapse !important; font-size: 10px !important; }
            th, td { border: 1px solid #ddd !important; padding: 4px !important; }
            th { background-color: #f2f2f2 !important; color: black !important; font-weight: bold !important; }
        }
    </style>

    <div class="print-container space-y-6">

        <!-- Row 1: Filter & Action -->
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32-2v-2a4 4 0 00-4-4h-2a4 4 0 00-4 4v2m-6-10a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#36656B]">Rekap Pembayaran Bulanan</h1>
                    <p class="text-xs text-gray-400">Rincian pemakaian, tagihan, dan status pembayaran</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <form method="GET" action="{{ route('rekap.index') }}" class="flex items-center w-full sm:w-auto">
                    <input type="month" name="bulan" value="{{ $bulan }}"
                        class="w-full sm:w-auto px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B]"
                        onchange="this.form.submit()">
                </form>
                <a href="{{ route('rekap.excel', ['bulan' => $bulan]) }}"
                    class="flex-1 sm:flex-none bg-[#75B06F] hover:bg-[#62a15c] text-white text-sm font-semibold px-4 py-2 rounded-xl flex items-center justify-center sm:justify-start gap-2 shadow-sm transition whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download Excel</span>
                </a>
            </div>
        </div>

        <!-- Print-only Header -->
        <div class="hidden print:block text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">REKAPITULASI PENGGUNAAN & TAGIHAN AIR TIRTA ANUGERAH</h1>
            <p class="text-sm text-gray-500 font-mono mt-1">Periode Laporan: {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</p>
            <div class="border-b-2 border-gray-900 mt-4"></div>
        </div>

        <!-- Row 2: Summary Stats (Kartu Ringkasan) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 no-print">
            <div class="bg-[#36656B] rounded-2xl p-4 text-white">
                <p class="text-[10px] uppercase tracking-widest opacity-80">Total Pemakaian</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($totalPemakaian) }} <span class="text-sm font-normal">m³</span></p>
            </div>
            <div class="bg-[#DAD887] rounded-2xl p-4 text-[#36656B]">
                <p class="text-[10px] uppercase tracking-widest opacity-80">Total Tagihan</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-[#75B06F] rounded-2xl p-4 text-white">
                <p class="text-[10px] uppercase tracking-widest opacity-80">Total Terbayar</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-50 border border-red-100 rounded-2xl p-4 text-red-600">
                <p class="text-[10px] uppercase tracking-widest opacity-80">Total Hutang</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalHutang, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Row 3: Report Table -->
        <div class="bg-white rounded-2xl border border-[#DAD887]/50 shadow-sm overflow-hidden">
            <div class="pl-6 pr-2 pt-6 border-[#DAD887]/30 flex items-center justify-between no-print">
                <h2 class="text-lg font-bold text-[#36656B]">Laporan Rincian Tagihan</h2>
            </div>

            <!-- Tabel Desktop / Mode Cetak -->
            <div class="hidden md:block print:block overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-[#36656B] text-white uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-3 text-left">Nama</th>
                            <th class="px-3 py-3 text-center">Lokasi</th>
                            <th class="px-3 py-3 text-right">Meter Awal</th>
                            <th class="px-3 py-3 text-right">Meter Akhir</th>
                            <th class="px-3 py-3 text-right">Pemakaian</th>
                            <th class="px-3 py-3 text-right">Tarif/m³</th>
                            <th class="px-3 py-3 text-right">Dana Meter</th>
                            <th class="px-3 py-3 text-right">Harga Air</th>
                            <th class="px-3 py-3 text-right">Tagihan</th>
                            <th class="px-3 py-3 text-right">Titip Lama</th>
                            <th class="px-3 py-3 text-right font-bold">Total Tagihan</th>
                            <th class="px-3 py-3 text-right text-[#75B06F]">Terbayar</th>
                            <th class="px-3 py-3 text-right">Hutang / Titip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DAD887]/30">
                        @forelse($wargas as $warga)
                            <tr class="hover:bg-[#F0F8A4]/20 transition-colors">
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $warga->nama }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @if($warga->dusun === 'sragan')
                                        RT {{ sprintf('%02d', $warga->rt) }} / RW {{ sprintf('%02d', $warga->rw) }}
                                    @else
                                        <span class="text-gray-500">Luar Sragan</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right font-mono">{{ number_format($warga->meteran_awal) }}</td>
                                <td class="px-3 py-2 text-right font-mono font-semibold">{{ $warga->pencatatan ? number_format($warga->meteran_akhir) : '-' }}</td>
                                <td class="px-3 py-2 text-right font-mono">{{ number_format($warga->pemakaian) }}</td>
                                <td class="px-3 py-2 text-right font-mono">{{ number_format($warga->tarif_per_meter) }}</td>
                                <td class="px-3 py-2 text-right font-mono">{{ number_format($warga->dana_meter) }}</td>
                                <td class="px-3 py-2 text-right font-mono">{{ number_format($warga->harga_air) }}</td>
                                <td class="px-3 py-2 text-right font-mono font-semibold">{{ number_format($warga->tagihan_bulan_ini) }}</td>
                                
                                {{-- ✅ PERBAIKAN: Titip Lama dengan warna dinamis & abs() --}}
                                <td class="px-3 py-2 text-right font-mono font-medium {{ $warga->titip_lama > 0 ? 'text-red-600' : ($warga->titip_lama < 0 ? 'text-[#75B06F]' : 'text-gray-400') }}">
                                    Rp {{ number_format(abs($warga->titip_lama), 0, ',', '.') }}
                                    @if($warga->titip_lama > 0)<span class="text-[9px] block text-red-500">(Hutang)</span>
                                    @elseif($warga->titip_lama < 0)<span class="text-[9px] block text-[#75B06F]">(Titip)</span>
                                    @endif
                                </td>

                                <td class="px-3 py-2 text-right font-mono font-bold text-[#36656B]">Rp {{ number_format($warga->total_tagihan, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right font-mono text-[#75B06F]">Rp {{ number_format($warga->terbayar, 0, ',', '.') }}</td>
                                
                                {{-- ✅ PERBAIKAN: Hutang / Titip dengan warna dinamis & abs() --}}
                                <td class="px-3 py-2 text-right font-mono font-bold {{ $warga->hutang_titip > 0 ? 'text-red-600' : ($warga->hutang_titip < 0 ? 'text-[#75B06F]' : 'text-gray-400') }}">
                                    Rp {{ number_format(abs($warga->hutang_titip), 0, ',', '.') }}
                                    @if($warga->hutang_titip > 0)<span class="text-[9px] block text-red-500">(Hutang)</span>
                                    @elseif($warga->hutang_titip < 0)<span class="text-[9px] block text-[#75B06F]">(Titip)</span>
                                    @else<span class="text-[9px] block text-gray-400">(Lunas)</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-8 text-gray-400 text-sm">Belum ada data warga terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!-- Footer Total di Tabel -->
                    <tfoot class="bg-[#F0F8A4]/30 font-bold no-print">
                        <tr>
                            <td colspan="4" class="text-right px-3 py-3">TOTAL KESELURUHAN</td>
                            <td class="text-right px-3 py-3 font-mono">{{ number_format($totalPemakaian) }} m³</td>
                            <td colspan="5"></td>
                            <td class="text-right px-3 py-3 font-mono text-[#36656B]">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                            <td class="text-right px-3 py-3 font-mono text-[#75B06F]">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</td>
                            <td class="text-right px-3 py-3 font-mono text-red-600">Rp {{ number_format($totalHutang, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- List Card Mobile (Disederhanakan agar tidak penuh) -->
            <div class="md:hidden print:hidden no-print space-y-3 p-4">
                @forelse($wargas as $warga)
                    <div class="bg-[#F0F8A4]/10 border border-[#DAD887]/40 rounded-xl p-4 shadow-sm flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-gray-900 text-base block">{{ $warga->nama }}</span>
                                <span class="text-xs font-mono text-gray-400 mt-0.5 block">No. Meter: {{ $warga->nomor_meteran }}</span>
                            </div>
                            @if($warga->dusun === 'sragan')
                                <span class="inline-block bg-[#F0F8A4] text-[#36656B] text-xs font-bold px-2 py-0.5 rounded-md">RT {{ sprintf('%02d', $warga->rt) }}</span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-md">Luar Sragan</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-center text-xs">
                            <div class="bg-white rounded-lg py-2 border border-[#DAD887]/30">
                                <span class="text-gray-400 block">Pemakaian</span>
                                <span class="font-mono font-semibold text-[#36656B] block mt-1">{{ number_format($warga->pemakaian) }} m³</span>
                            </div>
                            <div class="bg-white rounded-lg py-2 border border-[#DAD887]/30">
                                <span class="text-gray-400 block">Total Tagihan</span>
                                <span class="font-mono font-semibold text-[#36656B] block mt-1">Rp {{ number_format($warga->total_tagihan, 0, ',', '.') }}</span>
                            </div>
                            <div class="bg-white rounded-lg py-2 border border-[#DAD887]/30">
                                <span class="text-gray-400 block">Terbayar</span>
                                <span class="font-mono font-semibold text-[#75B06F] block mt-1">Rp {{ number_format($warga->terbayar, 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- ✅ PERBAIKAN: Mobile view juga pakai abs() dan warna dinamis --}}
                            <div class="bg-white rounded-lg py-2 border border-[#DAD887]/30">
                                <span class="text-gray-400 block">Hutang</span>
                                <span class="font-mono font-semibold {{ $warga->hutang_titip > 0 ? 'text-red-600' : ($warga->hutang_titip < 0 ? 'text-[#75B06F]' : 'text-gray-400') }} block mt-1">
                                    Rp {{ number_format(abs($warga->hutang_titip), 0, ',', '.') }}
                                    @if($warga->hutang_titip > 0) <span class="text-[9px]">(Hutang)</span>
                                    @elseif($warga->hutang_titip < 0) <span class="text-[9px]">(Titip)</span>
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
            <div class="hidden print:flex justify-between mt-12 text-sm px-6 pb-6">
                <div></div>
                <div class="text-center">
                    <p class="mb-16">Pengelola/Petugas TIRTA ANUGERAH</p>
                    <p class="font-bold underline">{{ Auth::user()->nama }}</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>