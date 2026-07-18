<nav x-data="{ open: false }" class="bg-[#36656B] sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo + Nav Links -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-[#DAD887] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#36656B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg tracking-tight">TIRTA ANUGERAH</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        Dashboard
                    </a>
                    @if (Auth::user()->role === 'pengelola')
                        <a href="{{ route('wargas.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                                  {{ request()->routeIs('wargas.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                            Daftar Rumah
                        </a>
                        <a href="{{ route('akuns.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                                  {{ request()->routeIs('akuns.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                            Manajemen Akun
                        </a>
                        <a href="{{ route('keuangan.index') }}"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                                {{ request()->routeIs('keuangan.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                            Keuangan
                        </a>
                        
                    @endif
                    <a href="{{ route('pencatatans.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('pencatatans.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        Pencatatan Air
                    </a>
                    
                    @if (Auth::user()->role === 'pengelola')
                        <a href="{{ route('rekap.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                                  {{ request()->routeIs('rekap.index') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                            Rekap Laporan
                        </a>
                    @endif
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex items-center gap-3">
                <!-- Role Badge -->
                <span class="bg-[#DAD887] text-[#36656B] text-xs font-semibold px-2.5 py-1 rounded-lg uppercase tracking-wide">
                    {{ Auth::user()->role }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150">
                            <div class="w-6 h-6 bg-[#75B06F] rounded-md flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->nama }}</span>
                            <svg class="w-4 h-4 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-3 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/20">
        <div class="px-4 py-3 space-y-1.5">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>
            @if (Auth::user()->role === 'pengelola')
                <a href="{{ route('wargas.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('wargas.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Daftar Rumah</span>
                </a>
                <a href="{{ route('akuns.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('akuns.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Manajemen Akun</span>
                </a>
            @endif
            <a href="{{ route('pencatatans.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('pencatatans.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Pencatatan Air</span>
            </a>
            @if (Auth::user()->role === 'pengelola')
                <a href="{{ route('rekap.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('rekap.index') ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Rekap Laporan</span>
                </a>
            @endif
        </div>

        <!-- Responsive Settings -->
        <div class="px-4 py-3 border-t border-white/20">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-[#75B06F] rounded-lg flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
                <div>
                    <div class="text-white text-sm font-medium">{{ Auth::user()->nama }}</div>
                    <div class="text-white/60 text-xs">{{ Auth::user()->role }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 rounded-lg text-sm text-red-300 hover:bg-white/10 transition inline-flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Lapor / Keluar</span>
                </button>
            </form>
        </div>
    </div>
</nav>
