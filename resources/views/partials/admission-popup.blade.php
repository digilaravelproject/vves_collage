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
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-100 flex items-center justify-center p-6 bg-black/70 backdrop-blur-md"
    style="display: none;"
    @keydown.escape.window="closePopup()">
    
    <div class="relative w-full max-w-md bg-white rounded-4xl overflow-hidden shadow-[0_25px_70px_rgba(0,0,0,0.6)] transform transition-all group"
        x-show="showPopup"
        x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-700"
        x-transition:enter-start="opacity-0 scale-90 translate-y-12"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        @click.away="closePopup()">
        
        {{-- Elegant Close Button --}}
        <button @click="closePopup()" class="absolute top-5 right-5 z-20 w-10 h-10 flex items-center justify-center bg-black/20 hover:bg-black/40 backdrop-blur-md text-white rounded-full border border-white/20 transition-all duration-300 hover:rotate-90">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        {{-- Content Area --}}
        <div class="flex flex-col">
            {{-- Image Section --}}
            <div class="relative h-96 w-full overflow-hidden">
                @if($activePopup->image_path)
                    <img src="{{ asset($activePopup->image_path) }}" alt="{{ $activePopup->title }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/20 to-transparent"></div>
                @else
                    <div class="w-full h-full bg-linear-to-br from-blue-900 to-vves-primary flex items-center justify-center">
                        <span class="text-7xl">🎓</span>
                    </div>
                    <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                @endif
                
                {{-- Floating Badge --}}
                <div class="absolute top-6 left-6 px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg border border-blue-400/30">
                    Limited Seats
                </div>
            </div>

            {{-- Info Section (Bottom Integrated) --}}
            <div class="p-8 text-center bg-white relative">
                <div class="absolute -top-6 left-0 right-0 flex justify-center">
                    <div class="bg-white px-4 py-1 rounded-full shadow-md text-[10px] font-bold text-vves-primary uppercase tracking-widest border border-gray-100">
                        Session 2026-27
                    </div>
                </div>

                <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase mb-3 px-2 leading-tight">
                    {{ $activePopup->title ?: 'Admissions Open 2026' }}
                </h2>
                <p class="text-gray-500 font-medium text-sm mb-8 leading-relaxed px-2">
                    Start your journey towards excellence. Join VVES for an unmatched academic experience.
                </p>
                
                @if($activePopup->button_name)
                    <a href="{{ $activePopup->button_link ?: '#' }}" 
                       @click="closePopup()"
                       class="relative group/btn inline-flex w-full py-4.5 px-8 text-sm font-black text-white rounded-2xl shadow-xl hover:shadow-[0_15px_30px_rgba(1,57,84,0.3)] transition-all duration-300 active:scale-95 uppercase tracking-widest overflow-hidden"
                       style="background-color: {{ $activePopup->button_color ?: '#013954' }}">
                        <span class="relative z-10">{{ $activePopup->button_name }}</span>
                        <div class="absolute inset-0 bg-white/10 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                    </a>
                @endif
                
                <button @click="closePopup()" class="mt-6 text-[10px] font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest">
                    Maybe Later
                </button>
            </div>
        </div>
    </div>
</div>
@endif
