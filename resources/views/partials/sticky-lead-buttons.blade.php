<div class="font-syne">

    {{-- Desktop & Tablet Floating Buttons (Right Side) --}}
    <div class="flex flex-col fixed right-0 top-1/2 -translate-y-1/2 z-90 space-y-2">

        {{-- Notice Board Button --}}
        <button type="button" id="open-notice-modal"
            class="group relative flex items-center justify-center w-8 sm:w-9 py-4 sm:py-4 bg-white text-[#92142c] rounded-l-xl shadow-[-5px_5px_15px_rgba(0,0,0,0.12)] transition-all duration-300 ease-in-out hover:bg-[#92142c] hover:text-white hover:pr-1.5 border border-r-0 border-gray-100 hover:border-transparent focus:outline-none overflow-hidden">

            {{-- Glowing Ping Indicator --}}
            <span class="absolute -top-1 -left-1 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#92142c] opacity-75 group-hover:bg-white group-hover:opacity-100 transition-colors"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#92142c] border-[1.5px] border-white group-hover:bg-white group-hover:border-[#92142c] transition-colors"></span>
            </span>

            <span class="block text-[8px] sm:text-[9px] font-bold tracking-tight uppercase"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Notice Board
            </span>
        </button>

        {{-- Apply Now Button (Desktop Only) --}}
        <button @click="openApply()" type="button"
            class="hidden md:flex group relative items-center justify-center w-9 py-4 bg-[#92142c] text-white rounded-l-xl shadow-[-5px_5px_15px_rgba(146,20,44,0.25)] transition-all duration-300 ease-in-out hover:bg-[#7a1024] hover:pr-1.5 focus:outline-none">
            <span class="block text-[8px] font-bold tracking-tight uppercase text-white/90 group-hover:text-white"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Apply Now
            </span>
        </button>

        {{-- Enquire Now Button (Desktop Only) --}}
        <button @click="openEnquire()" type="button"
            class="hidden md:flex group relative items-center justify-center w-9 py-4 bg-[#1a1a1a] text-white rounded-l-xl shadow-[-5px_5px_15px_rgba(0,0,0,0.15)] transition-all duration-300 ease-in-out hover:bg-gray-800 hover:pr-1.5 focus:outline-none">
            <span class="block text-[8px] font-bold tracking-tight uppercase text-white/90 group-hover:text-white"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Enquire Now
            </span>
        </button>

    </div>

    {{-- Mobile Bottom Sticky Buttons (Visible only on small screens) --}}
    <div class="md:hidden fixed bottom-1.5 left-1.5 right-1.5 z-95 flex gap-1.5">

        {{-- Enquire Now (Mobile) --}}
        <button @click="openEnquire()" type="button"
            class="flex-1 flex items-center justify-center gap-2 bg-white text-[#92142c] py-3 text-[9px] sm:text-[10px] font-bold uppercase tracking-tight hover:bg-gray-50 transition-colors border border-gray-100 rounded-xl shadow-lg focus:outline-none">
            <i class="bi bi-chat-dots-fill text-xs opacity-70"></i>
            Enquire
        </button>

        {{-- Apply Now (Mobile) --}}
        <button @click="openApply()" type="button"
            class="flex-1 flex items-center justify-center gap-2 bg-[#92142c] text-white py-3 text-[9px] sm:text-[10px] font-bold uppercase tracking-tight hover:bg-[#7a1024] transition-colors shadow-lg rounded-xl focus:outline-none">
            <i class="bi bi-box-arrow-in-right text-xs opacity-70"></i>
            Apply Now
        </button>

    </div>

</div>
