@php
    $feedItems = $items ?? collect();
@endphp

@if ($feedItems->isNotEmpty())
    @if ($feedItems->isNotEmpty())
        {{-- Main Header (Standardized) --}}
        <div class="mb-0 text-center" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-2">
                {{ $title ?: 'Our Social Buzz' }}
            </h2>
            <div class="w-16 h-1 bg-vves-primary rounded-full mx-auto mb-6"></div>
            @if ($description)
                <p class="max-w-4xl mx-auto text-base font-normal leading-relaxed text-gray-600 mb-8">
                    {{ $description }}
                </p>
            @endif
        </div>

        {{--
            Instagram Grid
            Clean columns: 2 on mobile (grid-cols-2), 4 on desktop (lg:grid-cols-4)
            Gap reduced on mobile (gap-3) to fit 2 items nicely
        --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 lg:gap-8">
            @foreach ($feedItems as $item)
                {{--
                    Clean Card - Light Grey background, subtle shadow
                    Padding reduced on mobile (p-2) to maximize space for 2 columns
                --}}
                <div class="bg-[#F8F9FA] rounded-xl sm:rounded-2xl p-2 sm:p-5 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md hover:-translate-y-1 flex flex-col"
                    data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                    {{-- Instagram Wrapper - Pure clean UI --}}
                    <div
                        class="w-full bg-white rounded-lg sm:rounded-xl overflow-hidden flex justify-center border border-gray-50">
                        {!! $item->embed_code !!}
                    </div>

                </div>
            @endforeach
        </div>

    @endif

    {{-- Important: Instagram Embed Script --}}
    <script async src="//www.instagram.com/embed.js"></script>

    <style>
        /* Force Instagram Iframe to fit cleanly without breaking card layout */
        .instagram-media {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
    </style>
@endif
