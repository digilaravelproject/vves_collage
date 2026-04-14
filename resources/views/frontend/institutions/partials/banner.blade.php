<x-breadcrumb-banner 
    :image="$institution->breadcrumb_image ? 'storage/' . $institution->breadcrumb_image : null" 
    :title="$institution->name" 
    :breadcrumbs="$breadcrumbTrail"
    :note="$institution->breadcrumb_note"
>
    {{-- Extra Institution Details --}}
    <span class="bg-[#FFD700] text-[#000165] px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest shadow-lg">
        {{ $institution->category_label }}
    </span>
    
    @if ($institution->year_of_establishment)
        <span class="text-white font-black text-[10px] md:text-[11px] border-l border-white/30 pl-3 uppercase tracking-widest">
            Est. {{ $institution->year_of_establishment }}
        </span>
    @endif
    
    @if ($institution->iso_certification)
        <span class="bg-blue-500/30 backdrop-blur-md text-[#FFD700] px-3 py-1 rounded-sm border border-[#FFD700]/30 font-black text-[9px] md:text-[10px] uppercase tracking-widest flex items-center gap-1.5 shadow-md">
            <i class="bi bi-patch-check-fill"></i>
            ISO Cert No: {{ $institution->iso_certification }}
        </span>
    @endif
    
    @if ($institution->city)
        <span class="text-white/90 font-bold text-[10px] md:text-[11px] border-l border-white/30 pl-3 uppercase tracking-widest flex items-center gap-1.5 group/loc transition-colors">
            <i class="bi bi-geo-alt-fill text-[#FFD700] group-hover/loc:animate-bounce"></i> {{ $institution->city }}
        </span>
    @endif
</x-breadcrumb-banner>
