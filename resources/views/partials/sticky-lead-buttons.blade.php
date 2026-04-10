<div class="font-syne">

    {{-- Desktop & Tablet Floating Buttons (Right Side) --}}
    <div class="flex flex-col fixed right-0 top-1/2 -translate-y-1/2 z-90 space-y-2">

        {{-- Notice Board Button --}}
        <button type="button" id="open-notice-modal"
            class="group relative flex items-center justify-center w-10 py-5 bg-[#FFD700] text-[#1E234B] rounded-l-xl shadow-[-5px_5px_15px_rgba(255,215,0,0.25)] transition-all duration-300 ease-in-out hover:bg-[#ffc800] hover:pr-1.5 border border-r-0 border-yellow-200 hover:border-transparent focus:outline-none overflow-hidden">

            {{-- Glowing Ping Indicator --}}
            <span class="absolute -top-1 -left-1 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1E234B] opacity-75 group-hover:bg-[#1E234B] group-hover:opacity-100 transition-colors"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#1E234B] border-[1.5px] border-white group-hover:bg-[#1E234B] group-hover:border-white transition-colors"></span>
            </span>

            <span class="block text-[10px] sm:text-[11px] font-bold tracking-tight uppercase"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Notice Section Wise
            </span>
        </button>

        {{-- Apply Now Button (Desktop Only) --}}
        <button @click="openApply()" type="button"
            class="hidden md:flex group relative items-center justify-center w-10 py-5 bg-[#FFD700] text-[#1E234B] rounded-l-xl shadow-[-5px_5px_15px_rgba(255,215,0,0.25)] transition-all duration-300 ease-in-out hover:bg-[#ffc800] hover:pr-1.5 focus:outline-none">
            <span class="block text-[10px] font-bold tracking-tight uppercase text-[#1E234B]/90 group-hover:text-[#1E234B]"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                Admission
            </span>
        </button>

        {{-- Enquire Now Button (Desktop Only) --}}
        <button @click="openEnquire()" type="button"
            class="hidden md:flex group relative items-center justify-center w-10 py-5 bg-[#1E234B] text-white rounded-l-xl shadow-[-5px_5px_15px_rgba(30,35,75,0.15)] transition-all duration-300 ease-in-out hover:bg-[#141835] hover:pr-1.5 focus:outline-none">
            <span class="block text-[10px] font-bold tracking-tight uppercase text-white/90 group-hover:text-white"
                style="writing-mode: vertical-rl; transform: rotate(180deg);">
                General Enquiry
            </span>
        </button>

    </div>

    {{-- Mobile Bottom Sticky Buttons (Visible only on small screens) --}}
    <div class="md:hidden fixed bottom-1.5 left-1.5 right-1.5 z-95 flex gap-1.5">

        {{-- Enquire Now (Mobile) --}}
        <button @click="openEnquire()" type="button"
            class="flex-1 flex items-center justify-center gap-2 bg-[#1E234B] text-white py-3.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-tight hover:bg-[#141835] transition-colors rounded-xl shadow-lg focus:outline-none">
            <i class="bi bi-chat-dots-fill text-xs opacity-80"></i>
            General Enquiry
        </button>
        {{-- Apply Now (Mobile) --}}
        <button @click="openApply()" type="button"
            class="flex-1 flex items-center justify-center gap-2 bg-[#FFD700] text-[#1E234B] py-3.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-tight hover:bg-[#ffc800] transition-colors shadow-lg rounded-xl focus:outline-none">
            <i class="bi bi-box-arrow-in-right text-xs"></i>
            Admission
        </button>

    </div>

</div>
