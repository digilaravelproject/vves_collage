<div class="slider-track mb-8 group">
    <div id="scroll-wrapper" class="scroll-container-wrapper">
        {{-- Unified Navigation Arrows --}}
        <button onclick="scrollCategories(-250)" id="btn-left"
            class="absolute left-2 top-1/2 -translate-y-1/2 z-30 w-10 h-10 bg-white border border-gray-100 rounded-full shadow-[0_4px_15px_rgba(0,0,0,0.1)] items-center justify-center text-[#1E234B] hover:bg-[#000165] hover:text-white transition-all transform hover:scale-110 hidden md:flex opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto">
            <i class="bi bi-chevron-left text-lg"></i>
        </button>
        <button onclick="scrollCategories(250)" id="btn-right"
            class="absolute right-2 top-1/2 -translate-y-1/2 z-30 w-10 h-10 bg-white border border-gray-100 rounded-full shadow-[0_4px_15px_rgba(0,0,0,0.1)] items-center justify-center text-[#1E234B] hover:bg-[#000165] hover:text-white transition-all transform hover:scale-110 hidden md:flex opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto">
            <i class="bi bi-chevron-right text-lg"></i>
        </button>

        <div id="category-scroll"
            class="flex items-center justify-start gap-3 overflow-x-auto whitespace-nowrap py-1 px-4 cursor-grab select-none active:cursor-grabbing pb-3">

            {{--
            ========================================================================
            TAB BUTTON ORDER
            ========================================================================
            You can easily reorder tabs by moving these blocks up or down.
            Whatever order you place these buttons here, that is the order they
            will appear in the scrolling tab bar.
            ========================================================================
            --}}

            {{-- 1. About School Tab --}}
            @if (!empty(trim(strip_tags($institution->institutional_journey))))
                <button @click="activeTab = 'about'"
                    :class="activeTab === 'about' ?
                                    'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' :
                                    'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                    class="shrink-0 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                    <i class="bi bi-info-circle me-2"></i>About School
                </button>
            @endif

            {{-- Academic Calendar (PDF) Tab --}}
            @if ($institution->academic_diary_pdf)
                <button @click="activeTab = 'academic_calendar'"
                    :class="activeTab === 'academic_calendar' ?
                                    'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' :
                                    'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                    class="shrink-0 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                    <i class="bi bi-calendar3 me-2"></i>Academic Calendar
                </button>
            @endif

            {{-- 3. Results & Awards Tab --}}
            @if ($institution->results_awards && count($institution->results_awards) > 0)
                <button @click="activeTab = 'results_awards'"
                    :class="activeTab === 'results_awards' ?
                                    'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' :
                                    'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                    class="shrink-0 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                    <i class="bi bi-trophy me-2"></i>Results & Awards
                </button>
            @endif

            {{-- 4. Activities & Facilities Tab --}}
            @if ($institution->activities_facilities_blocks && count($institution->activities_facilities_blocks) > 0)
                <button @click="activeTab = 'activities'"
                    :class="activeTab === 'activities' ?
                                    'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' :
                                    'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                    class="shrink-0 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Activities & Facilities
                </button>
            @endif
        </div>
    </div>
</div>
