@php
    $resultsAwards = is_array($institution->results_awards)
        ? $institution->results_awards
        : json_decode($institution->results_awards, true) ?? [];
@endphp

@if (!empty($resultsAwards))
    <div id="pane-results-awards" x-show="activeTab === 'results_awards'" x-transition.opacity.duration.400ms x-data="{ openSection: 0 }" style="display: none;">
        <div class="flex items-center gap-3 mb-6 md:mb-10">
            <div class="w-1.5 h-6 md:h-8 bg-[#FFD700] rounded-sm"></div>
            <h2 class="text-xl md:text-3xl font-black text-[#1E234B] tracking-tight uppercase">Results & Awards</h2>
        </div>

        <div class="space-y-3 md:space-y-4">
            @foreach ($resultsAwards as $index => $section)
                <div class="bg-white rounded-xl md:rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-500 hover:shadow-md"
                    :class="openSection === {{ $index }} ? 'ring-2 ring-[#FFD700]/30 border-transparent shadow-xl' : ''">
                    <button @click="openSection = (openSection === {{ $index }} ? -1 : {{ $index }})"
                        class="w-full px-4 md:px-8 py-4 md:py-6 flex items-center justify-between text-left hover:bg-gray-50/50 transition-all group">
                        <span class="font-black text-[#1E234B] text-[13px] md:text-base flex items-center gap-2 md:gap-4 tracking-wide uppercase">
                            <span class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center rounded-lg bg-gray-50 text-[#FFD700] text-[10px] md:text-xs group-hover:bg-[#000165] group-hover:text-white transition-colors"
                                x-text="String({{ $index }} + 1).padStart(2, '0')"></span>
                            {{ $section['title'] }}
                        </span>
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-50 flex items-center justify-center transition-transform duration-500"
                            :class="openSection === {{ $index }} ? 'rotate-180 bg-[#000165] text-white' : 'text-gray-400'">
                            <i class="bi bi-chevron-down text-xs md:text-sm"></i>
                        </div>
                    </button>
                    <div x-show="openSection === {{ $index }}" x-collapse>
                        <div class="px-5 md:px-12 pb-6 md:pb-10 pt-2">
                            <div class="w-full h-px bg-linear-to-r from-gray-100 via-transparent to-transparent mb-6"></div>
                            
                            <div class="grid grid-cols-1 gap-8">
                                @foreach ($section['items'] as $item)
                                    @if (($item['type'] ?? 'result') === 'result')
                                        {{-- Result Item Card --}}
                                        <div class="bg-white border border-gray-100 rounded-[32px] overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 group" x-data="{ expanded: false }">
                                            <div class="flex flex-col md:flex-row">
                                                {{-- Left side image (optional) --}}
                                                @if (!empty($item['photo']))
                                                    <div class="md:w-1/3 aspect-4/3 md:aspect-auto overflow-hidden border-b md:border-b-0 md:border-r border-gray-100">
                                                        <img src="{{ asset('storage/' . $item['photo']) }}"
                                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $item['title'] }}">
                                                    </div>
                                                @endif
                                                
                                                {{-- Right/Full info side --}}
                                                <div class="flex-1 p-8 md:p-10 flex flex-col justify-center">
                                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                                        <span class="w-8 h-8 rounded-full bg-[#000165]/10 text-[#000165] flex items-center justify-center shrink-0">
                                                            <i class="bi bi-trophy-fill"></i>
                                                        </span>
                                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest shrink-0">Result</span>
                                                        
                                                        <div class="flex items-center gap-2 ml-auto">
                                                            @if (!empty($item['medium']))
                                                                <span class="text-gray-400 text-[9px] font-bold uppercase tracking-widest shrink-0">{{ $item['medium'] }}</span>
                                                            @endif
                                                            @if (!empty($item['year']))
                                                                <span class="px-3 py-1 bg-[#FFD700] text-[#000165] rounded-md text-[10px] font-black uppercase tracking-widest shrink-0">{{ $item['year'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <h3 class="text-2xl md:text-3xl font-black text-[#000165] mb-4 leading-tight">{{ $item['title'] }}</h3>
                                                    
                                                    @if (!empty($item['overall_result']))
                                                        <div class="mb-4 inline-flex flex-col items-start px-5 py-3 bg-[#e8f7ec] border border-[#bceac9] rounded-2xl">
                                                            <p class="text-[9px] font-black text-[#1b6b36] uppercase tracking-widest mb-1.5 opacity-80">Overall Performance</p>
                                                            <h4 class="text-2xl font-black text-[#1b6b36]">{{ $item['overall_result'] }}</h4>
                                                        </div>
                                                    @endif
                                                    
                                                    @if (!empty($item['summary']))
                                                        <div class="relative mt-2">
                                                            <p class="text-gray-600 leading-relaxed font-syne text-sm md:text-base italic" :class="expanded ? '' : 'line-clamp-3'">
                                                                {!! nl2br(e($item['summary'])) !!}
                                                            </p>
                                                            @if (strlen($item['summary']) > 150)
                                                                <button @click="expanded = !expanded" class="text-[10px] font-black text-[#000165] uppercase tracking-widest mt-4 hover:underline focus:outline-none flex items-center gap-1">
                                                                    <span x-text="expanded ? 'Show Less' : 'Read More'"></span>
                                                                    <i class="bi bi-arrow-right"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Students Grid (Bottom Part) --}}
                                            @if (!empty($item['students']))
                                                <div class="border-t border-gray-100 bg-[#F8F9FA] p-8 md:p-10">
                                                    <div class="flex items-center justify-between mb-8">
                                                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Merit List / Toppers</h4>
                                                        <div class="h-px bg-gray-200 flex-1 mx-4"></div>
                                                    </div>

                                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                                        @foreach ($item['students'] as $student)
                                                            <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#FFD700] transition-all duration-300 group/student">
                                                                <div class="shrink-0 w-16 h-16 rounded-xl overflow-hidden border border-gray-100 shadow-sm relative">
                                                                    <img src="{{ !empty($student['photo']) ? asset('storage/' . $student['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($student['name']) . '&size=100&background=000165&color=fff' }}"
                                                                        class="w-full h-full object-cover group-hover/student:scale-110 transition-transform duration-500">
                                                                    <div class="absolute inset-0 bg-black/0 group-hover/student:bg-black/5 transition-colors"></div>
                                                                </div>
                                                                <div class="min-w-0 flex-1">
                                                                    <h5 class="text-sm font-black text-[#1E234B] truncate" title="{{ $student['name'] }}">{{ $student['name'] }}</h5>
                                                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tight mb-1">{{ $student['class'] ?? 'Student' }}</p>
                                                                    <p class="text-xl font-black text-[#FFD700] drop-shadow-sm leading-none">{{ $student['percentage'] }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        {{-- Award Item Card --}}
                                        <div class="bg-white border border-gray-100 rounded-[32px] overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 group" x-data="{ expanded: false }">
                                            <div class="flex flex-col md:flex-row">
                                                @if (!empty($item['photo']))
                                                    <div class="md:w-1/3 aspect-4/3 md:aspect-auto overflow-hidden">
                                                        <img src="{{ asset('storage/' . $item['photo']) }}"
                                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $item['title'] }}">
                                                    </div>
                                                @endif
                                                <div class="flex-1 p-8 md:p-10">
                                                    <div class="flex items-center gap-3 mb-4">
                                                        <span class="w-8 h-8 rounded-full bg-[#FFD700]/10 text-[#FFD700] flex items-center justify-center">
                                                            <i class="bi bi-patch-check-fill"></i>
                                                        </span>
                                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Award / Achievement</span>
                                                    </div>
                                                    <h3 class="text-2xl md:text-3xl font-black text-[#000165] mb-4 leading-tight">{{ $item['title'] }}</h3>
                                                    @if (!empty($item['summary']))
                                                        <div class="relative">
                                                            <p class="text-gray-600 leading-relaxed font-syne text-sm md:text-base italic" :class="expanded ? '' : 'line-clamp-3'">
                                                                {!! nl2br(e($item['summary'])) !!}
                                                            </p>
                                                            @if (strlen($item['summary']) > 200)
                                                                <button @click="expanded = !expanded" class="text-[10px] font-black text-[#000165] uppercase tracking-widest mt-4 hover:underline focus:outline-none">
                                                                    <span x-text="expanded ? 'Show Less' : 'Read More'"></span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
