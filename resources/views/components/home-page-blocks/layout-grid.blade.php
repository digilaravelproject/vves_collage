@php
    $columns = $block['columns'] ?? [];
    $title = $block['title'] ?? '';
@endphp

<section class="w-full py-8 md:py-12 {{ $loop && $loop->even ? 'bg-gray-50' : 'bg-white' }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        {{-- @if (!empty($title))
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl mb-2 text-center">
                {{ $title }}
            </h2>
            <div class="text-center">
                <span class="inline-block px-4 py-1 rounded-full bg-blue-600/10 text-blue-600 text-xs font-bold uppercase tracking-widest mb-6">{{ $title }}</span>
            </div>
        @endif --}}
       <div class="flex flex-wrap -mx-3 gap-y-6 lg:gap-y-0 text-left">
            @foreach ($columns as $col)
                @php
                    $span = $col['span'] ?? 12; // Default 12
                    $childBlocks = $col['blocks'] ?? [];

                    $wClass = 'w-full';
                    if ($span == 12) $wClass = 'w-full';
                    else if ($span == 6) $wClass = 'lg:w-1/2';
                    else if ($span == 4) $wClass = 'lg:w-1/3';
                    else if ($span == 8) $wClass = 'lg:w-2/3';
                    else if ($span == 3) $wClass = 'lg:w-1/4';
                    else if ($span == 9) $wClass = 'lg:w-3/4';
                    else if ($span == '2.4' || $span == 2.4) $wClass = 'lg:w-1/5';
                    else if ($span == 2) $wClass = 'lg:w-1/6';
                    else if ($span == 10) $wClass = 'lg:w-5/6';
                    else $wClass = 'lg:w-' . $span . '/12'; // Fallback
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
</section>
