@php
    $profiles = $block['profiles'] ?? [];
    $sectionTitle = $block['section_title'] ?? 'Follow Our Journey';

    // Extract Instagram username from URL
    if (!function_exists('extractInstagramUsername')) {
        function extractInstagramUsername($link)
        {
            $link = trim($link ?? '', '/ ');
            if (str_contains($link, 'instagram.com')) {
                $path = parse_url($link, PHP_URL_PATH);
                $path = trim($path ?? '', '/');
                $segments = explode('/', $path);
                return $segments[0] ?? '';
            }
            if (!str_contains($link, '/') && !str_contains($link, '.')) {
                return ltrim($link, '@');
            }
            return '';
        }
    }
@endphp

@if(!empty($profiles))
    @if(!empty($profiles))
        {{-- Section Header (Standardized) --}}
        <div class="mb-0 text-center" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 mb-2">
                {{ $sectionTitle ?: 'Follow Our Journey' }}
            </h2>
            <div class="w-16 h-1 bg-vves-primary rounded-full mx-auto mb-10"></div>
        </div>

        {{-- Grid Layout - Clean spacing --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            @foreach($profiles as $profile)
                @php
                    $username = extractInstagramUsername($profile['link'] ?? '');
                @endphp

                {{--
                Clean Profile Card
                Light Grey background (#F8F9FA) with subtle hover lift
                --}}
                <div class="bg-[#F8F9FA] rounded-2xl p-6 sm:p-8 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 flex flex-col items-center text-center group"
                    data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                    {{-- Avatar Ring - Static, clean Instagram gradient without spinning --}}
                    <div class="relative mb-5">
                        <div
                            class="w-24 h-24 sm:w-28 sm:h-28 rounded-full p-1 bg-linear-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888]">
                            <div
                                class="w-full h-full rounded-full border-4 border-white overflow-hidden bg-white flex items-center justify-center">
                                @if(!empty($profile['image']))
                                    <img src="{{ $profile['image'] }}" alt="{{ $profile['name'] }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <span class="text-3xl text-gray-300">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Small Instagram Badge --}}
                        <div
                            class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full p-1.5 shadow-sm border border-gray-50 flex items-center justify-center">
                            <svg class="w-full h-full text-[#dc2743]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Text Info --}}
                    <h3 class="text-lg sm:text-xl font-bold text-[#1E234B] mb-1">
                        {{ $profile['name'] }}
                    </h3>

                    @if($username)
                        <p class="text-sm font-medium text-gray-500 mb-6 group-hover:text-[#dc2743] transition-colors">
                            {{ '@' . $username }}
                        </p>
                    @endif

                    {{-- Clean Action Button --}}
                    <div class="mt-auto w-full">
                        <a href="{{ $profile['link'] }}" target="_blank"
                            class="inline-flex items-center justify-center gap-2 w-full px-6 py-2.5 text-sm font-bold text-[#1E234B] bg-white border border-gray-200 rounded-full transition-all duration-300 hover:border-[#1E234B] hover:bg-[#1E234B] hover:text-white group/btn">
                            Follow
                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
@endif
