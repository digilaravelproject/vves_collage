@php
    $layout = $block['layout'] ?? 'left'; // 'left', 'right', 'top'
    $image = $block['image'] ?? '';
    $heading = $block['heading'] ?? 'About Us';
    $text = $block['text'] ?? '';
    $btnText = $block['buttonText'] ?? 'More About Us';
    $btnHref = $block['buttonLink'] ?? '/#';

    // Text ko limit kiya hai clean card look ke liye
    $shortText = Str::limit(strip_tags($text), 500);
@endphp

{{-- Main Container - White Background --}}
{{-- Decorative Background Watermark (Aapke original code se) --}}
<div class="absolute right-0 top-0 -translate-y-1/4 translate-x-1/3 opacity-[0.02] pointer-events-none hidden lg:block">
    <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-120 h-120 bg-(--primary-color)/5 rounded-full blur-3xl -z-10">
    </div>
</div>

{{-- Main Card - Light Grey Background jaisa reference image me hai --}}
<div
    class="bg-gray-50 dark:bg-gray-800/10 rounded-2xl flex flex-col md:flex-row overflow-hidden min-h-[350px] lg:min-h-[400px] shadow-sm relative z-10">
    <div class="w-full md:w-[45%] relative overflow-hidden group {{ $layout === 'right' ? 'md:order-2' : 'md:order-1' }} min-h-[250px] md:min-h-full"
        data-aos="{{ $layout === 'right' ? 'fade-left' : 'fade-right' }}" data-aos-duration="800">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $heading }}" data-parallax-image
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-10000 ease-in-out group-hover:scale-105"
                loading="lazy">
        @else
            <div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400 font-medium">Image Placeholder</span>
            </div>
        @endif
    </div>

    <div class="w-full md:w-[55%] p-6 sm:p-8 lg:p-10 flex flex-col justify-center {{ $layout === 'right' ? 'md:order-1' : 'md:order-2' }}"
        data-aos="{{ $layout === 'right' ? 'fade-right' : 'fade-left' }}" data-aos-duration="800" data-aos-delay="200">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-1.5 h-8 md:h-10 bg-[#FFD700] rounded-sm"></div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-[#1E234B] tracking-tight text-left mb-0!">
                {{ $heading }}
            </h2>
        </div>
        <div class="text-gray-600 leading-relaxed text-sm sm:text-base font-normal text-justify">
            @if($shortText)
                <p>{!! $shortText !!}</p>
            @else
                <p>Universal Education means remarkable, and remarkable is who we strive to be — in everything we do. From
                    the quality education we provide, to the world class infrastructure our institutes have. From the
                    dedicated people we hire and partner with, to the impeccable service we provide our parents and
                    students.</p>
            @endif
        </div>
        @if ($btnText && $btnHref && strlen(strip_tags($text)) > 300)
            <div class="mt-8 flex items-start">
                <a href="{{ $btnHref }}"
                    class="group inline-flex items-center gap-2 px-6 py-2.5 text-xs sm:text-sm font-bold text-white transition-all duration-300 bg-[#1E234B] rounded-full shadow-[0_8px_20px_rgba(30,35,75,0.25)] hover:bg-opacity-90 hover:-translate-y-1 hover:shadow-[0_12px_25px_rgba(30,35,75,0.4)]">
                    {{ $btnText }}
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>

@pushOnce('scripts')
<script>
    document.addEventListener('scroll', function () {
        throttle(applyParallax, 16)();
    });

    function applyParallax() {
        const images = document.querySelectorAll('[data-parallax-image]');
        const isMobile = window.innerWidth < 768;
        const triggerOffset = window.innerHeight * 0.2;

        images.forEach(image => {
            const wrapper = image.closest('div');
            if (!wrapper) return;

            const rect = wrapper.getBoundingClientRect();
            const elTop = rect.top;
            const elHeight = rect.height;

            if (elTop < (window.innerHeight - triggerOffset) && (elTop + elHeight) > triggerOffset) {
                let scrollPercent = (window.innerHeight - elTop) / (window.innerHeight + elHeight);
                let centeredPercent = scrollPercent - 0.5;
                let intensity = isMobile ? 12 : 25;
                let moveY = centeredPercent * intensity * -1;

                image.style.transform = `translateY(${moveY.toFixed(2)}px) scale(1.05)`;
            }
        });
    }

    let throttleTimer = false;
    function throttle(callback, time) {
        return function () {
            if (throttleTimer) return;
            throttleTimer = true;
            setTimeout(() => {
                callback.apply(this, arguments);
                throttleTimer = false;
            }, time);
        }
    }
    document.addEventListener('DOMContentLoaded', applyParallax);
</script>
@endpushOnce
