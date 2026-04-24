{{-- 1. DYNAMIC BANNER SECTION (Slider) --}}
@php
    $banners = \App\Models\Banner::where('is_active', true)->orderBy('order', 'asc')->get();
@endphp

<section class="relative w-full overflow-hidden bg-black h-[70dvh] sm:h-[80dvh] font-inter">
    <div class="swiper mySwiper w-full h-full">
        <div class="swiper-wrapper">
            @forelse ($banners as $banner)
                <div class="swiper-slide relative w-full h-full overflow-hidden">
                    {{-- Cinematic Background Media --}}
                    <div class="absolute inset-0 z-0">
                        @if ($banner->media_type === 'video')
                            <video autoplay muted playsinline
                                class="object-cover w-full h-full scale-[1.02] transform transition-transform duration-10000 ease-linear hover:scale-105">
                                <source src="{{ asset('storage/' . $banner->media_path) }}" type="video/mp4">
                            </video>
                        @else
                            <picture>
                                @if($banner->mobile_media_path)
                                    <source media="(max-width: 768px)" srcset="{{ asset('storage/' . $banner->mobile_media_path) }}">
                                @endif
                                <img src="{{ asset('storage/' . $banner->media_path) }}"
                                    class="object-cover w-full h-full scale-[1.02] transform transition-transform duration-10000 ease-linear hover:scale-105"
                                    alt="{{ $banner->title ?? 'banner' }}">
                            </picture>
                        @endif
                        {{-- Enhanced Overlay with cinematic depth --}}
                        <div class="absolute inset-0 bg-linear-to-b from-black/40 via-black/10 to-black/50 sm:bg-black/20">
                        </div>
                        <div class="absolute inset-0 bg-linear-to-r from-black/20 via-transparent to-black/20"></div>
                    </div>

                    {{-- Content Layer --}}
                    <div class="relative z-10 flex flex-col items-center justify-center h-full px-6 text-center">
                        <div class="max-w-6xl mx-auto">
                            {{-- 🌟 Premium Backlight Glow --}}
                            <div
                                class="absolute -inset-20 bg-white/3 blur-[100px] rounded-full -z-10 animate-pulse hidden md:block">
                            </div>

                            {{-- The Grand Heading (Centered, Much Bigger, Cinematic Animation) --}}
                            @if ($banner->title)
                                <h1 class="font-bold leading-[1.02] tracking-tight mb-4 z-20 relative animate-heading-reveal text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl"
                                    style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; text-shadow: 0 10px 30px rgba(0,0,0,0.8), 0 0 20px rgba(255,255,255,0.3);">
                                    @php
                                        // Ensure numbers are strictly white with an intense glow
                                        $displayHeading = preg_replace(
                                            '/(\d+)/',
                                            '<span class="inline-block transform hover:scale-105 transition-transform duration-300 drop-shadow-[0_0_25px_rgba(255,255,255,0.9)]" style="color: #ffffff !important;">$1</span>',
                                            $banner->title,
                                        );
                                    @endphp
                                    {!! $displayHeading !!}
                                </h1>


                                {{-- Premium Animated Underline --}}
                                <div
                                    class="h-1.5 sm:h-2 w-40 sm:w-80 bg-linear-to-r from-transparent via-white to-transparent rounded-full mt-2 sm:mt-4 animate-width-reveal origin-center shadow-[0_0_25px_rgba(255,255,255,0.6)] mx-auto">
                                </div>

                                {{-- Established Legacy Subtext --}}
                                <div class="flex items-center justify-center gap-3 mt-6 sm:mt-8 text-white/90 text-[11px] sm:text-sm font-bold tracking-[0.5em] uppercase animate-fade-in-up mx-auto"
                                    style="animation-delay: 0.8s;">
                                    <span class="w-10 sm:w-20 h-[2px] bg-theme"></span>
                                    ESTABLISHED LEGACY
                                    <span class="w-10 sm:w-20 h-[2px] bg-theme"></span>
                                </div>
                            @endif
                            {{-- The Sophisticated Subheading --}}
                            @if ($banner->subtitle)
                                <p class="max-w-4xl mx-auto mt-6 sm:mt-8 text-lg sm:text-2xl md:text-3xl lg:text-4xl font-medium text-white/95 animate-fade-in-up drop-shadow-[0_5px_15px_rgba(0,0,0,0.9)] leading-tight"
                                    style="animation-delay: 1s;">
                                    {{ $banner->subtitle }}
                                </p>
                            @endif

                            {{-- Elegant CTA Buttons --}}
                            @if ($banner->button_text && $banner->button_link)
                                <a href="{{ $banner->button_link }}"
                                    class="inline-flex items-center justify-center gap-3 px-10 py-4 sm:px-14 sm:py-5 mt-8 sm:mt-12 text-lg sm:text-2xl font-bold text-white transition-all duration-300 transform bg-theme border border-white/20 rounded-full shadow-[0_15px_40px_rgba(0,0,0,0.7)] hover:bg-theme-hover hover:scale-110 hover:shadow-[0_20px_50px_rgba(146,20,44,0.8)] animate-fade-in-up mx-auto"
                                    style="animation-delay: 1.2s;">
                                    {{ $banner->button_text }}
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7 group-hover:translate-x-2 transition-transform duration-300"
                                        fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3">
                                        </path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                {{-- Fallback Slide --}}
                <div class="swiper-slide relative w-full h-full flex items-center justify-center bg-gray-950">
                    <div class="relative z-10 text-center px-6">
                        <h1 class="text-4xl sm:text-6xl font-bold text-white mb-4 drop-shadow-2xl">
                            {{ setting('college_name', 'VVES College') }}
                        </h1>
                        <p class="text-xl text-white/70 italic max-w-2xl mx-auto mb-8">
                            Empowering students through quality education and established legacy.
                        </p>
                        <p class="text-xs text-white/30 uppercase tracking-widest">Add banner slides in Admin Panel to
                            get
                            started</p>
                    </div>
                    <div class="absolute inset-0 bg-linear-to-b from-blue-900/10 to-black/50"></div>
                </div>
            @endforelse
        </div>
        @if ($banners->count() > 1)
            {{-- Modern Dots Pagination --}}
            <div class="swiper-pagination bottom-6! sm:bottom-10!"></div>

            {{-- Premium Navigation Arrows --}}
            <div
                class="swiper-button-prev left-10! hidden! lg:flex! w-14! h-14! bg-white/10! backdrop-blur-md! border! border-white/10! rounded-full! text-white! hover:bg-theme! hover:border-theme! transition-all! duration-500! after:text-xl!">
            </div>
            <div
                class="swiper-button-next right-10! hidden! lg:flex! w-14! h-14! bg-white/10! backdrop-blur-md! border! border-white/10! rounded-full! text-white! hover:bg-theme! hover:border-theme! transition-all! duration-500! after:text-xl!">
            </div>
        @endif
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const initSwiper = () => {
            if (typeof Swiper !== 'undefined') {
                const swiper = new Swiper(".mySwiper", {
                    loop: true,
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev"
                    },
                    autoplay: {
                        delay: 7000,
                        disableOnInteraction: false
                    },
                    effect: "fade",
                    fadeEffect: {
                        crossFade: true
                    },
                    on: {
                        init: function () {
                            handleMedia(this);
                        },
                        slideChangeTransitionEnd: function () {
                            handleMedia(this);
                            // Re-trigger content animations on slide change
                            const activeSlide = this.slides[this.activeIndex];
                            const animatedElements = activeSlide.querySelectorAll(
                                '.animate-heading-reveal, .animate-width-reveal, .animate-fade-in-up'
                            );
                            animatedElements.forEach(el => {
                                el.style.animation = 'none';
                                el.offsetHeight; /* trigger reflow */
                                el.style.animation = null;
                            });
                        }
                    }
                });

                function handleMedia(s) {
                    const activeSlide = s.slides[s.activeIndex];
                    const video = activeSlide.querySelector('video');

                    if (video) {
                        // Video Slide: Stop internal autoplay and wait for 'ended'
                        s.autoplay.stop();
                        video.currentTime = 0; // Ensure it starts from the beginning
                        video.play().catch(() => { }); // Handle potential autoplay blockers

                        video.onended = () => {
                            s.slideNext();
                        };
                    } else {
                        // Image Slide: Use normal 7s autoplay
                        s.autoplay.start();
                    }
                }
            } else {
                setTimeout(initSwiper, 100);
            }
        };
        initSwiper();
    });
</script>




{{-- 5. NEW CLEAN ANIMATION STYLE --}}
<style>
    .banner-image {
        font-family: 'Montserrat', 'Roboto', sans-serif;
        opacity: 0;
        animation: sectionReveal 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes sectionReveal {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* 🌟 EPIC Cinematic Heading Reveal */
    .animate-heading-reveal {
        opacity: 0;
        transform-origin: center center;
        animation: headingCinematicReveal 1.5s cubic-bezier(0.19, 1, 0.22, 1) forwards 0.2s;
    }

    @keyframes headingCinematicReveal {
        0% {
            opacity: 0;
            transform: translateY(100px) scale(0.85) rotateX(25deg);
            filter: blur(20px);
            letter-spacing: -0.1em;
        }

        50% {
            opacity: 0.8;
            filter: blur(5px);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1) rotateX(0deg);
            filter: blur(0);
            letter-spacing: normal;
        }
    }

    /* 🌟 Symmetrical Width Reveal for Underline */
    .animate-width-reveal {
        width: 0;
        opacity: 0;
        animation: widthReveal 1.2s cubic-bezier(0.8, 0, 0.2, 1) forwards 1s;
    }

    @keyframes widthReveal {
        0% {
            width: 0;
            opacity: 0;
        }

        100% {
            width: 20rem;
            opacity: 1;
        }

        /* 80 = 20rem */
    }

    @media (max-width: 640px) {
        @keyframes widthReveal {
            0% {
                width: 0;
                opacity: 0;
            }

            100% {
                width: 10rem;
                opacity: 1;
            }

            /* 40 = 10rem for mobile */
        }
    }

    /* 🌟 Smooth Fade In Up for Subtext and Button */
    .animate-fade-in-up {
        opacity: 0;
        transform: translateY(40px);
        animation: fadeInUp 1.2s cubic-bezier(0.2, 1, 0.3, 1) forwards;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(40px);
            filter: blur(5px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }
    }

    /* Ensure initial hidden state is respected before animation starts */
    .swiper-slide:not(.swiper-slide-active) .animate-heading-reveal,
    .swiper-slide:not(.swiper-slide-active) .animate-width-reveal,
    .swiper-slide:not(.swiper-slide-active) .animate-fade-in-up {
        opacity: 0 !important;
        animation: none !important;
    }

    /* Hide Swiper Default Arrows Mobile */
    .swiper-button-next,
    .swiper-button-prev {
        --swiper-navigation-size: 20px;
    }

    /* Custom Pagination Dots */
    .swiper-pagination-bullet {
        background: white !important;
        opacity: 0.5;
        width: 10px;
        height: 10px;
        transition: all 0.3s ease;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        width: 30px;
        border-radius: 5px;
        background: var(--theme-color) !important;
    }

    /* Modal Animation */
    #notice-modal:not(.hidden) {
        animation: modalFadeIn 0.3s ease forwards;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>