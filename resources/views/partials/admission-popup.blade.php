@php
    $activePopup = \App\Models\Popup::where('is_active', true)->latest()->first();
@endphp

@if($activePopup)
<div x-data="{ 
        showPopup: false,
        init() {
            // Check if seen in this session
            if (!sessionStorage.getItem('admission_popup_seen')) {
                setTimeout(() => {
                    this.showPopup = true;
                    document.body.classList.add('overflow-hidden');
                }, 1500);
            }
        },
        closePopup() {
            this.showPopup = false;
            document.body.classList.remove('overflow-hidden');
            sessionStorage.setItem('admission_popup_seen', 'true');
        }
    }" 
    x-show="showPopup"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    style="display: none;"
    @keydown.escape.window="closePopup()">
    
    <div class="relative w-full max-w-lg bg-white rounded-3xl overflow-hidden shadow-2xl transform transition-all"
        x-show="showPopup"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-90 translate-y-8"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        @click.away="closePopup()">
        
        {{-- Close Button --}}
        <button @click="closePopup()" class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center bg-black/10 hover:bg-black/20 text-gray-800 rounded-full transition-colors">
            <i class="bi bi-x-lg text-sm"></i>
        </button>

        {{-- Content Area --}}
        <div class="flex flex-col">
            @if($activePopup->image_path)
            <div class="relative h-64 w-full">
                <img src="{{ asset($activePopup->image_path) }}" alt="{{ $activePopup->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-6 right-6">
                    <h2 class="text-2xl font-black text-white drop-shadow-md tracking-tight uppercase">{{ $activePopup->title }}</h2>
                </div>
            </div>
            @else
            <div class="p-8 bg-indigo-600">
                <h2 class="text-3xl font-black text-white tracking-tight uppercase">{{ $activePopup->title }}</h2>
            </div>
            @endif

            <div class="p-8 text-center space-y-6 bg-white">
                <p class="text-gray-600 font-medium">Click the button below to proceed with your application.</p>
                
                @if($activePopup->button_name)
                <a href="{{ $activePopup->button_link ?: '#' }}" 
                   @click="closePopup()"
                   class="inline-block w-full py-4 px-8 text-lg font-black text-white rounded-2xl shadow-xl hover:shadow-2xl transition-all active:scale-95 uppercase tracking-widest"
                   style="background-color: {{ $activePopup->button_color }}">
                    {{ $activePopup->button_name }}
                </a>
                @endif
                
                <button @click="closePopup()" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                    Maybe Later
                </button>
            </div>
        </div>
    </div>
</div>
@endif
