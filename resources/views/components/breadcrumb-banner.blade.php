@props([
    'image' => null,
    'title' => 'Page Title',
    'breadcrumbs' => [],
    'note' => null,
    'fallbackImage' => asset('storage/breadcrum.jpeg')
])

@php
    // Logic to handle potential storage paths and prevent double prefixing
    $bannerUrl = $image;
    if ($bannerUrl) {
        if (!Str::startsWith($bannerUrl, ['http', 'https', 'assets/', 'storage/'])) {
            $bannerUrl = 'storage/' . $bannerUrl;
        }
    }
    $bannerImage = $bannerUrl ? asset($bannerUrl) : $fallbackImage;
@endphp

<div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8 mt-4 md:mt-6 relative z-10">
    <div class="relative w-full h-[200px] md:h-[260px] rounded-3xl overflow-hidden shadow-sm group">

        {{-- Background Image --}}
        <img src="{{ $bannerImage }}" alt="{{ $title }} Banner"
             class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
             onerror="this.src='{{ asset('storage/breadcrum.jpeg') }}'">

        {{-- Premium Navy Gradient Overlay --}}
        <div class="absolute inset-0 bg-linear-to-r from-[#000165]/25 via-[#000165]/15 to-transparent"></div>

        {{-- Content inside Banner --}}
        <div class="absolute inset-0 w-full p-6 md:p-10 flex flex-col justify-center">

            {{-- Breadcrumb --}}
            <nav class="flex text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-white/80 mb-3" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="opacity-40">/</li>
                        <li>
                            @if(isset($breadcrumb['url']) && $breadcrumb['url'])
                                <a href="{{ $breadcrumb['url'] }}" class="hover:text-white transition-colors">{{ $breadcrumb['label'] }}</a>
                            @else
                                <span class="text-[#FFD700] truncate max-w-[150px] sm:max-w-none">{{ $breadcrumb['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>

            {{-- Banner Title Area --}}
            <div class="border-l-4 border-[#FFD700] pl-6 max-w-4xl">
                <h1 class="text-3xl md:text-5xl font-black text-white! leading-tight mb-2 tracking-tighter">
                    {{ $title }}
                </h1>
                
                @if ($note)
                    <p class="text-white/80 font-medium italic text-xs md:text-sm mb-4 tracking-wide max-w-2xl drop-shadow-sm">
                        "{{ $note }}"
                    </p>
                @endif
                
                @if(isset($slot) && $slot->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-3">
                        {{ $slot }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
