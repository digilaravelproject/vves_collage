@props(['title', 'items', 'id' => uniqid('scroll_')])

<div class="w-full bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden flex flex-col font-roboto h-full transition-shadow hover:shadow-[0_15px_40px_rgba(0,1,101,0.08)]" data-aos="fade-up" data-aos-delay="100">

    <!-- Header -->
    <div class="bg-(--primary-color) px-5 sm:px-6 py-4 flex items-center justify-between shrink-0 relative overflow-hidden">
        {{-- Subtle background pattern for header --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 L100 0 L100 100 Z" />
            </svg>
        </div>

        <div class="flex items-center gap-3 relative z-10 w-full">
            {{-- Professional minimal icon --}}
            <span class="p-1.5 bg-white/10 rounded-md shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
            </span>
            <h2 class="text-white! text-sm sm:text-base font-bold tracking-widest uppercase m-0 leading-none wrap-break-word">
                {{ $title }}
            </h2>
        </div>
    </div>

    <!-- Scrollable Body Container -->
    <div class="relative flex-1 bg-white">

        <!-- Premium Fade Overlays (Top & Bottom) -->
        <div class="absolute top-0 left-0 w-full h-6 bg-linear-to-b from-white to-transparent z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full h-6 bg-linear-to-t from-white to-transparent z-10 pointer-events-none"></div>

        <!-- Auto-Scrolling Content -->
        <div id="{{ $id }}"
             class="h-64 sm:h-72 md:h-80 overflow-y-auto px-5 sm:px-6 py-4 space-y-3 notice-scrollbar scroll-smooth relative">

            @forelse ($items as $item)
                <div class="group border-b border-gray-50 pb-3 last:border-0 last:pb-0 fade-item">
                    @if ($item->link)
                        <a href="{{ $item->link }}" target="_blank" class="flex items-start gap-3 w-full outline-none">
                            <span class="text-(--primary-color)/50 group-hover:text-(--primary-color) mt-1 shrink-0 transition-colors">
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                            <span class="text-sm text-gray-700 font-medium group-hover:text-(--primary-color) transition-colors leading-relaxed">
                                {{ $item->title }}
                            </span>
                        </a>
                    @else
                        <div class="flex items-start gap-3 w-full">
                            <span class="text-(--primary-color)/30 mt-1 shrink-0 flex items-center justify-center h-4 w-4">
                                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                            </span>
                            <span class="text-sm text-gray-700 font-medium leading-relaxed">
                                {{ $item->title }}
                            </span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-gray-400 space-y-2 opacity-50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <span class="text-sm font-medium">No announcements found.</span>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Sticky Footer for "Read More" -->
    @if ($items->first()?->link)
        <div class="bg-gray-50/80 px-5 sm:px-6 py-3.5 border-t border-gray-100 flex items-center justify-end shrink-0">
            <a href="{{ $items->first()->link }}" target="_blank"
               class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-(--primary-color) hover:text-(--primary-hover) transition-colors uppercase tracking-wide">
                View Details
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    @endif

</div>

<style>
    /* === Custom Elegant Scrollbar === */
    .notice-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .notice-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .notice-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 1, 101, 0.15);
        border-radius: 10px;
    }
    .notice-scrollbar:hover::-webkit-scrollbar-thumb {
        background: rgba(0, 1, 101, 0.4);
    }

    /* === Smooth Fade-in Animation === */
    .fade-item {
        opacity: 0;
        animation: fadeInItem 0.6s ease-out forwards;
    }

    @keyframes fadeInItem {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Stagger fade items slightly based on their position (up to 10 items) */
    .fade-item:nth-child(1) { animation-delay: 0.1s; }
    .fade-item:nth-child(2) { animation-delay: 0.2s; }
    .fade-item:nth-child(3) { animation-delay: 0.3s; }
    .fade-item:nth-child(4) { animation-delay: 0.4s; }
    .fade-item:nth-child(5) { animation-delay: 0.5s; }
    .fade-item:nth-child(n+6) { animation-delay: 0.6s; }
</style>

<!-- === Auto Scroll Script | Globally Scoped for Multiple Instances === -->
<script>
    if (typeof window.initAutoScroll !== 'function') {
        window.initAutoScroll = function(boxId, speed = 1) {
            const box = document.getElementById(boxId);
            if (!box) return;

            let direction = 1;
            let isPaused = false;
            let scrollPos = box.scrollTop;
            let lastTime = 0;
            let pauseEndTime = 0;

            function step(timestamp) {
                if (!lastTime) lastTime = timestamp;
                const deltaTime = timestamp - lastTime;
                lastTime = timestamp;

                if (!isPaused && timestamp > pauseEndTime) {
                    if (box.scrollHeight > box.clientHeight) {
                        scrollPos += direction * (speed * (deltaTime / 16.67));
                        box.scrollTop = scrollPos;

                        if (direction === 1 && Math.ceil(box.scrollTop + box.clientHeight) >= box.scrollHeight) {
                            direction = -1;
                            pauseEndTime = timestamp + 2000;
                        } else if (direction === -1 && box.scrollTop <= 0) {
                            direction = 1;
                            pauseEndTime = timestamp + 2000;
                        }
                        
                        if (Math.abs(scrollPos - box.scrollTop) > 10) {
                            scrollPos = box.scrollTop;
                        }
                    }
                }

                requestAnimationFrame(step);
            }

            box.addEventListener("mouseenter", () => isPaused = true);
            box.addEventListener("mouseleave", () => isPaused = false);
            box.addEventListener("touchstart", () => isPaused = true, {passive: true});
            box.addEventListener("touchend", () => isPaused = false, {passive: true});

            requestAnimationFrame(step);
        };
    }

    document.addEventListener("DOMContentLoaded", function () {
        window.initAutoScroll("{{ $id }}", 0.5);
    });
</script>
