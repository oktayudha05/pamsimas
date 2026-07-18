<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TIRTA ANUGERAH') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F0F8A4]/30 min-h-screen flex items-center justify-center p-4 py-8">

        <!-- Bento Login Container -->
        <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Left Panel - Branding Bento Tile -->
            <div class="bg-[#36656B] rounded-2xl p-6 md:p-10 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 md:block">
                        <div class="w-11 h-11 md:w-14 md:h-14 bg-[#DAD887] rounded-xl flex items-center justify-center shrink-0 md:mb-8">
                            <svg class="w-6 h-6 md:w-8 md:h-8 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white md:mb-3">TIRTA ANUGERAH</h1>
                    </div>
                    <p class="text-[#F0F8A4] text-sm leading-relaxed hidden md:block mt-3">
                        Pencatatan Air Bersih Terpadu Cepat & Akurat
                    </p>
                </div>

                <!-- Stats Grid Mini: disembunyikan di HP agar ringkas -->
                <div class="hidden md:grid grid-cols-2 gap-3 mt-8">
                    <div class="bg-white/10 rounded-xl p-4">
                        <p class="text-[#DAD887] text-xs font-medium uppercase tracking-wide">Warga</p>
                        <p class="text-white text-2xl font-bold mt-1">Digital</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4">
                        <p class="text-[#DAD887] text-xs font-medium uppercase tracking-wide">Meteran</p>
                        <p class="text-white text-2xl font-bold mt-1">Akurat</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Login Form Bento Tile -->
            <div class="bg-white rounded-2xl p-6 md:p-10 flex flex-col justify-center">
                <h2 class="text-xl md:text-2xl font-bold text-[#36656B] mb-1">Masuk</h2>
                <p class="text-gray-500 text-sm mb-6 md:mb-8">Gunakan akun yang diberikan pengelola.</p>

                {{ $slot }}
            </div>
        </div>

    </body>
</html>
