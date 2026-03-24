@php
    $id = uniqid('scroll_links_');
    $links = $block['links'] ?? [];
@endphp

<div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden flex flex-col font-roboto h-full transition-shadow hover:shadow-[0_15px_40px_rgba(0,1,101,0.08)]" data-aos="fade-up" data-aos-delay="100">

    <!-- Header -->
    <div class="bg-(--primary-color) px-5 sm:px-6 py-4 flex items-center justify-between shrink-0 relative overflow-hidden">
        {{-- Subtle background pattern for header --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 L100 0 L100 100 Z" />
            </svg>
        </div>

        <div class="flex items-center gap-3 relative z-10 w-full">
            {{-- Professional Link Icon --}}
            <span class="p-1.5 bg-white/10 rounded-md shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
            </span>
            <h2 class="text-white! text-sm sm:text-base font-bold tracking-widest uppercase m-0 leading-none break-words">
                {{ $title }}
            </h2>
        </div>
    </div>

    <!-- Scrollable Body Container -->
    <div class="relative flex-1 bg-white">

        <!-- Premium Fade Overlays (Top & Bottom) -->
        <div class="absolute inset-x-0 top-0 h-6 bg-gradient-to-b from-white to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-x-0 bottom-0 h-6 bg-gradient-to-t from-white to-transparent z-10 pointer-events-none"></div>

        <!-- Auto-Scrolling Content -->
        <div id="{{ $id }}"
             class="h-72 md:h-80 overflow-y-auto px-5 sm:px-6 py-4 space-y-3 notice-scrollbar scroll-smooth relative">

            @forelse ($links as $link)
                <div class="group border-b border-gray-50 pb-3 last:border-0 last:pb-0 fade-item">
                    <a href="{{ $link['url'] ?? '#' }}" target="_blank" class="flex items-start gap-3 w-full outline-none">
                        <span class="text-(--primary-color)/50 group-hover:text-(--primary-color) mt-1 shrink-0 transition-colors">
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                        <span class="text-sm text-gray-700 font-medium group-hover:text-(--primary-color) transition-colors leading-relaxed wrap-break-word">
                            {{ $link['text'] ?? 'Link Item' }}
                        </span>
                    </a>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-gray-400 space-y-2 opacity-50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span class="text-sm font-medium">No links available.</span>
                </div>
            @endforelse
        </div>
    </div>

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
    // Function setup to prevent multi-declaration errors if included multiple times
    if (typeof window.initAutoScroll !== 'function') {
        window.initAutoScroll = function(boxId, speed = 1) {
            const box = document.getElementById(boxId);
            if (!box) return;

            // If content is less than container height, don't scroll
            if (box.scrollHeight <= box.clientHeight) return;

            let direction = 1;      // 1 = down, -1 = up
            let interval;

            function start() {
                interval = setInterval(() => {
                    box.scrollTop += direction * speed;

                    // At bottom → reverse direction
                    if (Math.ceil(box.scrollTop + box.clientHeight) >= box.scrollHeight) {
                        direction = -1;
                    }

                    // At top → reverse again
                    if (box.scrollTop <= 0) {
                        direction = 1;
                    }
                }, 40);
            }

            function stop() {
                clearInterval(interval);
            }

            // Start scrolling after a short delay to let animations finish
            setTimeout(start, 1000);

            // Pause on hover/touch
            box.addEventListener("mouseenter", stop);
            box.addEventListener("mouseleave", start);
            box.addEventListener("touchstart", stop, {passive: true});
            box.addEventListener("touchend", start, {passive: true});
        };
    }

    // Initialize this specific instance
    document.addEventListener("DOMContentLoaded", function () {
        // Speed: 0.5 = smooth/slow, 1 = normal, 2 = fast
        window.initAutoScroll("{{ $id }}", 0.5);
    });
</script>
