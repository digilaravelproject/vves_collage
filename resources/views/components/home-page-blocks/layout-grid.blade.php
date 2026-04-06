@php
    $columns = $block['columns'] ?? [];
    $title = $block['title'] ?? '';
@endphp

<div class="relative overflow-hidden w-full">
    @if (!empty($title))
        {{-- Section Header (Standardized) --}}
        <div class="mb-0 text-center" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-2">
                {{ $title }}
            </h2>
            <div class="w-16 h-1 bg-vves-primary rounded-full mx-auto mb-10"></div>
        </div>
    @endif

       {{-- Grid Columns --}}
       <div class="flex flex-wrap -mx-3 gap-y-8 lg:gap-y-0 text-left">
            @foreach ($columns as $col)
                @php
                    $span = $col['span'] ?? 12; // Default 12
                    $childBlocks = $col['blocks'] ?? [];

                    $wClass = 'w-full';
                    if ($span == 12) $wClass = 'w-full';
                    else if ($span == 6) $wClass .= ' lg:w-1/2';
                    else if ($span == 4) $wClass .= ' lg:w-1/3';
                    else if ($span == 8) $wClass .= ' lg:w-2/3';
                    else if ($span == 3) $wClass .= ' lg:w-1/4';
                    else if ($span == 9) $wClass .= ' lg:w-3/4';
                    else if ($span == '2.4' || $span == 2.4) $wClass .= ' lg:w-1/5';
                    else if ($span == 2) $wClass .= ' lg:w-1/6';
                    else if ($span == 10) $wClass .= ' lg:w-5/6';
                    else $wClass .= ' lg:w-' . $span . '/12'; // Fallback
                @endphp

                <div class="px-3 {{ $wClass }}">
                    <div class="space-y-6 h-full">
                        @foreach ($childBlocks as $childBlock)
                            <x-home-page-block :block="$childBlock" />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
