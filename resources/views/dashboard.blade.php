<x-app-layout>
    <div class="space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-[#DAD887]/50">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B] text-lg sm:text-xl font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-base sm:text-xl font-bold text-[#36656B] leading-snug">
                            Selamat datang, {{ Auth::user()->nama }}!
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">
                            Anda login sebagai
                            <span class="inline-block bg-[#F0F8A4] text-[#36656B] font-semibold text-xs px-2 py-0.5 rounded-md uppercase">
                                {{ Auth::user()->role }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-[#F0F8A4] rounded-2xl p-5 sm:p-6 flex flex-row items-center justify-left gap-4">
                <form method="GET" action="{{ route('dashboard') }}" class="relative shrink-0">
                    <input type="month" name="bulan" value="{{ $bulan }}"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="this.form.submit()">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#36656B] hover:bg-[#2a4f54] text-white rounded-xl flex items-center justify-center shadow-sm transition-all duration-150 pointer-events-none">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </form>
                    <div>
                    <p class="text-[#36656B] text-xs font-semibold uppercase tracking-widest mb-1">Periode</p>
                    <p class="text-[#36656B] text-xl sm:text-2xl font-bold">
                        {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ===== ROW 1: STATS CARDS (4 tiles sekarang) ===== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

            {{-- Total Petugas --}}
            <div class="bg-[#36656B] rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <p class="text-[#F0F8A4] text-[10px] sm:text-xs font-semibold uppercase tracking-widest leading-tight">Total Petugas</p>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl sm:text-4xl font-bold text-white mt-3">{{ $data['total_petugas'] }}</p>
            </div>

            {{-- Total Warga --}}
            <div class="bg-[#75B06F] rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <p class="text-white/80 text-[10px] sm:text-xs font-semibold uppercase tracking-widest leading-tight">Total Rumah</p>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl sm:text-4xl font-bold text-white mt-3">{{ $data['total_warga'] }}</p>
            </div>

            {{-- Pemakaian Bulan Ini --}}
            <div class="bg-[#DAD887] rounded-2xl p-4 sm:p-5 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <p class="text-[#36656B]/80 text-[10px] sm:text-xs font-semibold uppercase tracking-widest leading-tight">Pemakaian Bulan Ini</p>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#36656B]/20 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-[#36656B] mt-3">{{ number_format($data['total_meteran']) }} <span class="text-sm font-normal">m³</span></p>
            </div>

            {{-- Rata-rata per Warga (BARU!) --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 flex flex-col justify-between border border-[#DAD887]/50">
                <div class="flex items-start justify-between">
                    <p class="text-gray-500 text-[10px] sm:text-xs font-semibold uppercase tracking-widest leading-tight">Rata-rata / Warga</p>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#F0F8A4] rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-[#36656B] mt-3">{{ $rataRata }} <span class="text-sm font-normal text-gray-500">m³</span></p>
            </div>
        </div>

        {{-- ===== ROW 2: LINE CHART - TREND 6 BULAN ===== --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-[#DAD887]/50">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-[#36656B]">Tren Pemakaian Air</h3>
                    <p class="text-xs text-gray-400">Total pemakaian 6 bulan terakhir</p>
                </div>
                <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500">
                    <span class="w-3 h-3 rounded-full bg-[#36656B]"></span>
                    <span>m³ per bulan</span>
                </div>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- ===== ROW 3: BAR + DOUGHNUT CHART ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Bar Chart: Pemakaian per RT --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-[#DAD887]/50">
                <div class="mb-4">
                    <h3 class="text-base sm:text-lg font-bold text-[#36656B]">Pemakaian per RT</h3>
                    <p class="text-xs text-gray-400">Bulan {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="h-64 sm:h-72">
                    <canvas id="rtChart"></canvas>
                </div>
            </div>

            {{-- Doughnut: Distribusi Warga per RT --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-[#DAD887]/50">
                <div class="mb-4">
                    <h3 class="text-base sm:text-lg font-bold text-[#36656B]">Distribusi Warga</h3>
                    <p class="text-xs text-gray-400">Per RT se-dusun</p>
                </div>
                <div class="h-64 sm:h-72 flex items-center justify-center">
                    <canvas id="wargaChart"></canvas>
                </div>
            </div>
        </div>

        {{-- ===== ROW 4: TOP 5 + STATUS PENCATATAN ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Top 5 Warga --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-[#DAD887]/50">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B] shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#36656B]">Top 5 Pemakai Terbanyak</h3>
                        <p class="text-xs text-gray-400">Periode {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @forelse($topWarga as $index => $w)
                        <div class="flex items-center gap-3 p-2.5 rounded-lg {{ $index === 0 ? 'bg-[#F0F8A4]/40' : 'bg-gray-50' }} hover:bg-[#F0F8A4]/30 transition">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                                {{ $index === 0 ? 'bg-[#36656B] text-white' : ($index === 1 ? 'bg-[#75B06F] text-white' : ($index === 2 ? 'bg-[#DAD887] text-[#36656B]' : 'bg-gray-200 text-gray-600')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $w['nama'] }}</p>
                                <p class="text-[10px] text-gray-400">RT {{ $w['rt'] }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-[#36656B]">{{ number_format($w['pemakaian']) }}</p>
                                <p class="text-[10px] text-gray-400">m³</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-sm">
                            Belum ada data pencatatan bulan ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Status Pencatatan Bulan Ini --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-[#DAD887]/50">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-[#75B06F]/20 rounded-xl flex items-center justify-center text-[#36656B] shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#36656B]">Status Pencatatan</h3>
                        <p class="text-xs text-gray-400">Bulan {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="flex justify-between items-baseline mb-2">
                        <span class="text-xs font-semibold text-gray-600">Progress Pengisian</span>
                        <span class="text-2xl font-bold text-[#36656B]">{{ $persentaseIsi }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#75B06F] to-[#36656B] rounded-full transition-all duration-500"
                             style="width: {{ $persentaseIsi }}%"></div>
                    </div>
                </div>

                {{-- Detail Stats --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-[#75B06F]/10 border border-[#75B06F]/20 rounded-xl p-3 text-center">
                        <p class="text-[10px] text-[#36656B]/70 font-semibold uppercase tracking-wider">Sudah Diisi</p>
                        <p class="text-2xl font-bold text-[#75B06F] mt-1">{{ $sudahIsi }}</p>
                        <p class="text-[10px] text-gray-400">warga</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 rounded-xl p-3 text-center">
                        <p class="text-[10px] text-red-600/70 font-semibold uppercase tracking-wider">Belum Diisi</p>
                        <p class="text-2xl font-bold text-red-500 mt-1">{{ $belumIsi }}</p>
                        <p class="text-[10px] text-gray-400">warga</p>
                    </div>
                </div>

                {{-- Info Tambahan --}}
                <div class="mt-4 pt-4 border-t border-[#DAD887]/30">
                    <p class="text-xs text-gray-500">
                        @if($persentaseIsi == 100)
                            <span class="text-[#75B06F] font-semibold">✓ Semua warga sudah mengisi data bulan ini!</span>
                        @elseif($persentaseIsi >= 75)
                            <span class="text-[#36656B] font-semibold">Hampir selesai!</span> Tinggal {{ $belumIsi }} warga lagi.
                        @elseif($persentaseIsi >= 50)
                            <span class="text-amber-600 font-semibold">Setengah jalan.</span> Ayo kejar target!
                        @else
                            <span class="text-red-500 font-semibold">Perlu perhatian!</span> Masih banyak yang belum isi.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- ===== ROW 5: WELCOME + INFO PERIODE ===== --}}
    </div>

    {{-- ===== SCRIPT CHART.JS ===== --}}
    @push('scripts')
    <script>
        // Data dari Laravel (di-encode ke JSON)
        const trendData = @json($trendBulanan);
        const rtData = @json($pemakaianPerRt);
        const wargaData = @json($wargaPerRt);

        // Warna tema TIRTA ANUGERAH
        const colors = {
            primary: '#36656B',
            secondary: '#75B06F',
            accent: '#DAD887',
            light: '#F0F8A4',
            palette: ['#36656B', '#75B06F', '#DAD887', '#F0F8A4', '#2a4f54', '#62a15c']
        };

        // Default Chart.js config
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.color = '#6b7280';

        // ===== 1. LINE CHART: TREND 6 BULAN =====
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendData.map(d => d.bulan),
                datasets: [{
                    label: 'Total Pemakaian (m³)',
                    data: trendData.map(d => d.total),
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.primary,
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ctx.parsed.y.toLocaleString('id-ID') + ' m³'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            callback: v => v.toLocaleString('id-ID')
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // ===== 2. BAR CHART: PEMAKAIAN PER RT =====
        new Chart(document.getElementById('rtChart'), {
            type: 'bar',
            data: {
                labels: rtData.map(d => d.rt),
                datasets: [{
                    label: 'Pemakaian (m³)',
                    data: rtData.map(d => d.total),
                    backgroundColor: rtData.map((_, i) => colors.palette[i % colors.palette.length] + 'CC'),
                    borderColor: rtData.map((_, i) => colors.palette[i % colors.palette.length]),
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.primary,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ctx.parsed.y.toLocaleString('id-ID') + ' m³'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { callback: v => v.toLocaleString('id-ID') }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // ===== 3. DOUGHNUT CHART: DISTRIBUSI WARGA =====
        new Chart(document.getElementById('wargaChart'), {
            type: 'doughnut',
            data: {
                labels: wargaData.map(d => d.rt),
                datasets: [{
                    data: wargaData.map(d => d.total),
                    backgroundColor: wargaData.map((_, i) => colors.palette[i % colors.palette.length]),
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: colors.primary,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ctx.label + ': ' + ctx.parsed + ' warga'
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>