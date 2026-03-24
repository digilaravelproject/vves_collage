@php
    $feedItems = $items ?? collect();
@endphp

@if($feedItems->isNotEmpty())
<section class="py-12 sm:py-20 bg-gray-50/30 overflow-hidden font-roboto relative">

    {{-- Background Decorative Elements --}}
    <div class="absolute top-0 right-0 -mt-32 -mr-32 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-(--primary-color)/5 rounded-full blur-[80px] sm:blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-32 -ml-32 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-(--primary-color)/5 rounded-full blur-[80px] sm:blur-[120px] pointer-events-none"></div>

    <div class="max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8 relative z-10">

        {{-- Section Header --}}
        <div class="mb-10 sm:mb-16 text-center relative" data-aos="fade-up">
            <!-- Background faint text -->
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.02] pointer-events-none select-none">
                <span class="text-5xl sm:text-8xl lg:text-[10rem] font-black uppercase tracking-widest text-(--primary-color) whitespace-nowrap">{{ $title ?: 'Our Social Buzz' }}</span>
            </div>

            <!-- Main Heading (Red Theme) -->
            <h2 class="text-2xl sm:text-4xl lg:text-5xl flex items-center justify-center gap-2 sm:gap-4 relative z-10 font-black tracking-tight text-gray-900 uppercase">
                {{ $title ?: 'Our Social Buzz' }}
            </h2>

            @if($description)
                <p class="text-sm sm:text-lg lg:text-xl text-gray-500 max-w-2xl mx-auto mt-3 sm:mt-6 relative z-10 font-medium px-4">
                    {{ $description }}
                </p>
            @endif

            <!-- Symmetrical Theme Underline -->
            <div class="h-1.5 sm:h-2 w-20 sm:w-32 bg-linear-to-r from-transparent via-(--primary-color) to-transparent mx-auto rounded-full mt-4 sm:mt-6 shadow-[0_0_15px_rgba(0,1,101,0.4)]"></div>
        </div>

        {{-- Instagram Grid (2 on mobile, 3 on tablet, 4 on desktop) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6 lg:gap-8">
            @foreach($feedItems as $item)
                <div class="bg-white p-4 sm:p-8 sm:rounded-4xl shadow-2xl relative overflow-hidden group"
                     data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                    {{-- Decorative Top Glow Line --}}
                    <div class="h-1 sm:h-1.5 w-full bg-linear-to-r from-(--primary-color)/20 via-(--primary-color) to-(--primary-color)/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 absolute top-0 left-0 z-30"></div>

                    {{--
                        FIXED HEIGHT WRAPPER WITHOUT SCROLL:
                        Mobile height reduced to 320px to accommodate 2 columns neatly.
                        Desktop height kept at 500px.
                    --}}
                    <div class="instagram-embed-wrapper w-full h-[320px] sm:h-[450px] lg:h-[500px] overflow-hidden relative z-10 bg-white flex justify-center pt-1 sm:pt-2">

                        <div class="w-full flex justify-center pointer-events-auto transform scale-[0.85] sm:scale-100 origin-top">
                            {!! $item->embed_code !!}
                        </div>

                        {{-- Premium Fade-Out Gradient at the bottom --}}
                        <div class="absolute bottom-0 left-0 w-full h-24 sm:h-32 bg-gradient-to-t from-white via-white/95 to-transparent z-20 pointer-events-none transition-opacity duration-300"></div>

                        {{-- Hover 'View Post' Overlay Badge --}}
                        <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6">
                            <span class="px-4 py-2 sm:px-6 sm:py-3 bg-(--primary-color) text-white text-xs sm:text-sm font-bold rounded-full shadow-[0_8px_15px_rgba(0,1,101,0.4)] flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                View Post
                            </span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>

{{-- Important: Instagram Embed Script --}}
<script async src="//www.instagram.com/embed.js"></script>

<style>
    /* Force Instagram Iframe to fit perfectly inside our card */
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
