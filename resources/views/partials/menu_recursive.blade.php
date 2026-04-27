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
            $paddingLeft = 14 + $level * 12;
            $wrapperClass = $level === 0 ? 'bg-gray-50 py-1 rounded-sm w-full' : 'mt-0.5 w-full';
            $html = '<ul class="space-y-0.5 ' . $wrapperClass . '">';
            foreach ($items as $item) {
                $hasChildren = $item->children->count() > 0;
                $title = getMenuLabel($item->title);
                
                // Add anchor support: if link starts with #, use it as is. 
                // If it's a section_id, we'll need to handle it.
                $link = $item->link;
                if ($item->section_id) {
                    $link = '#' . $item->section_id;
                }

                $html .= '<li class="w-full">';
                $html .=
                    '<a href="' .
                    $link .
                    '" class="block text-xs font-normal text-gray-600 hover:text-theme hover:underline transition duration-150 wrap-break-word whitespace-normal" style="padding-left: ' .
                    $paddingLeft .
                    'px;">' .
                    $title .
                    '</a>';
                if ($hasChildren) {
                    $html .= renderDesktopRecursive($item->children, $level + 1);
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
            $html = '<div class="flex flex-col space-y-0.5 ' . $borderClass . ' ' . $bgClass . '">';
            foreach ($items as $item) {
                $hasChildren = $item->children->count() > 0;
                $title = getMenuLabel($item->title);
                
                $link = $item->link ?? '#';
                if ($item->section_id) {
                    $link = '#' . $item->section_id;
                }

                $html .= '<div x-data="{ open: false }" class="w-full">';
                $html .= '<div class="flex items-center justify-between w-full pr-4">';
                $html .=
                    '<a href="' .
                    $link .
                    '" class="flex-1 py-2.5 pl-3 text-sm font-medium text-theme hover:bg-theme-light rounded-l-md transition">' .
                    $title .
                    '</a>';
                if ($hasChildren) {
                    $html .=
                        '<button @click="open = !open" class="p-2.5 text-theme hover:bg-theme-light rounded-r-md transition">';
                    $html .=
                        '<svg :class="open ? \'rotate-180\' : \'\'" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>';
                    $html .= '</button>';
                }
                $html .= '</div>';
                if ($hasChildren) {
                    $html .= '<div x-show="open" x-cloak x-collapse class="pb-2">';
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
