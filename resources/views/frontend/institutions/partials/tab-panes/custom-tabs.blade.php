@php
    $customTabs = is_array($institution->custom_tabs)
        ? $institution->custom_tabs
        : json_decode($institution->custom_tabs, true) ?? [];
@endphp

@foreach($customTabs as $tIdx => $tab)
    @php
        $tab = (array) $tab;
        $isActive = !empty($tab['is_active']);
        $items = $tab['items'] ?? [];
    @endphp

    @if($isActive)
        <div id="pane-custom-{{ $tIdx }}" x-show="activeTab === 'custom_{{ $tIdx }}'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            style="display: none;">
            
            {{-- Premium Header --}}
            <div class="relative mb-12 md:mb-16">
                <div class="flex items-center gap-4">
                    <div class="w-2 h-10 bg-linear-to-b from-[#000165] to-blue-400 rounded-full shadow-lg"></div>
                    <h2 class="text-3xl md:text-5xl font-black text-[#1E234B] tracking-tight uppercase leading-none">{{ $tab['title'] ?? 'Section' }}</h2>
                </div>
            </div>

            <div class="space-y-16 md:space-y-24">
                {{-- Introduction Section --}}
                @if(!empty($tab['intro']))
                    <section class="relative group">
                        <div class="absolute -top-10 -left-10 w-64 h-64 bg-blue-50/50 rounded-full blur-3xl -z-10 opacity-70"></div>
                        <div class="bg-white rounded-[40px] border border-gray-100 shadow-[0_32px_64px_-16px_rgba(0,1,101,0.08)] overflow-hidden relative p-8 md:p-16 hover:shadow-[0_48px_96px_-24px_rgba(0,1,101,0.12)] transition-shadow duration-700">
                            <div class="prose prose-blue max-w-none text-gray-600 font-syne text-lg md:text-xl leading-relaxed">
                                {!! $tab['intro'] !!}
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Blocks / Items --}}
                @if(!empty($items))
                    <section>
                        <div class="grid grid-cols-1 gap-10">
                            @foreach($items as $item)
                                @php $item = (array) $item; @endphp
                                <div class="group bg-white rounded-[40px] p-8 md:p-12 border border-gray-100 shadow-[0_10px_40px_rgba(0,1,101,0.03)] hover:shadow-[0_30px_70px_rgba(0,1,101,0.1)] transition-all duration-700 flex flex-col lg:flex-row gap-12 items-start hover:-translate-y-2">
                                    @if(!empty($item['photo']))
                                        <div class="w-full lg:w-[400px] shrink-0 rounded-[32px] overflow-hidden shadow-2xl ring-1 ring-black/5 bg-gray-50 aspect-4/3 relative">
                                            <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                            <div class="absolute inset-0 bg-linear-to-tr from-black/20 to-transparent"></div>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 space-y-6 w-full py-2">
                                        @if(!empty($item['title']))
                                            <div class="inline-flex items-center gap-3">
                                                <div class="w-1.5 h-6 bg-[#FFD700] rounded-full"></div>
                                                <h3 class="text-2xl md:text-3xl font-black text-[#1E234B] tracking-tight group-hover:text-[#000165] transition-colors">{{ $item['title'] }}</h3>
                                            </div>
                                        @endif
                                        
                                        @if(!empty($item['description']))
                                            <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed text-base md:text-lg font-medium opacity-90">
                                                {!! $item['description'] !!}
                                            </div>
                                        @endif

                                        <div class="pt-4 flex flex-wrap gap-4">
                                            {{-- Future-proof for CTA buttons if needed --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    @endif
@endforeach
