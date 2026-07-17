<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PAMSIMAS') }} - Sistem Pencatatan Air Dusun</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F0F8A4]/30 min-h-screen flex flex-col h-full">

        <!-- Navigation Bar -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <!-- flex-grow memaksa main section mengambil ruang sisa agar footer terdorong ke bawah -->
        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 w-full">
            {{ $slot }}
        </main>

        <!-- Sticky Footer Mobile-Responsive -->
        <footer class="w-full py-2 text-center border-t border-[#36656B]/10 bg-white/40 backdrop-blur-sm mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-[#36656B]/70 text-xs sm:text-sm font-medium">
                    &copy; 2026 KKN UNTIDAR Desa Menggoro
                </p>
                <p class="text-[#36656B]/50 text-[10px] sm:text-xs mt-0.5 tracking-wide uppercase">
                    Sistem Pencatatan Air - PAMSIMAS
                </p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        @stack('scripts')
    </body>
</html>