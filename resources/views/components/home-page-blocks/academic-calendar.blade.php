{{-- ========================================= --}}
{{-- Styles: Swiper CSS & Custom Overrides --}}
{{-- ========================================= --}}
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<style>
    /* ---------- ARROWS (fixed: only one icon visible) ---------- */
    .calendar-swiper .swiper-button-next,
    .calendar-swiper .swiper-button-prev {
        color: #DC2626;
        background: rgba(255, 255, 255, 0.85);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background-size: 12px;
        background-repeat: no-repeat;
        background-position: center;
    }

    /* remove default arrows completely */
    .calendar-swiper .swiper-button-next:after,
    .calendar-swiper .swiper-button-prev:after {
        content: '' !important;
    }

    /* custom SVG icons */
    .calendar-swiper .swiper-button-next {
        background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' fill='red' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 3l5 5-5 5' stroke='%23DC2626' stroke-width='2' fill='none'/%3E%3C/svg%3E");
    }

    .calendar-swiper .swiper-button-prev {
        background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' fill='red' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 3L6 8l5 5' stroke='%23DC2626' stroke-width='2' fill='none'/%3E%3C/svg%3E");
    }

    /* ---------- Pagination active dot ---------- */
    .swiper-pagination-bullet-active {
        background: #DC2626 !important;
    }

    /* ---------- Equal Card Height Wrapper ---------- */
    .swiper-slide {
        display: flex;
        height: auto;
    }

    .calendar-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* ---------- Full description (no limit) ---------- */
    .calendar-desc {
        overflow: visible !important;
    }

    /* ---------- Title with custom size + !important ---------- */
    .calendar-title {
        font-size: 22px !important;
        line-height: 1.3 !important;
        margin-bottom: 1rem;
    }
</style>

<div class="relative">
    <div class="text-center mb-0" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight mb-2">
            {{ $title }}
        </h2>
        <div class="w-16 h-1 bg-(--primary-color) rounded-full mx-auto mb-6"></div>
    </div>
    @if ($items->isEmpty())
        <p class="text-center text-gray-500" data-aos="fade-up" data-aos-delay="100">
            No calendar items found.
        </p>
    @else
        <div class="relative px-2 mx-auto sm:px-0" data-aos="fade-up" data-aos-delay="100">

            <div class="swiper calendar-swiper pb-12 px-2">
                <div class="swiper-wrapper">

                    @foreach ($items as $item)
                        <div class="swiper-slide">

                            <div class="calendar-card bg-white p-6 sm:p-8
                                                    rounded-xl shadow-sm hover:shadow-md
                                                    hover:-translate-y-1 transition-all duration-300
                                                    border border-gray-100">

                                <a href="{{ $item->link_href }}" class="flex flex-col h-full">

                                    <!-- Date -->
                                    <div class="pb-4 border-b border-gray-100 mb-5">
                                        <p class="text-4xl font-bold text-(--primary-color) leading-none mb-1">
                                            {{ $item->event_datetime->format('d') }}
                                        </p>
                                        <p class="text-sm font-semibold uppercase tracking-wider text-gray-500">
                                            {{ $item->event_datetime->format('F Y') }}
                                        </p>
                                    </div>

                                    <!-- Title (full + !important size) -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 text-left">
                                        {{ $item->title }}
                                    </h3>

                                    <!-- Time -->
                                    <div class="flex items-center gap-2 mb-4 text-(--primary-color)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm font-bold">
                                            {{ $item->event_datetime->format('g:i A') }} –
                                            {{ $item->end_time ? $item->end_time->format('g:i A') : '10:00 AM' }}
                                        </p>
                                    </div>

                                    <!-- Description (FULL, not cut) -->
                                    <p class="text-sm text-gray-600 leading-relaxed text-left">
                                        {{ $item->description }}
                                    </p>

                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>

                @if($items->count() > 3)
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                @endif
            </div>

        </div>
    @endif
</div>



{{-- ========================================= --}}
{{-- Scripts: AOS & Swiper JS --}}
{{-- ========================================= --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-in-out'
        });

        const swiper = new Swiper('.calendar-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            grabCursor: true,

            breakpoints: {
                480: { slidesPerView: 1.4, spaceBetween: 20 },
                640: { slidesPerView: 2, spaceBetween: 24 },
                1024: { slidesPerView: 3, spaceBetween: 30 },
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

    });
</script>
