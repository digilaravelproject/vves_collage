@props(['menus', 'activeSection', 'level' => 1])

<ul class="space-y-1 {{ $level > 1 ? 'pl-4 mt-1' : '' }}">
    @foreach ($menus as $menu)
        @php
            $hasChildren = $menu->childrenRecursive->count() > 0;
            $isActive = ($activeSection->id ?? 0) === ($menu->page->id ?? 0);
            $url = $menu->link;
            
            $itemClasses = $isActive 
                ? 'bg-[#013954] text-white shadow-md' 
                : 'bg-gray-100 text-gray-800 hover:bg-blue-50 hover:text-[#013954]';
        @endphp

        <li @if($hasChildren && $level >= 2) x-data="{ open: false }" @endif>
            <div class="flex items-center justify-between w-full">
                <a href="{{ $url }}"
                    class="flex-1 block px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg {{ $itemClasses }}">
                    {{ $menu->title }}
                </a>
                
                @if($hasChildren && $level >= 2)
                    <button @click="open = !open"
                        class="px-2 text-gray-600 transition hover:text-[#013954] focus:outline-none"
                        title="Toggle submenu">
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
                        </svg>
                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 15l-6-6-6 6" />
                        </svg>
                    </button>
                @endif
            </div>

            @if ($hasChildren)
                @if ($level >= 2)
                    <div x-show="open" x-collapse style="display: none;">
                        @include('frontend.pages.partials.menu', ['menus' => $menu->childrenRecursive, 'activeSection' => $activeSection, 'level' => $level + 1])
                    </div>
                @else
                    @include('frontend.pages.partials.menu', ['menus' => $menu->childrenRecursive, 'activeSection' => $activeSection, 'level' => $level + 1])
                @endif
            @endif
        </li>
    @endforeach
</ul>
