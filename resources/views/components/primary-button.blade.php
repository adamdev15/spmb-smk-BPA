<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#2491CA] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1D80B5] focus:bg-[#1D80B5] active:bg-[#1976A8] focus:outline-none focus:ring-2 focus:ring-[#2491CA] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
