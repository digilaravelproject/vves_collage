<section class="border-t border-gray-100 bg-white py-6 md:py-8 relative z-10">
    <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="w-1.5 h-8 bg-[#FFD700] rounded-sm"></div>
            <h2 class="text-3xl md:text-4xl font-bold text-[#1E234B] tracking-tight text-center">Campus Gallery
            </h2>
            <div class="w-1.5 h-8 bg-[#FFD700] rounded-sm"></div>
        </div>

        @php
            $galleries = $institution->galleries;
            $count = $galleries->count();
            $midpoint = $count > 3 ? ceil($count / 2) : $count;
            $row1 = $galleries->slice(0, $midpoint);
            $row2 = $count > 3 ? $galleries->slice($midpoint) : collect();
        @endphp

        <div class="marquee-wrapper">
            <div class="marquee-track animate-scroll-right">
                @php $items1 = $row1->concat($row1)->concat($row1); @endphp
                @foreach ($items1 as $img)
                    {{-- ADJSUT HEIGHT HERE: change h-[...] and md:h-[...] --}}
                    <div class="marquee-item w-auto" style="height: 180px;">
                        <img src="{{ asset('storage/' . $img->image_path) }}"
                            class="cursor-pointer h-full w-auto object-cover" alt="Gallery Image"
                            onclick="openGlobalLightbox('{{ asset('storage/' . $img->image_path) }}')">
                    </div>
                @endforeach
            </div>

            @if ($row2->count() > 0)
                <div class="marquee-track animate-scroll-left">
                    @php $items2 = $row2->concat($row2)->concat($row2); @endphp
                    @foreach ($items2 as $img)
                        {{-- ADJSUT HEIGHT HERE: change h-[...] and md:h-[...] --}}
                        <div class="marquee-item w-auto" style="height: 180px;">
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                class="cursor-pointer h-full w-auto object-cover" alt="Gallery Image"
                                onclick="openGlobalLightbox('{{ asset('storage/' . $img->image_path) }}')">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
