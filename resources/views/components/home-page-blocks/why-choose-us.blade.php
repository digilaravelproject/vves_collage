@props(['title', 'description', 'items'])

{{-- === Main Title Section (Standardized) === --}}
<div class="mb-0 text-center" data-aos="fade-up">
    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-2">
        {{ $title }}
    </h2>
    <div class="w-16 h-1 bg-vves-primary rounded-full mx-auto mb-6"></div>
    @if ($description)
        <p class="max-w-4xl mx-auto text-base font-normal leading-relaxed text-gray-600 mb-8">
            {{ $description }}
        </p>
    @endif
</div>
{{-- === End Main Title Section === --}}


@if ($items->isEmpty())
    <p class="text-center text-gray-500" data-aos="fade-up" data-aos-delay="100">
        No items found.
    </p>
@else
    {{-- PERFECT GRID: Gap adjust kiya finishing ke liye --}}
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3" data-aos="fade-up" data-aos-delay="100">

        @foreach ($items as $item)
            {{--
            PERFECT CARD (FINISHING):
            - Koi border nahi.
            - Halka, professional shadow (shadow-lg)
            - Padding (p-6) kam ki taaki bulky na lage.
            - border-gray-200 ka ek halka border finishing touch ke liye.
            --}}
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-lg">

                @if ($item->icon_or_image)
                    <img class="object-contain w-full h-44 mx-auto mb-6" src="{{ asset('storage/' . $item->icon_or_image) }}"
                alt="{{ $item->title }}" loading="lazy"> @endif <h3
                    class="mb-2 text-lg font-semibold text-gray-900 text-center">
                    {{ $item->title }}
                </h3>

                {{-- PERFECT TEXT: Size (text-base) adjust kiya --}}
                <p class="text-base text-gray-600 text-center">
                    {{ $item->description }}
                </p>
            </div>
        @endforeach
    </div>
@endif
