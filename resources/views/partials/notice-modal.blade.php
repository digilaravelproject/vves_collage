{{-- 4. NOTICE BOARD MODAL --}}
@php
    $notifService = app(\App\Services\NotificationService::class);
    $marqueeNotifications = $notifService->getMarqueeNotifications();
@endphp

<div id="notice-modal"
    class="fixed inset-0 z-70 items-center justify-center p-3 bg-black/60 backdrop-blur-sm transition-all duration-300 ease-out hidden opacity-0">
    <div id="notice-modal-content"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[80vh] flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0 overflow-hidden border border-gray-100">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-theme text-white">
            <h3 class="text-lg font-bold flex items-center gap-2 uppercase tracking-wide">
                📋 <span>Notice Board</span>
            </h3>
            <button type="button" id="close-notice-modal"
                class="text-white/70 hover:text-white text-3xl leading-none transition-colors">&times;</button>
        </div>

        {{-- Body --}}
        <div class="p-4 sm:p-6 space-y-3 overflow-y-auto text-[14px] thin-scrollbar">
            @if (count($marqueeNotifications))
                @foreach ($marqueeNotifications as $n)
                    @php
                        $icon = $n->icon ?: '🔔';
                        $title = $n->title;
                        $href = $n->href;
                        $btn = $n->button_name ?: 'View Details';
                    @endphp

                    <a href="{{ $href ?? '#' }}" target="_blank"
                        class="group flex items-center gap-4 py-3 px-4 border border-gray-100 rounded-xl transition-all duration-300 bg-gray-50 hover:bg-theme/5 hover:border-theme/30 hover:shadow-md">
                        <span
                            class="text-xl w-10 h-10 flex items-center justify-center bg-white shadow-sm border border-gray-100 rounded-full group-hover:scale-110 transition-transform">
                            {{ $icon }}
                        </span>
                        <div class="flex flex-col grow min-w-0">
                            <p class="font-semibold text-gray-800 line-clamp-2 group-hover:text-theme transition-colors">
                                {{ $title }}
                            </p>
                        </div>
                        @if ($href)
                            <span
                                class="text-theme text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap bg-theme/10 px-3 py-1.5 rounded-full">
                                {{ $btn }} &rarr;
                            </span>
                        @endif
                    </a>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center text-gray-400 py-10">
                    <span class="text-5xl mb-3 opacity-50">📭</span>
                    <p class="font-medium text-gray-500">No current announcements.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL SCRIPT --}}
<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            const openBtn = document.getElementById('open-notice-modal');
            const closeBtn = document.getElementById('close-notice-modal');
            const modal = document.getElementById('notice-modal');
            const modalContent = document.getElementById('notice-modal-content');

            const openModal = () => {
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.classList.add('overflow-hidden');
                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                });
            };

            const closeModal = () => {
                if (!modal) return;
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                modal.classList.add('opacity-0');
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                }, 300);
            };

            // Event Listeners
            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal();
                });
            }
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    })();
</script>
