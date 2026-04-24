<div id="pane-about" x-show="activeTab === 'about'" x-transition.opacity.duration.400ms x-data="{ openSection: 0 }"
    style="display: none;">

    <div class="flex items-center gap-3 mb-6 md:mb-10">
        <div class="w-1.5 h-6 md:h-8 bg-[#FFD700] rounded-sm"></div>
        <h2 class="text-xl md:text-3xl font-black text-[#1E234B] tracking-tight uppercase">About
            Institution</h2>
    </div>

    <div class="space-y-3 md:space-y-6">
        @php
            $journey = trim(strip_tags($institution->institutional_journey));
            $sections = is_array($institution->about_sections)
                ? $institution->about_sections
                : json_decode($institution->about_sections, true) ?? [];
        @endphp

        {{-- 1. Legacy Institutional Journey (If exists) --}}
        @if (!empty($journey))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-500 hover:shadow-md ring-2 ring-[#FFD700]/10"
                x-data="{ isOpen: true }">
                <button @click="isOpen = !isOpen"
                    class="w-full px-6 md:px-10 py-5 md:py-7 flex items-center justify-between text-left hover:bg-gray-50/30 transition-all group">
                    <span
                        class="font-black text-[#1E234B] text-sm md:text-lg flex items-center gap-4 tracking-wide uppercase">
                        <span
                            class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-xl bg-[#1E234B] text-white text-xs md:text-sm shadow-lg shadow-blue-900/20">
                            <i class="bi bi-star-fill"></i>
                        </span>
                        Institutional Journey
                    </span>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-50 flex items-center justify-center transition-transform duration-500"
                        :class="isOpen ? 'rotate-180 bg-[#1E234B] text-white' : 'text-gray-400'">
                        <i class="bi bi-chevron-down text-xs md:text-sm"></i>
                    </div>
                </button>
                <div x-show="isOpen" x-collapse>
                    <div
                        class="px-6 md:px-12 pb-8 md:pb-12 pt-0 prose-sm md:prose-base max-w-none text-gray-600 leading-relaxed">
                        <div class="w-full h-px bg-linear-to-r from-gray-100 via-transparent to-transparent mb-6"></div>
                        <div class="rich-text-content">
                            {!! $institution->institutional_journey !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 2. Dynamic About Sections --}}
        @foreach ($sections as $index => $section)
            @php
                $secIndex = !empty($journey) ? $index + 1 : $index;
            @endphp
            <div class="bg-white rounded-xl md:rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-500 hover:shadow-md"
                :class="openSection === {{ $index }} ? 'ring-2 ring-[#FFD700]/30 border-transparent shadow-xl' : ''">
                <button @click="openSection = (openSection === {{ $index }} ? -1 : {{ $index }})"
                    class="w-full px-4 md:px-8 py-4 md:py-6 flex items-center justify-between text-left hover:bg-gray-50/50 transition-all group">
                    <span
                        class="font-black text-[#1E234B] text-[13px] md:text-base flex items-center gap-2 md:gap-4 tracking-wide uppercase">
                        <span
                            class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center rounded-lg bg-gray-50 text-[#1E234B] text-[10px] md:text-xs group-hover:bg-[#000165] group-hover:text-[#FFD700] transition-colors">
                            <i class="bi bi-arrow-right-short text-lg"></i>
                        </span>
                        {{ $section['title'] }}
                    </span>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-50 flex items-center justify-center transition-transform duration-500"
                        :class="openSection === {{ $index }} ? 'rotate-180 bg-[#000165] text-white' : 'text-gray-400'">
                        <i class="bi bi-chevron-down text-xs md:text-sm"></i>
                    </div>
                </button>
                <div x-show="openSection === {{ $index }}" x-collapse>
                    <div
                        class="px-5 md:px-12 pb-6 md:pb-10 pt-2 prose prose-sm md:prose-base max-w-none text-gray-600 leading-relaxed font-medium">
                        <div class="w-full h-px bg-linear-to-r from-gray-100 via-transparent to-transparent mb-4 md:mb-6">
                        </div>
                        <div class="rich-text-content">
                            {!! $section['content'] !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if (empty($journey) && count($sections) === 0)
            <div class="py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                <div
                    class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                    <i class="bi bi-journal-x text-3xl text-gray-200"></i>
                </div>
                <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No detailed sections found for this
                    campus.</p>
            </div>
        @endif
    </div>
</div>
