<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TIRTA ANUGERAH - Sistem Pencatatan Air Dusun</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="font-sans antialiased bg-[#F0F8A4]/20 text-[#1b1b18] flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-[#DAD887]/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl p-1 flex items-center justify-center shadow-sm shrink-0 border border-gray-100">
                        <img src="{{ asset('logoo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-[#36656B] leading-tight">TIRTA ANUGERAH</h1>
                        <p class="text-[10px] text-gray-500 font-medium tracking-wide">PAMSIMAS DESA MENGGORO</p>
                    </div>
                </div>

                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-150 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-16 pb-12 lg:pt-24 lg:pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- <span class="inline-block bg-[#DAD887]/40 text-[#36656B] text-xs font-bold px-3 py-1 rounded-full mb-6 tracking-wide uppercase">
                    Sistem Digital Terpadu
                </span> -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#36656B] tracking-tight mb-6">
                    Pencatatan Air Bersih <br class="hidden sm:block" />
                    <span class="text-[#75B06F]">Terpadu Cepat & Akurat</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-gray-600 mb-10 leading-relaxed">
                    Platform digital untuk pengelolaan data meteran air, perhitungan tagihan otomatis, 
                    dan monitoring pemakaian air warga.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold px-8 py-3.5 rounded-xl transition-all duration-150 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Masuk ke Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#statistik" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-[#36656B] border border-[#DAD887] font-semibold px-8 py-3.5 rounded-xl transition-all duration-150">
                        Lihat Statistik
                    </a>
                </div>
            </div>
        </section>

        <!-- Chart Stats Section -->
        <section id="statistik" class="py-16 bg-white border-y border-[#DAD887]/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#36656B]">Statistik Layanan Terkini</h2>
                    <p class="text-gray-500 mt-2">Data visual per {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Chart 1: Tren Pemakaian -->
                    <div class="bg-[#F0F8A4]/20 rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
                        <h3 class="text-lg font-bold text-[#36656B] mb-2 text-center">Tren Pemakaian Air (6 Bulan)</h3>
                        <div class="h-64 sm:h-72">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Distribusi Warga -->
                    <div class="bg-[#75B06F]/10 rounded-2xl p-6 border border-[#75B06F]/30 shadow-sm flex flex-col">
                        <h3 class="text-lg font-bold text-[#36656B] mb-2 text-center">Distribusi Warga Terlayani</h3>
                        <div class="h-64 sm:h-72 flex items-center justify-center">
                            <canvas id="dusunChart"></canvas>
                        </div>
                        <!-- Summary Text di Bawah Chart -->
                        <div class="mt-6 grid grid-cols-2 gap-4 text-center">
                            <div class="bg-white/60 rounded-xl p-3">
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total KK</p>
                                <p class="text-2xl font-extrabold text-[#36656B]">{{ number_format($stats['total_warga']) }}</p>
                            </div>
                            <div class="bg-white/60 rounded-xl p-3">
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Pemakaian Bulan Ini</p>
                                <p class="text-2xl font-extrabold text-[#75B06F]">{{ number_format($stats['total_pemakaian_bulan_ini']) }} <span class="text-sm font-normal">m³</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-[#36656B] text-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <p class="text-white/60 text-sm mt-1">Sistem Pencatatan Air Dusun Sragan</p>
                </div>
                <div class="text-center md:text-right text-white/60 text-sm">
                    <p>&copy; {{ date('Y') }} KKN UNTIDAR Desa Menggoro.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chart Initialization Script -->
    <script>
        // Data dari Laravel
        const trendData = @json($trendBulanan);
        const dusunData = @json($wargaPerDusun);

        // Warna Tema
        const colors = {
            primary: '#36656B',
            secondary: '#75B06F',
            accent: '#DAD887',
        };

        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.color = '#6b7280';

        // 1. Line Chart: Tren Pemakaian
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendData.map(d => d.bulan),
                datasets: [{
                    label: 'Pemakaian (m³)',
                    data: trendData.map(d => d.total),
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.primary,
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: { label: ctx => ctx.parsed.y.toLocaleString('id-ID') + ' m³' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e5e7eb' },
                        ticks: { callback: v => v.toLocaleString('id-ID') }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Doughnut Chart: Distribusi Dusun
        new Chart(document.getElementById('dusunChart'), {
            type: 'doughnut',
            data: {
                labels: dusunData.map(d => d.label),
                datasets: [{
                    data: dusunData.map(d => d.total),
                    backgroundColor: [colors.primary, colors.secondary],
                    borderWidth: 0,
                    hoverOffset: 6
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
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: colors.primary,
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' KK' }
                    }
                }
            }
        });
    </script>
</body>
</html>