<div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8 mt-4 md:mt-6 relative z-10">
    <div class="relative w-full h-[200px] md:h-[260px] rounded-3xl overflow-hidden shadow-sm group">

        {{-- Background Image --}}
        @if ($institution->breadcrumb_image)
            <img src="{{ asset('storage/' . $institution->breadcrumb_image) }}" alt="{{ $institution->name }} Banner"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
        @else
            <img src="{{ asset('storage/breadcrum.jpeg') }}" alt="{{ $institution->name }} Banner"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                onerror="this.src='https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop'">
        @endif

        {{-- Premium Navy Gradient Overlay --}}
        <div class="absolute inset-0 bg-linear-to-r from-[#000165]/25 via-[#000165]/15 to-transparent"></div>

        {{-- Content inside Banner --}}
        <div class="absolute inset-0 w-full p-6 md:p-10 flex flex-col justify-center">

            {{-- Breadcrumb --}}
            <nav class="flex text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-white/80 mb-3" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li class="opacity-40">/</li>
                    <li><a href="{{ route('institutions.list') }}" class="hover:text-white transition-colors">Our Institute</a></li>
                    <li class="opacity-40">/</li>
                    <li class="text-[#FFD700] truncate max-w-[150px] sm:max-w-none">{{ $institution->name }}</li>
                </ol>
            </nav>

            {{-- Banner Title Area --}}
            <div class="border-l-4 border-[#FFD700] pl-6 max-w-4xl">
                <h1 class="text-3xl md:text-5xl font-black text-white! leading-tight mb-2 tracking-tighter">
                    {{ $institution->name }}
                </h1>
                
                @if ($institution->tagline)
                    <p class="text-white/80 font-medium italic text-xs md:text-sm mb-4 tracking-wide max-w-2xl drop-shadow-sm">
                        "{{ $institution->tagline }}"
                    </p>
                @endif
                
                <div class="flex flex-wrap items-center gap-3">
                    <span class="bg-[#FFD700] text-[#000165] px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">
                        {{ $institution->category_label }}
                    </span>
                    
                    @if ($institution->year_of_establishment)
                        <span class="text-white font-black text-[10px] md:text-[11px] border-l border-white/30 pl-3 uppercase tracking-widest">
                            Est. {{ $institution->year_of_establishment }}
                        </span>
                    @endif
                    
                    @if ($institution->iso_certification)
                        <span class="bg-blue-500/20 backdrop-blur-md text-[#FFD700] px-2.5 py-0.5 rounded-sm border border-[#FFD700]/30 font-black text-[9px] md:text-[10px] uppercase tracking-widest flex items-center gap-1.5 shadow-sm">
                            <i class="bi bi-patch-check-fill"></i>
                            ISO Cert No: {{ $institution->iso_certification }}
                        </span>
                    @endif
                    
                    @if ($institution->city)
                        <span class="text-white/80 font-bold text-[10px] md:text-[11px] border-l border-white/30 pl-3 uppercase tracking-widest flex items-center gap-1.5">
                            <i class="bi bi-geo-alt-fill text-[#FFD700]"></i> {{ $institution->city }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
