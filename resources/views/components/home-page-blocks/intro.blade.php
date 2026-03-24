@php
    $layout = $block['layout'] ?? 'left'; // 'left', 'right', 'top'
    $image = $block['image'] ?? '';
    $heading = $block['heading'] ?? 'About Our School';
    $text = $block['text'] ?? '';
    $btnText = $block['buttonText'] ?? 'More About Us';
    $btnHref = $block['buttonLink'] ?? '/#';

    // Strip tags and limit text for the clean card look
    $shortText = Str::limit(strip_tags($text), 600);
@endphp

{{-- Main Container - Warm Light Background with Reduced Spacing --}}
<section class="py-10 md:py-16 bg-[#FDFBF9] overflow-hidden font-roboto relative">

    {{-- Decorative Background Watermark --}}
    <div class="absolute right-0 top-0 -translate-y-1/4 translate-x-1/3 opacity-[0.02] pointer-events-none hidden lg:block">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-120 h-120 bg-(--primary-color)/5 rounded-full blur-3xl -z-10"></div>
    </div>

    <div class="max-w-[1300px] mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            {{--
                1. IMAGE BLOCK
                Clean, professional rounded shape with subtle depth
            --}}
            <div class="relative order-2 {{ $layout === 'right' ? 'lg:order-2' : 'lg:order-1' }}"
                 data-aos="{{ $layout === 'right' ? 'fade-left' : 'fade-right' }}" data-aos-duration="800">

                {{-- Decorative Accent Box behind image --}}
                <div class="absolute inset-0 bg-(--primary-color) translate-x-4 translate-y-4 sm:translate-x-6 sm:translate-y-6 rounded-[2rem] opacity-10 hidden sm:block"></div>

                {{-- Main Image Wrapper --}}
                <div class="relative h-full rounded-4xl overflow-hidden shadow-[0_15px_40px_rgba(0,0,0,0.08)] aspect-4/5 sm:aspect-square lg:aspect-4/5 bg-white group border-4 border-white">
                    @if ($image)
                        <div class="-rotate-[4deg] overflow-hidden translate-x-4">
                            <img src="{{ $image }}" alt="{{ $heading }}" class="w-full h-[500px] object-cover hover:scale-105 transition-transform duration-10000 ease-in-out" loading="lazy">
                        </div>
                    @else
                        <div class="flex items-center justify-center w-full h-full bg-gray-50">
                            <span class="text-gray-400 font-medium">Image Placeholder</span>
                        </div>
                    @endif
                </div>
            </div>

            {{--
                2. CONTENT BLOCK
                Professional typography and clean layout without hardcoded extras
            --}}
            <div class="order-1 {{ $layout === 'right' ? 'lg:order-1' : 'lg:order-2' }} pt-2 lg:pt-0"
                 data-aos="{{ $layout === 'right' ? 'fade-right' : 'fade-left' }}" data-aos-duration="800" data-aos-delay="200">

                {{-- Small Decorative Icon + Heading --}}
                <div class="flex items-center gap-3 mb-4 sm:mb-6">
                    <div class="text-(--primary-color) bg-(--primary-color)/10 p-2 rounded-lg">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight leading-tight">
                        {{ $heading }}
                    </h2>
                </div>

                {{-- Underline --}}
                <div class="w-20 h-1.5 bg-(--primary-color) rounded-full mb-6 shadow-sm"></div>

                {{-- Dynamic Text Content --}}
                <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] p-6 sm:p-8 mb-8 border border-gray-100">
                    <div class="text-base sm:text-lg text-gray-600 leading-relaxed font-medium text-justify">
                        @if($shortText)
                            {!! $shortText !!}
                        @else
                            <p>We are committed to providing a safe, inclusive, and engaging learning environment that empowers students to achieve academic excellence, develop critical thinking, and become responsible, compassionate members of society.</p>
                        @endif
                    </div>
                </div>

                {{-- Dynamic Action Button --}}
                @if ($btnText && $btnHref)
                    <div class="flex items-start">
                        <a href="{{ $btnHref }}"
                           class="group inline-flex items-center gap-3 px-8 py-3.5 text-sm sm:text-base font-bold text-white transition-all duration-300 bg-(--primary-color) rounded-full shadow-[0_8px_20px_rgba(0,1,101,0.25)] hover:bg-(--primary-hover) hover:-translate-y-1 hover:shadow-[0_12px_25px_rgba(0,1,101,0.4)]">
                            {{ $btnText }}
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                @endif


            </div>
        </div>
    </div>
</section>

{{-- PARALLAX SCRIPT (Optimized for performance) --}}
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
