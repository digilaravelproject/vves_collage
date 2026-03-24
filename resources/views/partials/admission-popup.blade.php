@php
    $activePopup = \App\Models\Popup::where('is_active', true)->latest()->first();
@endphp

@if($activePopup)
<div x-data="{
        showPopup: false,
        init() {
            if (!sessionStorage.getItem('admission_popup_seen')) {
                setTimeout(() => {
                    this.showPopup = true;
                    document.body.classList.add('overflow-hidden');
                }, 2000);
            }
        },
        closePopup() {
            this.showPopup = false;
            document.body.classList.remove('overflow-hidden');
            sessionStorage.setItem('admission_popup_seen', 'true');
        }
    }"
    x-show="showPopup"
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-9999 flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm"
    style="display: none;"
    @keydown.escape.window="closePopup()">

    {{-- Main Popup Container --}}
    <div class="relative w-full max-w-md bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all"
        x-show="showPopup"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        @click.away="closePopup()">

        {{-- Elegant Close Button --}}
        <button @click="closePopup()" class="absolute top-4 right-4 z-20 w-8 h-8 flex items-center justify-center bg-black/40 hover:bg-black/60 backdrop-blur-md text-white rounded-full transition-all duration-300 hover:rotate-90 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        {{-- Content Area --}}
        <div class="flex flex-col">

            {{-- Image Section --}}
            <div class="relative h-56 sm:h-64 w-full overflow-hidden bg-gray-100 group">
                @if($activePopup->image_path)
                    <img src="{{ asset($activePopup->image_path) }}" alt="{{ $activePopup->title }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                @else
                    {{-- Fallback Image/Color --}}
                    <div class="w-full h-full bg-(--primary-color) flex items-center justify-center">
                        <span class="text-6xl drop-shadow-md">🎓</span>
                    </div>
                @endif

                {{-- Gradient Overlay for better contrast --}}
                <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>

                {{-- Floating Badge --}}
                <div class="absolute top-4 left-4 px-3 py-1 bg-white text-(--primary-color) text-[10px] font-black uppercase tracking-widest rounded-sm shadow-md">
                    Limited Seats
                </div>
            </div>

            {{-- Info Section --}}
            <div class="p-6 sm:p-8 text-center bg-white">

                {{-- Session Subtitle --}}
                <span class="block text-(--primary-color) text-[11px] font-bold uppercase tracking-widest mb-2">
                    Session 2026-27
                </span>

                {{-- Main Title --}}
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight leading-tight mb-3">
                    {{ $activePopup->title ?: 'Admissions Open' }}
                </h2>

                {{-- Description --}}
                {{-- <p class="text-gray-500 font-medium text-[13px] sm:text-sm mb-6 leading-relaxed">
                    Start your journey towards excellence. Join us for an unmatched academic and holistic experience.
                </p> --}}

                {{-- Call to Action Button --}}
                @if($activePopup->button_name)
                    <a href="{{ $activePopup->button_link ?: '#' }}"
                       @click="closePopup()"
                       class="relative group/btn flex justify-center w-full py-3.5 px-6 text-sm font-bold text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 active:scale-[0.98] uppercase tracking-wide overflow-hidden"
                       style="background-color: {{ $activePopup->button_color ?: 'var(--primary-color)' }}">
                        <span class="relative z-10 flex items-center gap-2">
                            {{ $activePopup->button_name }}
                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </span>
                        {{-- Hover Highlight --}}
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                    </a>
                @endif

                {{-- Dismiss Link --}}
                <button @click="closePopup()" class="mt-5 text-[11px] font-bold text-gray-400 hover:text-gray-800 transition-colors uppercase tracking-widest focus:outline-none">
                    Maybe Later
                </button>
            </div>
        </div>
    </div>
</div>
@endif
