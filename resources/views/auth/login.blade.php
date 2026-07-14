<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 bg-[#F0F8A4] text-[#36656B] text-sm rounded-lg px-4 py-3 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Username -->
        <div>
            <label for="username" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                Username
            </label>
            <input id="username"
                   type="text"
                   name="username"
                   value="{{ old('username') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="Masukkan username..."
                   class="w-full px-4 py-3 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150">
            @error('username')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                Password
            </label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full px-4 py-3 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition-all duration-150">
            @error('password')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                   class="rounded border-[#DAD887] text-[#36656B] focus:ring-[#36656B]">
            <label for="remember_me" class="text-sm text-gray-600">Ingat saya</label>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-3 px-6 rounded-xl
                       transition-all duration-150 text-sm tracking-wide shadow-sm hover:shadow-md">
            Masuk
        </button>
    </form>
</x-guest-layout>
