@props(['block'])

@php
    $title = $block['section_title'] ?? 'Board of Advisors';
    $items = collect($block['items'] ?? []);
    if ($items->isEmpty()) {
        return;
    }
    $advisorCount = $items->count();
@endphp

{{-- Main Section - Padding strictly py-6 md:py-8 as requested --}}
<div class="relative py-6 md:py-8 font-sans">

    {{-- Max width 1500px --}}
    <div class="max-w-[1500px] w-full mx-auto relative z-10 px-4">

        {{-- Section Header (Standardized) --}}
        <div class="mb-4 md:mb-6 text-center" data-aos="fade-up">
            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-1">
                {{ $title }}
            </h2>
            <div class="w-12 h-1 bg-vves-primary rounded-full mx-auto mb-4"></div>
        </div>

        <div class="relative group">
            {{-- Swiper Container --}}
            <div class="swiper advisors-swiper pb-16 pt-4 px-2 -mx-2">
                <div class="swiper-wrapper items-stretch!">
                    @foreach ($items as $item)
                        <div class="swiper-slide h-auto! p-2 sm:p-3"> 
                            {{-- Clean Card Design --}}
                            <div class="flex flex-col h-full bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 group/card overflow-hidden">
                                {{-- Image Wrapper - Smaller on mobile to reduce overall card length --}}
                                <div class="relative aspect-4/5 sm:aspect-square overflow-hidden bg-[#F8F9FA] shrink-0">
                                    <img src="{{ $item['photo'] ?: 'https://via.placeholder.com/500x500?text=Advisor' }}"
                                        alt="{{ $item['name'] }}"
                                        class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-700 ease-in-out">
                                </div>

                                {{-- Content --}}
                                <div class="p-4 sm:p-6 flex flex-col grow bg-[#F8F9FA]">
                                    <h3 class="text-base sm:text-xl font-bold text-[#1E234B] mb-2 transition-colors line-clamp-1">
                                        {{ $item['name'] }}
                                    </h3>

                                    <p class="text-xs sm:text-sm text-gray-600 line-clamp-3 sm:line-clamp-4 leading-relaxed grow font-normal">
                                        {{ $item['description'] }}
                                    </p>

                                    {{-- Accent line on hover --}}
                                    <div class="mt-4 w-6 h-1 bg-gray-200 group-hover/card:bg-[#FFD700] group-hover/card:w-full transition-all duration-500 rounded-full">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Standard Pagination Dots --}}
                <div class="swiper-pagination bottom-0!"></div>
            </div>

            {{-- Premium Navigation Buttons --}}
            <div class="absolute top-1/2 -translate-y-1/2 left-0 right-0 flex justify-between items-center pointer-events-none z-20 px-0 sm:px-2">
                <button class="adv-prev hidden sm:flex w-12 h-12 items-center justify-center -translate-x-4 bg-white shadow-lg border border-gray-100 rounded-full text-vves-primary hover:bg-vves-primary hover:text-white transition-all transform hover:scale-110 pointer-events-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="adv-next hidden sm:flex w-12 h-12 items-center justify-center translate-x-4 bg-white shadow-lg border border-gray-100 rounded-full text-vves-primary hover:bg-vves-primary hover:text-white transition-all transform hover:scale-110 pointer-events-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const initAdvSwiper = () => {
            if (typeof Swiper !== 'undefined') {
                const totalItems = {{ $advisorCount }};
                new Swiper(".advisors-swiper", {
                    slidesPerView: 1.15, // Peek effect
                    spaceBetween: 0,
                    loop: totalItems > 4,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".adv-next",
                        prevEl: ".adv-prev",
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2.2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                        1280: {
                            slidesPerView: 4,
                        }
                    },
                    on: {
                        init: function () {
                            this.update();
                        },
                    },
                });
            } else {
                setTimeout(initAdvSwiper, 100);
            }
        };
        initAdvSwiper();
    });
</script>
