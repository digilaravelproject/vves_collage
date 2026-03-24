<div class="font-roboto">

    {{-- Desktop & Tablet Floating Buttons (Right Side) --}}
    <div class="flex flex-col fixed right-0 top-1/2 -translate-y-1/2 z-[90] space-y-3">

        {{-- Notice Board Button --}}
        <button type="button" id="open-notice-modal"
            class="group relative flex items-center justify-center w-10 sm:w-11 py-5 sm:py-6 bg-white text-[#92142c] rounded-l-2xl shadow-[-5px_5px_20px_rgba(0,0,0,0.15)] transition-all duration-300 ease-in-out hover:bg-[#92142c] hover:text-white hover:pr-2 border border-r-0 border-gray-100 hover:border-transparent focus:outline-none">

            {{-- Glowing Ping Indicator --}}
            <span class="absolute -top-1.5 -left-1.5 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#92142c] opacity-75 group-hover:bg-white group-hover:opacity-100 transition-colors"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-[#92142c] border-2 border-white group-hover:bg-white group-hover:border-[#92142c] transition-colors"></span>
            </span>

            <span class="block text-[12px] sm:text-[13px] font-black tracking-widest uppercase"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Notice Board
            </span>
        </button>

        {{-- Apply Now Button (Desktop Only) --}}
        <button @click="openApply()" type="button"
            class="hidden md:flex group relative items-center justify-center w-11 py-6 bg-[#92142c] text-white rounded-l-2xl shadow-[-5px_5px_20px_rgba(146,20,44,0.3)] transition-all duration-300 ease-in-out hover:bg-[#7a1024] hover:pr-2 focus:outline-none">
            <span class="block text-[13px] font-black tracking-widest uppercase text-white/90 group-hover:text-white"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Apply Now
            </span>
        </button>

        {{-- Enquire Now Button (Desktop Only) --}}
        <button @click="openEnquire()" type="button"
            class="hidden md:flex group relative items-center justify-center w-11 py-6 bg-[#1a1a1a] text-white rounded-l-2xl shadow-[-5px_5px_20px_rgba(0,0,0,0.2)] transition-all duration-300 ease-in-out hover:bg-gray-800 hover:pr-2 focus:outline-none">
            <span class="block text-[13px] font-black tracking-widest uppercase text-white/90 group-hover:text-white"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Enquire Now
            </span>
        </button>

    </div>

    {{-- Mobile Bottom Sticky Buttons (Visible only on small screens) --}}
    <div class="md:hidden fixed bottom-0 left-0 w-full z-[95] flex shadow-[0_-10px_20px_rgba(0,0,0,0.1)] bg-white">

        {{-- Enquire Now (Mobile) --}}
        <button @click="openEnquire()" type="button"
            class="flex-1 flex items-center justify-center gap-2 bg-white text-[#92142c] py-3.5 text-xs font-black uppercase tracking-wider hover:bg-gray-50 transition-colors border-t border-r border-gray-200 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Enquire
        </button>

        {{-- Apply Now (Mobile) --}}
        <button @click="openApply()" type="button"
            class="flex-1 flex items-center justify-center gap-2 bg-[#92142c] text-white py-3.5 text-xs font-black uppercase tracking-wider hover:bg-[#7a1024] transition-colors shadow-[0_-2px_10px_rgba(146,20,44,0.3)] z-10 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            Apply Now
        </button>

    </div>

</div>
