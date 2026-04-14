<div id="pane-activities" x-show="activeTab === 'activities'" x-transition.opacity.duration.400ms
    x-data="{ openSection: 0 }" style="display: none;">

    <div class="flex items-center gap-3 mb-6 md:mb-10">
        <div class="w-1.5 h-6 md:h-8 bg-[#FFD700] rounded-sm"></div>
        <h2 class="text-xl md:text-3xl font-black text-[#1E234B] tracking-tight uppercase">Activities & Facilities</h2>
    </div>

    <div class="space-y-3 md:space-y-4">
        @php
            $blocks = is_array($institution->activities_facilities_blocks)
                ? $institution->activities_facilities_blocks
                : json_decode($institution->activities_facilities_blocks, true) ?? [];
        @endphp

        @forelse($blocks as $index => $block)
            <div class="bg-white rounded-xl md:rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-500 hover:shadow-md"
                :class="openSection === {{ $index }} ? 'ring-2 ring-[#FFD700]/30 border-transparent shadow-xl' : ''">
                
                {{-- Accordion Trigger --}}
                <button
                    @click="openSection = (openSection === {{ $index }} ? -1 : {{ $index }})"
                    class="w-full px-4 md:px-8 py-4 md:py-6 flex items-center justify-between text-left hover:bg-gray-50/50 transition-all group">
                    
                    <span class="font-black text-[#1E234B] text-[13px] md:text-base flex items-center gap-2 md:gap-4 tracking-wide uppercase">
                        <span
                            class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center rounded-lg bg-gray-50 text-[#1E234B] text-[10px] md:text-xs group-hover:bg-[#000165] group-hover:text-[#FFD700] transition-colors"
                            x-text="'0'+({{ $index }} + 1)"></span>
                        {{ $block['title'] }}
                    </span>

                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-50 flex items-center justify-center transition-transform duration-500"
                        :class="openSection === {{ $index }} ? 'rotate-180 bg-[#000165] text-white' : 'text-gray-400'">
                        <i class="bi bi-chevron-down text-xs md:text-sm"></i>
                    </div>
                </button>

                {{-- Accordion Content --}}
                <div x-show="openSection === {{ $index }}" x-collapse>
                    <div class="px-5 md:px-12 pb-6 md:pb-10 pt-2 prose prose-sm md:prose-base max-w-none text-gray-600 leading-relaxed font-medium">
                        <div class="w-full h-px bg-linear-to-r from-gray-100 via-transparent to-transparent mb-4 md:mb-6"></div>
                        <div class="rich-text-content">
                            {!! $block['content'] !!}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                    <i class="bi bi-layers text-3xl text-gray-200"></i>
                </div>
                <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No activities or facilities listed yet.</p>
            </div>
        @endforelse
    </div>
</div>
