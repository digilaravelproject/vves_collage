@php
    $items = $block['items'] ?? [];
    $columnsCount = $block['columns_count'] ?? 3;
    $sectionTitle = $block['section_title'] ?? null;
    
    // Determine grid classes based on column count
    $gridCols = match((int)$columnsCount) {
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 md:grid-cols-3', // Default to 3
    };
@endphp

<div class="py-4">
    @if($sectionTitle)
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-[#1E234B] relative inline-block pb-2">
                {{ $sectionTitle }}
                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-16 h-1 bg-[#FFD700] rounded-full"></span>
            </h2>
        </div>
    @endif

    <div class="grid {{ $gridCols }} gap-6 lg:gap-8">
        @foreach($items as $item)
            <div class="group flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                {{-- Image Container --}}
                @if(!empty($item['image']))
                    <div class="relative aspect-video overflow-hidden bg-gray-50">
                        <img src="{{ $item['image'] }}" 
                             alt="{{ $item['title'] ?? 'Feature Image' }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                @endif

                {{-- Content --}}
                <div class="flex-1 p-5 flex flex-col items-center text-center">
                    @if(!empty($item['title']))
                        <h3 class="text-xl font-bold text-[#1E234B] mb-2 group-hover:text-blue-700 transition-colors">
                            {{ $item['title'] }}
                        </h3>
                    @endif

                    @if(!empty($item['caption']))
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $item['caption'] }}
                        </p>
                    @endif

                    @if(!empty($item['button_text']) && !empty($item['button_url']))
                        <div class="mt-auto pt-2">
                            <a href="{{ $item['button_url'] }}" 
                               class="inline-flex items-center px-5 py-2 rounded-full font-bold text-sm bg-[#1E234B] text-white transition-all duration-300 hover:bg-[#FFD700] hover:text-[#1E234B] shadow-md hover:shadow-lg active:scale-95">
                                {{ $item['button_text'] }}
                                <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
