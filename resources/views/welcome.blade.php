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
</head>
<body class="font-sans antialiased bg-[#F0F8A4]/20 text-[#1b1b18] flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-[#DAD887]/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#36656B] rounded-xl flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-[#36656B] leading-tight">TIRTA ANUGERAH</h1>
                        <p class="text-[10px] text-gray-500 font-medium tracking-wide">PAMSIMAS DESA MENGGORO</p>
                    </div>
                </div>

                <!-- Login Button -->
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
        <section class="relative overflow-hidden pt-16 pb-20 lg:pt-24 lg:pb-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="inline-block bg-[#DAD887]/40 text-[#36656B] text-xs font-bold px-3 py-1 rounded-full mb-6 tracking-wide uppercase">
                    Sistem Digital Terpadu
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#36656B] tracking-tight mb-6">
                    Pencatatan Air Bersih <br class="hidden sm:block" />
                    <span class="text-[#75B06F]">Lebih Transparan & Akurat</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-gray-600 mb-10 leading-relaxed">
                    Platform digital untuk pengelolaan data meteran air, perhitungan tagihan otomatis, 
                    dan monitoring pemakaian air warga Dusun Sragan.
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

        <!-- Stats Section -->
        <section id="statistik" class="py-16 bg-white border-y border-[#DAD887]/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#36656B]">Statistik Layanan Bulan Ini</h2>
                    <p class="text-gray-500 mt-2">Data terkini per {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Stat 1 -->
                    <div class="bg-[#F0F8A4]/30 rounded-2xl p-6 border border-[#DAD887]/50 text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-[#36656B] rounded-xl flex items-center justify-center text-white mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Warga Terlayani</p>
                        <p class="text-4xl font-extrabold text-[#36656B] mt-2">{{ number_format($stats['total_warga']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Kepala Keluarga</p>
                    </div>

                    <!-- Stat 2 -->
                    <div class="bg-[#75B06F]/10 rounded-2xl p-6 border border-[#75B06F]/30 text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-[#75B06F] rounded-xl flex items-center justify-center text-white mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Pemakaian</p>
                        <p class="text-4xl font-extrabold text-[#75B06F] mt-2">{{ number_format($stats['total_pemakaian_bulan_ini']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Meter Kubik (m³)</p>
                    </div>

                    <!-- Stat 3 -->
                    <div class="bg-[#DAD887]/30 rounded-2xl p-6 border border-[#DAD887]/50 text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-[#DAD887] rounded-xl flex items-center justify-center text-[#36656B] mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Status Layanan</p>
                        <p class="text-3xl font-extrabold text-[#36656B] mt-3">{{ $stats['status_layanan'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Sistem Berjalan Normal</p>
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
                    <p class="font-bold text-lg">TIRTA ANUGERAH</p>
                    <p class="text-white/60 text-sm mt-1">Sistem Pencatatan Air Bersih PAMSIMAS</p>
                </div>
                <div class="text-center md:text-right text-white/60 text-sm">
                    <p>&copy; {{ date('Y') }} KKN UNTIDAR Desa Menggoro.</p>
                    <p class="mt-1">Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>