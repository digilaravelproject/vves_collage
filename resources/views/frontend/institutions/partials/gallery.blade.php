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
                            onclick="openLightbox('{{ asset('storage/' . $img->image_path) }}')">
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
                                onclick="openLightbox('{{ asset('storage/' . $img->image_path) }}')">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Lightbox --}}
    <div id="lightbox"
        class="fixed inset-0 z-50 bg-[#1E234B]/95 hidden items-center justify-center p-4 backdrop-blur-md"
        onclick="this.classList.add('hidden'); this.classList.remove('flex');">
        <img id="lightbox-img" src=""
            class="max-w-full max-h-[90vh] object-contain rounded drop-shadow-2xl scale-95 transition-transform"
            alt="Zoomed">
        <button
            class="absolute top-6 right-6 text-[#1E234B] bg-white hover:bg-[#FFD700] rounded-full w-12 h-12 flex items-center justify-center text-xl transition-colors shadow-lg"><svg
                class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg></button>
    </div>
    <script>
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            const lb = document.getElementById('lightbox');
            lb.classList.remove('hidden');
            lb.classList.add('flex');
            setTimeout(() => document.getElementById('lightbox-img').classList.replace('scale-95', 'scale-100'), 50);
        }
    </script>
</section>
