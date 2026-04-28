@php
    if (!function_exists('getMenuLabel')) {
        function getMenuLabel($title)
        {
            return ucwords(strtolower($title));
        }
    }

    if (!function_exists('renderDesktopRecursive')) {
        function renderDesktopRecursive($items, $level = 0)
        {
            if ($items->isEmpty()) {
                return '';
            }
            
            // Premium layout: No clunky gray backgrounds, clean white with soft transitions
            $paddingLeft = 20 + $level * 12;
            $wrapperClass = $level === 0 ? 'w-full py-1' : 'w-full mt-0.5';
            
            $html = '<ul class="flex flex-col ' . $wrapperClass . '">';
            foreach ($items as $item) {
                $hasChildren = $item->children->count() > 0;
                $title = getMenuLabel($item->title);
                $link = $item->link; // Using the model accessor (fixed)

                $html .= '<li class="group/item relative w-full">';
                $html .=
                    '<a href="' .
                    $link .
                    '" class="flex items-center gap-3 py-2 pr-4 text-[13px] font-bold text-gray-700 hover:text-theme hover:bg-theme-light transition-all duration-300 rounded-md mx-2 relative overflow-hidden" style="padding-left: ' .
                    $paddingLeft .
                    'px;">';
                
                // Active indicator on hover
                $html .= '<span class="absolute left-0 top-0 bottom-0 w-1 bg-theme scale-y-0 group-hover/item:scale-y-100 transition-transform origin-center"></span>';
                
                $html .= '<span class="relative z-10">' . $title . '</span>';
                
                if ($hasChildren) {
                    $html .= '<svg class="w-3 h-3 ml-auto opacity-40 group-hover/item:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>';
                }
                
                $html .= '</a>';
                
                if ($hasChildren) {
                    $html .= '<div class="w-full">' . renderDesktopRecursive($item->children, $level + 1) . '</div>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
    }

    if (!function_exists('renderMobileRecursive')) {
        function renderMobileRecursive($items, $level = 0)
        {
            if ($items->isEmpty()) {
                return '';
            }
            $borderClass = $level >= 0 ? 'border-l-2 border-theme ml-4 opacity-70' : '';
            $bgClass = $level % 2 == 0 ? 'bg-theme-light' : 'bg-white';
            $html = '<div class="flex flex-col space-y-1 mt-1">';
            foreach ($items as $item) {
                $hasChildren = $item->children->count() > 0;
                $title = getMenuLabel($item->title);
                $link = $item->link; // Use model accessor

                $html .= '<div x-data="{ open: false }" class="w-full px-2">';
                $html .= '<div class="flex items-center w-full group">';
                $html .=
                    '<a href="' .
                    $link .
                    '" class="flex-1 py-3 px-4 text-[14px] font-semibold text-gray-700 hover:text-theme hover:bg-theme-light rounded-l-xl transition-all duration-300">' .
                    $title .
                    '</a>';
                if ($hasChildren) {
                    $html .=
                        '<button @click="open = !open" class="p-3 text-gray-400 hover:text-theme hover:bg-theme-light rounded-r-xl transition-all duration-300 border-l border-gray-50">';
                    $html .=
                        '<svg :class="open ? \'rotate-180\' : \'\'" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>';
                    $html .= '</button>';
                }
                $html .= '</div>';
                if ($hasChildren) {
                    $html .= '<div x-show="open" x-cloak x-collapse class="pl-4 mt-1 border-l-2 border-gray-100">';
                    $html .= renderMobileRecursive($item->children, $level + 1);
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
            return $html;
        }
    }
@endphp
