<section class="md:hidden w-full text-[#ffffff] overflow-hidden flex flex-wrap items-center border-[#D6DBE2]"
    style="background:linear-gradient(90deg, rgba(1, 39, 112, 0.1) 0%, #013954 62.5%);">

    <div
        class="flex items-center justify-center px-2 py-2 lg:px-5 sm:py-3 text-xs sm:text-sm md:text-base font-semibold tracking-wide text-vves-primary uppercase">
        📢 Announcement</div>

    {{-- <div
        class="flex items-center justify-center px-2 py-2 lg:px-5 sm:py-3 text-xs sm:text-sm md:text-base font-semibold tracking-wide text-white uppercase bg-[#0A1F44] announcement-label">
        📢 Announcement
    </div> --}}

    <div class="relative flex-1 py-2 overflow-hidden text-xs sm:text-sm md:text-[15px] font-medium tracking-wide">
        <div class="marquee">
            <div class="track">
                @php
                    $notifService = app(\App\Services\NotificationService::class);

                    // This is the update:
                    // We directly call the new function to get ONLY the top-featured notifications.
                    $marqueeNotifications = $notifService->getMarqueeNotifications();

                    // The old .filter() block is no longer needed.
                @endphp

                @if (count($marqueeNotifications))
                    @foreach ($marqueeNotifications as $n)
                        @php
                            $icon = $n->icon ?: '🔔';
                            $title = $n->title;
                            $href = $n->href;
                            $btn = $n->button_name ?: 'Click Here';
                        @endphp
                        <span>{{ $icon }} {{ $title }} — @if ($href)<a href="{{ $href }}"
                        class="marquee-link">{{ $btn }}</a>@endif</span>
                    @endforeach

                    @foreach ($marqueeNotifications as $n)
                        @php
                            $icon = $n->icon ?: '🔔';
                            $title = $n->title;
                            $href = $n->href;
                            $btn = $n->button_name ?: 'Click Here';
                        @endphp
                        <span>{{ $icon }} {{ $title }} — @if ($href)<a href="{{ $href }}"
                        class="marquee-link">{{ $btn }}</a>@endif</span>
                    @endforeach
                @else
                    {{-- This is your original fallback content --}}
                    <span>🎓 Admissions Open 2025–26 — <a href="#" class="marquee-link">Apply Now</a></span>
                    <span>🏆 Merit List Declared — <a href="#" class="marquee-link">View Results</a></span>
                    <span>🎭 Annual Cultural Fest Coming Soon — <a href="#" class="marquee-link">Know More</a></span>
                    <span>📚 Exam Timetable Released — <a href="#" class="marquee-link">Check Schedule</a></span>

                    <span>🎓 Admissions Open 2025–26 — <a href="#" class="marquee-link">Apply Now</a></span>
                    <span>🏆 Merit List Declared — <a href="#" class="marquee-link">View Results</a></span>
                    <span>🎭 Annual Cultural Fest Coming Soon — <a href="#" class="marquee-link">Know More</a></span>
                    <span>📚 Exam Timetable Released — <a href="#" class="marquee-link">Check Schedule</a></span>
                @endif
            </div>
        </div>
    </div>
</section>
    {{-- 🌟 FIX: ONE Alpine root for all lead components (Sticky Buttons & Modals) --}}
    <div x-data="leadForms()">
        @include('partials.sticky-lead-buttons')
        @include('partials.lead-modals')
    </div>

{{-- 2. DYNAMIC BANNER SECTION (Slider) --}}
@php
    $banners = \Illuminate\Support\Facades\Cache::remember('banner_media_all', 3600, function() {
        return \App\Models\Setting::query()->where('key', 'like', 'banner_media_%')->get();
    });
@endphp

@if ($banners->count())
    <section class="relative w-full overflow-hidden banner-image">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($banners as $index => $banner)
                    @php $data = json_decode($banner->value, true); @endphp
                    <div class="relative swiper-slide">
                        @if ($data['type'] === 'image')
                            <img src="{{ asset('storage/' . $data['path']) }}"
                                class="w-full h-[380px] sm:h-[450px] lg:h-[520px] xl:h-[600px] object-cover object-center transition-transform duration-700 hover:scale-105"
                                @if($index === 0) fetchpriority="high" @else loading="lazy" @endif />
                        @else
                            <video
                                class="w-full h-[380px] sm:h-[450px] lg:h-[520px] xl:h-[600px] object-cover object-center your-slider-video"
                                autoplay muted loop playsinline @if($index === 0) preload="auto" fetchpriority="high" @else preload="metadata" loading="lazy" @endif>
                                <source src="{{ asset('storage/' . $data['path']) }}" type="video/mp4" />
                                Your browser does not support the video tag
                            </video>
                        @endif

                        <div
                            class="absolute inset-0 z-10 flex flex-col items-start justify-center px-6 sm:px-16 lg:px-24 text-left text-white bg-[linear-gradient(90deg,rgba(0,0,0,0.85)_0%,rgba(0,0,0,0.4)_45%,rgba(0,0,0,0)_75%)]">

                            @php
                                $bannerHeading = setting('banner_heading');
                            @endphp
                            @if ($bannerHeading)
                                <div class="relative group animate-hero-text">
                                    {{-- 🌟 Premium Backlight Glow --}}
                                    <div class="absolute -inset-10 bg-blue-600/20 blur-[120px] rounded-full -z-10 animate-pulse hidden md:block"></div>

                                    {{-- Decorative Seal Icon (No Text, purely graphic/visual) --}}
                                    <div class="absolute -top-16 -left-12 opacity-10 pointer-events-none transition-transform duration-1000 group-hover:rotate-12 group-hover:scale-110 hidden lg:block">
                                        <svg class="w-48 h-48 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.2">
                                            <circle cx="12" cy="12" r="10" stroke-dasharray="1 2"/>
                                            <path d="M12 15L8.5 17L9.5 13.5L7 11L10.5 10.5L12 7L13.5 10.5L17 11L14.5 13.5L15.5 17L12 15Z" />
                                        </svg>
                                    </div>

                                    <h1
                                        class="text-4xl font-black leading-[1.05] tracking-tight sm:text-6xl md:text-7xl lg:text-8xl xl:text-9xl drop-shadow-[0_15px_50px_rgba(0,0,0,0.8)] text-white mb-2 z-20 relative"
                                        style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; text-shadow: 6px 6px 20px rgba(0,0,0,0.8); font-weight: 900 !important; opacity: 1 !important;">
                                        @php
                                            // Dynamically style numbers to make them POP
                                            $displayHeading = preg_replace(
                                                '/(\d+)/',
                                                '<span class="text-blue-500 inline-block transform hover:scale-110 transition-transform duration-300">$1</span>',
                                                $bannerHeading
                                            );
                                        @endphp
                                        {!! $displayHeading !!}
                                    </h1>

                                    {{-- Premium Animated Underline --}}
                                    <div class="h-1.5 sm:h-2 w-48 bg-linear-to-r from-blue-500 via-white to-transparent rounded-full mt-4 shadow-[0_0_25px_rgba(59,130,246,0.6)] animate-pulse"></div>

                                    <div class="flex items-center gap-2 mt-4 text-white/60 text-[10px] sm:text-xs font-bold tracking-[0.3em] uppercase">
                                        <span class="w-8 h-px bg-white/20"></span>
                                        ESTABLISHED LEGACY
                                        <span class="w-8 h-px bg-white/20"></span>
                                    </div>
                                </div>
                            @endif

                            @if (setting('banner_subheading'))
                                <p class="max-w-2xl mt-4 text-base italic font-medium text-white/90 sm:text-lg md:text-2xl animate-hero-subtext drop-shadow-md">
                                    {{ setting('banner_subheading') }}
                                </p>
                            @endif

                            @if (setting('banner_button_text') && setting('banner_button_link'))
                                <a href="{{ setting('banner_button_link') }}"
                                    class="inline-block px-8 py-4 mt-8 text-sm font-bold text-white transition-all duration-300 transform bg-blue-600 rounded-full shadow-2xl hover:bg-blue-700 sm:text-lg sm:px-10 hover:scale-110 animate-hero-btn">
                                    {{ setting('banner_button_text') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const initSwiper = () => {
                if (typeof Swiper !== 'undefined') {
                    new Swiper(".mySwiper", {
                        loop: true,
                        pagination: { el: ".swiper-pagination", clickable: true },
                        autoplay: { delay: 5000, disableOnInteraction: false },
                        effect: "fade",
                        fadeEffect: { crossFade: true },
                    });
                } else {
                    setTimeout(initSwiper, 100);
                }
            };
            initSwiper();
        });
    </script>
@endif
{{-- 4. NOTICE BOARD MODAL --}}
<div id="notice-modal"
    class="fixed inset-0 z-65 items-center justify-center p-3 bg-black/50 transition-all duration-300 ease-out hidden opacity-0">

    <div id="notice-modal-content"
        class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-lg max-h-[80vh] flex flex-col transform transition-all duration-300 ease-out scale-90 opacity-0 overflow-hidden border border-gray-200">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b bg-linear-to-r from-blue-600 to-blue-800 text-white">
            <h3 class="text-lg font-semibold flex items-center gap-2">
                📋 <span>Notice Board</span>
            </h3>
            <button type="button" id="close-notice-modal"
                class="text-white/80 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        {{-- Body --}}
        <div class="p-5 space-y-3 overflow-y-auto text-[14px] scrollbar-thin scrollbar-thumb-blue-300 scrollbar-track-transparent">
            @if (count($marqueeNotifications))
                @foreach ($marqueeNotifications as $n)
                    @php
                        $icon = $n->icon ?: '🔔';
                        $title = $n->title;
                        $href = $n->href;
                        $btn = $n->button_name ?: 'View';
                    @endphp

                    <a href="{{ $href ?? '#' }}" target="_blank"
                        class="group flex items-center gap-3 py-3 px-4 border border-gray-200 rounded-xl transition-all duration-200 bg-white/60 hover:bg-blue-50 hover:scale-[1.01] shadow-sm">
                        <span class="text-lg w-9 h-9 flex items-center justify-center bg-blue-100 text-blue-700 rounded-md">
                            {{ $icon }}
                        </span>
                        <div class="flex flex-col grow min-w-0">
                            <p class="font-medium text-gray-800 truncate">{{ $title }}</p>
                        </div>
                        @if ($href)
                            <span
                                class="text-blue-600 text-xs font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ $btn }} →
                            </span>
                        @endif
                    </a>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center text-gray-500 py-8">
                    <span class="text-4xl mb-2">📭</span>
                    <p>No current announcements.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- 5. CLEAN ANIMATION STYLE --}}
<style>
     .banner-image {
        font-family: 'Montserrat', sans-serif;
        opacity: 0;
        animation: sectionReveal 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes sectionReveal {
        from { opacity: 0; transform: scale(1.02); filter: blur(10px); }
        to { opacity: 1; transform: scale(1); filter: blur(0); }
    }

    /* Hero Text & Elements Animation */
    @keyframes heroFadeInUp {
        from { opacity: 0; transform: translateY(40px); filter: blur(10px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }

    .animate-hero-text {
        opacity: 0;
        animation: heroFadeInUp 1.2s cubic-bezier(0.2, 1, 0.3, 1) forwards 0.3s;
    }

    .animate-hero-subtext {
        opacity: 0;
        animation: heroFadeInUp 1.2s cubic-bezier(0.2, 1, 0.3, 1) forwards 0.5s;
    }

    .animate-hero-btn {
        opacity: 0;
        animation: heroFadeInUp 1.2s cubic-bezier(0.2, 1, 0.3, 1) forwards 0.7s;
    }

    /* Ensure elements are visible on active slide */
    .swiper-slide-active .animate-hero-text,
    .swiper-slide-active .animate-hero-subtext,
    .swiper-slide-active .animate-hero-btn {
        opacity: 1 !important;
    }

    /* Hide Swiper Navigation Arrows */
    .swiper-button-next, .swiper-button-prev {
        display: none !important;
    }

    .announcement-label {
        clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%);
        font-family: "Poppins", "Open Sans", sans-serif;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        margin-right: -3px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    /* Marquee wrapper */
    .marquee {
        overflow: hidden;
        white-space: nowrap;
        width: 100%;
    }

    /* Marquee track */
    .track {
        display: inline-flex;
        gap: 3rem;
        animation: marquee 60s linear infinite;
        will-change: transform;
    }

    .marquee:hover .track {
        animation-play-state: paused;
    }

    .track span {
        display: inline-block;
        white-space: nowrap;
    }

    /* Links inside marquee */
    .marquee-link {
        /* color: #1E90FF; */
        color:#0000EE;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s, text-decoration 0.3s;
    }

    .marquee-link:hover {
        color: #0A1F44;
        text-decoration: underline;
    }

    /* Animation */
    @keyframes marquee {
        0% {
            transform: translate3d(0, 0, 0);
        }

        100% {
            transform: translate3d(-50%, 0, 0);
        }
    }

    /* 📱 Responsive Scaling */
    @media (max-width: 1024px) {
        .announcement-label {
            clip-path: polygon(0 0, 92% 0, 100% 50%, 92% 100%, 0 100%);
        }
    }

    @media (max-width: 768px) {
        .announcement-label {
            clip-path: polygon(0 0, 94% 0, 100% 50%, 94% 100%, 0 100%);
            font-size: 13px;
            /* padding: 6px 16px; */
        }

        .track {
            gap: 2rem;
            animation-duration: 30s;
        }
    }

    @media (max-width: 480px) {
        .announcement-label {
            clip-path: polygon(0 0, 96% 0, 100% 50%, 96% 100%, 0 100%);
            font-size: 12px;
            /* padding: 5px 14px; */
        }

        .track {
            gap: 1.5rem;
            animation-duration: 35s;
        }
    }
    /* 🔴 Pulse Indicator + Subtle Shake Animation */
    #open-notice-modal .animate-pulse {
        animation: pulse-shake 2s infinite;
    }

    @keyframes pulse-shake {
        0%, 100% {
            transform: translate(0, 0) scale(1);
            opacity: 1;
        }
        25% {
            transform: translate(1px, -1px) scale(1.2);
            opacity: 0.9;
        }
        50% {
            transform: translate(-1px, 1px) scale(1);
            opacity: 1;
        }
        75% {
            transform: translate(0px, -1px) scale(1.2);
            opacity: 0.9;
        }
    }

    /* Modal Animation */
    #notice-modal-content {
        transition: all 0.3s ease;
    }

    #notice-modal:not(.hidden) {
        animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            backdrop-filter: blur(0px);
        }
        to {
            opacity: 1;
            backdrop-filter: blur(3px);
        }
    }

    /* Scrollbar Styling */
    .scrollbar-thin {
        scrollbar-width: thin;
    }

    .scrollbar-thumb-blue-300::-webkit-scrollbar {
        width: 6px;
    }

    .scrollbar-thumb-blue-300::-webkit-scrollbar-thumb {
        background-color: #93c5fd;
        border-radius: 10px;
    }
</style>

{{-- 6. SCRIPT (Removed Hover-to-Open Logic, Click Only) --}}
<script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const openBtn = document.getElementById('open-notice-modal');
            const closeBtn = document.getElementById('close-notice-modal');
            const modal = document.getElementById('notice-modal');
            const modalContent = document.getElementById('notice-modal-content');

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.classList.add('overflow-hidden');
                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-90', 'opacity-0');
                });
            };

            const closeModal = () => {
                modalContent.classList.add('scale-90', 'opacity-0');
                modal.classList.add('opacity-0');
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                }, 250);
            };

            // Only Click Open Now
            openBtn.addEventListener('click', openModal);

            // Close Events
            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => e.target === modal && closeModal());
            document.addEventListener('keydown', (e) => e.key === 'Escape' && !modal.classList.contains('hidden') && closeModal());

            // Auto-open logic (Commented out as requested)
            /*
            if (!sessionStorage.getItem('notice_modal_shown')) {
                setTimeout(() => {
                    openModal();
                    sessionStorage.setItem('notice_modal_shown', 'true');
                }, 1000);
            }
            */
        });
    })();
</script>
