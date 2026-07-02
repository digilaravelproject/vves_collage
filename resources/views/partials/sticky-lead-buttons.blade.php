<div class="font-syne">

    {{-- Desktop & Tablet Floating Buttons (Right Side) --}}
    <div class="flex flex-col fixed right-0 top-1/2 -translate-y-1/2 z-90 space-y-2">

        {{-- Notice Board Button --}}
        <button type="button" id="open-notice-modal"
            class="group relative flex items-center justify-center w-8 md:w-10 py-3 md:py-5 bg-[#FFD700] text-[#1E234B] rounded-l-xl shadow-[-5px_5px_15px_rgba(255,215,0,0.25)] transition-all duration-300 ease-in-out hover:bg-[#ffc800] hover:pr-1.5 border border-r-0 border-yellow-200 hover:border-transparent focus:outline-none overflow-hidden">

            {{-- Glowing Ping Indicator --}}
            <span class="absolute -top-1 -left-1 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1E234B] opacity-75 group-hover:bg-[#1E234B] group-hover:opacity-100 transition-colors"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#1E234B] border-[1.5px] border-white group-hover:bg-[#1E234B] group-hover:border-white transition-colors"></span>
            </span>

            {{-- Mobile Bell Icon --}}
            <span class="block md:hidden">
                <svg class="w-4 h-4 text-[#1E234B]" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                </svg>
            </span>

            {{-- Desktop Vertical Text --}}
            <span class="hidden md:block text-[10px] sm:text-[11px] font-bold tracking-tight uppercase"
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
