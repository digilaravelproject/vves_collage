@php
    $profiles = $block['profiles'] ?? [];
    $sectionTitle = $block['section_title'] ?? 'Follow Our Journey';

    // Extract Instagram username from URL
    if (!function_exists('extractInstagramUsername')) {
        function extractInstagramUsername($link) {
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

<div class="instagram-profiles-section py-8">
    @if($sectionTitle)
        <div class="mb-10 text-center">
            <h2 class="header-title h2 flex items-center justify-center gap-3">
                <span class="text-4xl">📸</span> {{ $sectionTitle }}
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-pink-500 via-purple-500 to-yellow-500 mx-auto rounded-full mt-4"></div>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 px-4">
        @foreach($profiles as $profile)
            @php
                $username = extractInstagramUsername($profile['link'] ?? '');
            @endphp
            <div class="instagram-card group relative bg-white rounded-2xl p-6 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                {{-- Decorative background element --}}
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-pink-50 to-purple-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10 flex flex-col items-center">
                    {{-- Avatar/Logo --}}
                    <div class="w-20 h-20 rounded-full p-1 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 mb-4 transition-transform duration-300 group-hover:rotate-6">
                        <div class="w-full h-full rounded-full border-2 border-white overflow-hidden bg-gray-100">
                            @if(!empty($profile['image']))
                                <img src="{{ $profile['image'] }}" alt="{{ $profile['name'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Name --}}
                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $profile['name'] }}</h3>
                    @if($username)
                        <p class="text-sm text-gray-500 mb-6">{{ '@' . $username }}</p>
                    @else
                        <p class="text-sm text-gray-500 mb-6">&nbsp;</p>
                    @endif

                    {{-- Actions --}}
                    <div class="w-full grid grid-cols-2 gap-3">
                        <a href="{{ $profile['link'] }}" target="_blank"
                           class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl text-xs font-bold transition-all duration-300 hover:shadow-lg hover:shadow-pink-200 active:scale-95">
                            Follow
                        </a>
                        <a href="{{ $profile['link'] }}" target="_blank"
                           class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-50 text-gray-700 border border-gray-100 rounded-xl text-xs font-bold transition-all duration-300 hover:bg-gray-100 active:scale-95">
                            View
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .instagram-card {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
</style>
