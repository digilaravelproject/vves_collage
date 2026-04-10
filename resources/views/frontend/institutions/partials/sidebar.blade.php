<div class="sticky top-24">
    <div class="bg-[#F8F9FA] rounded-3xl border border-gray-100 p-8 shadow-md relative overflow-hidden">
        <div class="relative z-10 flex flex-col gap-6">
            
            <div class="flex items-center gap-4 border-b border-gray-200 pb-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-[#1E234B] text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                </div>
                <h3 class="font-bold text-[#1E234B] leading-tight uppercase tracking-tight text-base text-left">
                    {{ $institution->name }}
                </h3>
            </div>

            <ul class="space-y-5">
                @if ($institution->website)
                    <li>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Website</span>
                        <a href="{{ $institution->website }}" target="_blank" class="flex items-center gap-3 text-sm font-bold text-[#1E234B] hover:text-[#FFD700] transition-colors wrap-break-word">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            <span class="break-all">{{ str_replace(['http://', 'https://'], '', $institution->website) }}</span>
                        </a>
                    </li>
                @endif
                @if ($institution->phone)
                    <li>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Phone</span>
                        <a href="tel:{{ $institution->phone }}" class="flex items-center gap-3 text-sm font-bold text-[#1E234B] hover:text-[#FFD700] transition-colors">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $institution->phone }}
                        </a>
                    </li>
                @endif
                @if ($institution->address)
                    <li>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Address</span>
                        <div class="flex items-start gap-3 text-sm font-medium text-gray-600 leading-relaxed mb-3">
                            <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $institution->address }}</span>
                        </div>
                        @if ($institution->google_maps_link)
                            <a href="{{ $institution->google_maps_link }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#000165] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#FFD700] hover:text-[#000165] transition-all shadow-md group">
                                <i class="bi bi-geo-alt-fill"></i>
                                Get Directions
                                <i class="bi bi-box-arrow-up-right text-[8px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </a>
                        @endif
                    </li>
                @endif
            </ul>

            @if ($institution->social_links)
                @php
                    $socials = is_array($institution->social_links)
                        ? $institution->social_links
                        : json_decode($institution->social_links, true);
                    $hasSocials = collect($socials)->filter()->count() > 0;
                @endphp
                @if ($hasSocials)
                    <div class="pt-5 border-t border-gray-200 mt-2">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Social Connect</span>
                        <div class="flex items-center gap-3">
                            @foreach (['facebook', 'instagram', 'linkedin', 'youtube'] as $network)
                                @if (isset($socials[$network]) && $socials[$network])
                                    <a href="{{ $socials[$network] }}" target="_blank" class="w-10 h-10 rounded-full bg-white border border-gray-100 text-[#1E234B] flex items-center justify-center hover:bg-[#1E234B] hover:-translate-y-1 hover:text-[#FFD700] transition-all shadow-sm">
                                        <i class="bi bi-{{ $network }} text-lg"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
