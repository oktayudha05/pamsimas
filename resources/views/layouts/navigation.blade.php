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
                    <span class="text-white font-bold text-lg tracking-tight">PAMSIMAS</span>
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
                            Data Warga
                        </a>
                        <a href="{{ route('petugases.index') }}"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                                  {{ request()->routeIs('petugases.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                            Data Petugas
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
                <button @click="open = ! open" class="p-2 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition duration-150">
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
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium
                      {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10' }}">
                Dashboard
            </a>
            @if (Auth::user()->role === 'pengelola')
                <a href="{{ route('wargas.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium
                          {{ request()->routeIs('wargas.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10' }}">
                    Data Warga
                </a>
                <a href="{{ route('petugases.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium
                          {{ request()->routeIs('petugases.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10' }}">
                    Data Petugas
                </a>
            @endif
            <a href="{{ route('pencatatans.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium
                      {{ request()->routeIs('pencatatans.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10' }}">
                Pencatatan Air
            </a>
            @if (Auth::user()->role === 'pengelola')
                <a href="{{ route('rekap.index') }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium
                          {{ request()->routeIs('rekap.index') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10' }}">
                    Rekap Laporan
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
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-300 hover:bg-white/10 transition">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
