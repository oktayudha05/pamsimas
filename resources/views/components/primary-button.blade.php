<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold rounded-xl transition-all duration-150 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
