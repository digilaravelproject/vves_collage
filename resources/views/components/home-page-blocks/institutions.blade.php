@php
    // $items are passed from HomePageBlock component (fetched from Institution model)
    // $title is the section title from the block settings
    $institutions = $items ?? collect();
    $instCount = $institutions->count();
@endphp

{{-- Main Section - Padding strictly py-6 md:py-8 as requested --}}
<div class="relative py-6 md:py-8 font-sans">

    {{-- Max width 1500px with safe side padding --}}
    <div class="max-w-[1500px] w-full mx-auto relative z-10 px-4">

        {{-- Section Header (Standardized) --}}
        @if (!empty($title))
            <div class="mb-4 md:mb-6 text-center" data-aos="fade-up">
                <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-1">
                    {{ $title }}
                </h2>
                <div class="w-12 h-1 bg-vves-primary rounded-full mx-auto mb-4"></div>
            </div>
        @endif

        @if ($institutions->isNotEmpty())
            <div class="relative group">
                {{-- Swiper Container --}}
                <div class="swiper institutions-swiper pb-16 pt-4 px-2 -mx-2">
                    <div class="swiper-wrapper items-stretch!">
                        @foreach ($institutions as $inst)
                            <div class="swiper-slide h-auto! p-2 sm:p-3"> 
                                {{-- Clean Card Design --}}
                                <div class="flex flex-col h-full bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 group/card overflow-hidden">
                                    {{-- Image Wrapper - Shorter aspect ratio on mobile --}}
                                    <div class="relative aspect-video sm:aspect-4/3 overflow-hidden bg-[#F8F9FA] shrink-0 border-b border-gray-50">
                                        {{-- Category Badge --}}
                                        <span class="absolute top-3 right-3 z-20 bg-[#FFD700] px-2 py-1 rounded-full text-[9px] sm:text-[10px] font-bold uppercase text-gray-900 tracking-wider shadow-sm">
                                            {{ $inst->category_label }}
                                        </span>

                                        @if ($inst->featured_image)
                                            <img src="{{ asset('storage/' . $inst->featured_image) }}"
                                                class="w-full h-full object-cover transform group-hover/card:scale-105 transition-transform duration-700 ease-in-out"
                                                alt="{{ $inst->name }}">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-center p-4 gap-3 bg-gray-100">
                                                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v1H9V7zm5 0h1v1h-1V7zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1zm-3 4H2v5h12v-5zm3 0h1v1h-1v-1z"></path>
                                                </svg>
                                                <span class="text-xs sm:text-sm font-bold text-gray-400 uppercase tracking-widest leading-tight">
                                                    {{ $inst->name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Card Content --}}
                                    <div class="p-4 sm:p-6 flex flex-col grow bg-[#F8F9FA]">
                                        <h3 class="text-base sm:text-xl font-bold text-[#1E234B] mb-4 group-hover/card:text-vves-primary transition-colors duration-300 leading-tight line-clamp-2">
                                            {{ $inst->name }}
                                        </h3>

                                        <div class="mt-auto space-y-3 sm:space-y-4 mb-5 sm:mb-6">
                                            <div class="flex items-center gap-2 sm:gap-3 text-gray-600">
                                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-vves-primary shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72l5 2.73 5-2.73v3.72z" /></svg>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Curriculum</span>
                                                    <span class="text-xs sm:text-sm font-semibold text-gray-800 leading-none mt-0.5">{{ $inst->curriculum ?? 'Not Specified' }}</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 sm:gap-3 text-gray-600">
                                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-vves-primary shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" /></svg>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Location</span>
                                                    <span class="text-xs sm:text-sm font-semibold text-gray-800 leading-none mt-0.5">{{ $inst->city ?? 'Campus' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('institutions.show', $inst->slug) }}"
                                            class="mt-auto flex items-center justify-center gap-2 w-full px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-[#1E234B] bg-white border border-gray-200 rounded-full transition-all duration-300 hover:border-vves-primary hover:bg-vves-primary hover:text-white group/btn">
                                            Explore Center
                                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
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
                    <button class="inst-prev hidden sm:flex w-12 h-12 items-center justify-center -translate-x-4 bg-white shadow-lg border border-gray-100 rounded-full text-vves-primary hover:bg-vves-primary hover:text-white transition-all transform hover:scale-110 pointer-events-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button class="inst-next hidden sm:flex w-12 h-12 items-center justify-center translate-x-4 bg-white shadow-lg border border-gray-100 rounded-full text-vves-primary hover:bg-vves-primary hover:text-white transition-all transform hover:scale-110 pointer-events-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
        @else
            <div class="bg-[#F8F9FA] rounded-[24px] p-16 text-center border border-dashed border-gray-200">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <h3 class="text-xl font-bold text-gray-400 uppercase tracking-tight">No institutions found</h3>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const initInstSwiper = () => {
            if (typeof Swiper !== 'undefined') {
                const totalItems = {{ $instCount }};
                new Swiper(".institutions-swiper", {
                    slidesPerView: 1.15, // Peek effect (thoda sa part)
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
                        nextEl: ".inst-next",
                        prevEl: ".inst-prev",
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
                setTimeout(initInstSwiper, 100);
            }
        };
        initInstSwiper();
    });
</script>
