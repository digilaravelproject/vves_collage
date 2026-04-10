@props(['block', 'depth' => 0])

@php
    $type = $block['type'] ?? '';
    $content = $block['content'] ?? '';
    $maxDepth = 10; // Balance between flexibility and safety

    $style = collect([
        'font-size' => $block['fontSize'] ?? null ? $block['fontSize'] . 'px' : null,
        'color' => $block['color'] ?? null,
        'text-align' => $block['textAlign'] ?? null,
        'line-height' => $block['lineHeight'] ?? null,
        'font-weight' => $block['bold'] ?? false ? 'bold' : null,
        'font-style' => $block['italic'] ?? false ? 'italic' : null,
        'text-decoration' => $block['underline'] ?? false ? 'underline' : null,
        'margin-bottom' => '1rem',
    ])
        ->filter()
        ->map(fn($v, $k) => "{$k}: {$v}")
        ->implode('; ');
@endphp

@switch($type)

    {{-- ======================================================================
    SECTION
====================================================================== --}}
    @case('section')
        <section id="section-{{ $block['id'] }}" 
            x-data="{ open: {{ $block['expanded'] ?? false ? 'true' : 'false' }} }" 
            class="p-4 mb-6 border border-gray-200 shadow-sm rounded-2xl bg-gray-50 scroll-mt-24">
            <div @click="open = !open" class="flex items-center justify-between cursor-pointer select-none">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $block['title'] ?? 'Untitled Section' }}
                </h2>

                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                </svg>

                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <div x-show="open" x-collapse class="mt-4 space-y-6">
                @if (!empty($block['blocks']) && is_array($block['blocks']))
                    @if($depth < $maxDepth)
                        @foreach ($block['blocks'] as $sub)
                            <x-page-block :block="$sub" :depth="$depth + 1" />
                        @endforeach
                    @else
                        <div class="p-4 border border-red-200 bg-red-50 text-red-600 rounded-xl text-sm italic">
                            Maximum nesting depth reached.
                        </div>
                    @endif
                @else
                    <p class="italic text-gray-400">No content in this section.</p>
                @endif
            </div>
        </section>
    @break

 {{-- ======================================================================
    ✅ LAYOUT GRID (NEW)
    ====================================================================== --}}
    @case('layout_grid')
        <div class="my-8">
            {{-- Optional Title --}}
            @if(!empty($block['title']))
                <h3 class="mb-4 text-xl font-bold text-gray-800">{{ $block['title'] }}</h3>
            @endif

            {{-- Grid Container --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
                @if (!empty($block['columns']) && is_array($block['columns']))
                    @foreach ($block['columns'] as $col)
                        @php
                            // Span logic: Default to 12 if missing.
                            // Using md:col-span-X so it stacks on mobile (grid-cols-1)
                            $span = $col['span'] ?? 12;
                            $colClass = "md:col-span-{$span}";
                        @endphp

                        <div class="{{ $colClass }} space-y-6">
                            @if (!empty($col['blocks']) && is_array($col['blocks']))
                                @if($depth < $maxDepth)
                                    @foreach ($col['blocks'] as $childBlock)
                                        {{-- Recursive Call for Child Blocks --}}
                                        <x-page-block :block="$childBlock" :depth="$depth + 1" />
                                    @endforeach
                                @else
                                    <div class="p-4 border border-red-200 bg-red-50 text-red-600 rounded-xl text-sm italic">
                                        Nesting limit reached.
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @break



    {{-- ======================================================================
    HEADING & TEXT
====================================================================== --}}
    @case('heading')
    @case('text')
        <div class="mb-4 prose-sm prose prose-gray max-w-none" style="{{ $style }}">
            {!! $content !!}
        </div>
    @break

    {{-- ======================================================================
    IMAGE
====================================================================== --}}
    @case('image')
        @if (!empty($block['src']))
            <div class="my-6 text-center">
                <img src="{{ $block['src'] }}" alt="{{ $block['alt'] ?? 'Image' }}"
                    style="max-width: 100%; height: auto; {{ $style }}"
                    class="object-contain mx-auto rounded-lg shadow-md" loading="lazy" />

                @if (!empty($block['caption']))
                    <p class="mt-2 text-sm text-gray-500">{{ $block['caption'] }}</p>
                @endif
            </div>
        @endif
    @break

    {{-- ======================================================================
    VIDEO
====================================================================== --}}
    @case('video')
        @if (!empty($block['src']))
            <div class="my-6 text-center">
                <video src="{{ $block['src'] }}" controls class="max-w-full mx-auto rounded-lg shadow-md"
                    style="height: {{ $block['height'] ?? '315px' }}; max-width: 100%;"></video>
            </div>
        @endif
    @break

    {{-- ======================================================================
    PDF OLD
====================================================================== --}}
    @case('pdf_old')
        @if (!empty($block['src']))
            <link rel="preload" href="{{ $block['src'] }}" as="fetch" crossorigin="anonymous">

            <style>
                @media print {
                    .pdf-iframe-wrapper {
                        display: none !important;
                    }
                }
            </style>

            <div class="my-6 pdf-iframe-wrapper">
                <iframe src="{{ $block['src'] }}#toolbar=0" width="100%" height="700"
                    class="border rounded-lg shadow-inner" loading="lazy" oncontextmenu="return false;">
                    PDF load nahi ho paayi.
                </iframe>
            </div>

            <script>
                if (!window.pdfJsGlobalKeysAdded) {
                    document.addEventListener('keydown', function(e) {
                        if ((e.ctrlKey && e.key === 'p') || (e.ctrlKey && e.key === 's')) {
                            e.preventDefault();
                        }
                    });
                    window.pdfJsGlobalKeysAdded = true;
                }
            </script>
        @endif
    @break

    {{-- ======================================================================
    PDF NEW
====================================================================== --}}
   @case('pdf')
        @if (!empty($block['src']))
            @php $uniqueId = 'pdf_viewer_' . uniqid(); @endphp

            <link rel="preload" href="{{ $block['src'] }}" as="fetch" crossorigin="anonymous">

            <style>
                @media print {
                    .pdf-viewer-wrapper-{{ $uniqueId }} {
                        display: none !important;
                    }
                }
            </style>

            <div class="my-6 pdf-viewer-wrapper-{{ $uniqueId }}">
                <div id="{{ $uniqueId }}_container"
                    class="w-full max-h-[700px] border rounded-lg shadow-inner overflow-auto relative"
                    data-pdf-url="{{ $block['src'] }}?view=raw">
                    <div id="{{ $uniqueId }}_loading"
                        class="p-8 text-center text-gray-500 font-semibold bg-gray-50 dark:bg-gray-800 rounded-lg h-full">
                        <div class="animate-pulse space-y-4">
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mx-auto"></div>
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/2 mx-auto"></div>
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-5/6 mx-auto"></div>
                        </div>
                        <p class="mt-4 text-sm flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            Loading PDF...
                        </p>
                    </div>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" defer></script>

            <script>
                if (!window.pdfJsGlobalKeysAdded) {
                    document.addEventListener('keydown', function(e) {
                        if ((e.ctrlKey && e.key === 'p') || (e.ctrlKey && e.key === 's')) {
                            e.preventDefault();
                        }
                    });
                    window.pdfJsGlobalKeysAdded = true;
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const viewer = document.getElementById('{{ $uniqueId }}_container');
                    const loadingMessage = document.getElementById('{{ $uniqueId }}_loading');

                    if (viewer) {
                        const url = viewer.dataset.pdfUrl;

                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    loadPdf(url, viewer, loadingMessage);
                                    observer.unobserve(viewer);
                                }
                            });
                        }, {
                            rootMargin: '100px'
                        });

                        observer.observe(viewer);
                    }
                });

                function loadPdf(url, viewer, loadingMessage) {
                    const devicePixelRatio = window.devicePixelRatio || 1;
                    const baseScale = 1.2;
                    let pdfDoc = null;
                    const renderedPages = new Set();

                    viewer.style.userSelect = "none";
                    viewer.addEventListener('contextmenu', e => e.preventDefault());

                    pdfjsLib.GlobalWorkerOptions.workerSrc =
                        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                    pdfjsLib.getDocument(url).promise.then(pdf => {
                        pdfDoc = pdf;
                        const initialPages = Math.min(3, pdfDoc.numPages);

                        for (let i = 1; i <= initialPages; i++) {
                            renderPage(i);
                        }

                        if (loadingMessage) loadingMessage.remove();

                        if (pdfDoc.numPages > initialPages) renderVisiblePages();
                    });

                    function renderPage(pageNumber) {
                        if (renderedPages.has(pageNumber)) return;

                        pdfDoc.getPage(pageNumber).then(page => {
                            const viewport = page.getViewport({
                                scale: baseScale * devicePixelRatio
                            });
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            const wrap = document.createElement('div');
                            wrap.className = 'pdf-page-container';
                            wrap.style.marginBottom = "1rem";
                            wrap.style.width = '100%';

                            canvas.width = viewport.width;
                            canvas.height = viewport.height;
                            canvas.style.width = "100%";
                            canvas.style.height = "auto";

                            wrap.appendChild(canvas);
                            viewer.appendChild(wrap);

                            page.render({
                                canvasContext: ctx,
                                viewport
                            }).promise.then(() => {
                                renderedPages.add(pageNumber);
                            });
                        });
                    }

                    function renderVisiblePages() {
                        if (!pdfDoc) return;

                        const scrollTop = viewer.scrollTop;
                        const scrollBottom = scrollTop + viewer.clientHeight;
                        const estHeight = viewer.scrollHeight / pdfDoc.numPages;

                        for (let i = 1; i <= pdfDoc.numPages; i++) {
                            const top = (i - 1) * estHeight;
                            const bottom = top + estHeight;

                            if (bottom >= scrollTop - 300 && top <= scrollBottom + 300) {
                                renderPage(i);
                            }
                        }
                    }

                    let scrollTimeout;

                    viewer.addEventListener('scroll', () => {
                        clearTimeout(scrollTimeout);
                        scrollTimeout = setTimeout(() => {
                            renderVisiblePages();
                        }, 150);
                    });
                }
            </script>
        @endif
    @break

    {{-- ======================================================================
    EMBED
====================================================================== --}}
    @case('embed')
        @php
            $embedUrl = null;
            $src = $block['src'] ?? '';

            if (preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $src, $m)) {
                $embedUrl = 'https://www.youtube.com/embed/' . $m[2] . '?rel=0';
            }
        @endphp

        @if ($embedUrl)
            <div class="my-6 aspect-w-16 aspect-h-9">
                <iframe src="{{ $embedUrl }}" class="w-full h-[500px] rounded-lg shadow-md" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen loading="lazy"></iframe>
            </div>
        @elseif (!empty($src))
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                Unsupported embed URL: {{ $src }}
            </div>
        @endif
    @break

    {{-- ======================================================================
    DIVIDER
====================================================================== --}}
    @case('divider')
        <hr class="my-8 border-gray-200" />
    @break

    {{-- ======================================================================
    BUTTON
====================================================================== --}}
@case('button')
    @php
        $buttonHref = $block['href'] ?? '#';
        $buttonTarget = ($block['target'] ?? '_self');
        $buttonText = $block['text'] ?? ($block['content'] ?? null);
        $buttonImage = $block['src'] ?? null;
        $displayMode = $block['display_mode'] ?? 'default';
        $alignClass = match ($block['align'] ?? 'left') {
            'center' => 'justify-center',
            'right' => 'justify-end',
            default => 'justify-start',
        };
    @endphp

    {{-- Inline → NO flex. Normal → flex alignment --}}
    <div class="{{ $displayMode === 'inline' ? '' : 'my-6 flex ' . $alignClass }}">

        @if ($buttonImage)
            {{-- ⭐ CLEAN SQUARE TILE (No blur, bigger image, less radius) ⭐ --}}
            <a href="{{ $buttonHref }}" target="{{ $buttonTarget }}"
                class="w-[250px] p-2 bg-white shadow-lg border border-gray-200
                       rounded-xl hover:shadow-2xl hover:scale-[1.03]
                       transition-all duration-300">

                {{-- BIG Square Image --}}
                <div class="w-full aspect-square overflow-hidden rounded-lg">
                    <img src="{{ $buttonImage }}" class="w-full h-full object-cover">
                </div>

                @if ($buttonText)
                    <span class="mt-3 block text-sm font-semibold text-gray-800 text-center">
                        {{ $buttonText }}
                    </span>
                @endif

            </a>

        @else
            {{-- Normal Button --}}
            <a href="{{ $buttonHref }}" target="{{ $buttonTarget }}"
                class="inline-block px-8 py-3 text-lg font-semibold text-white
                       bg-blue-600 rounded-xl shadow-lg hover:bg-blue-700
                       hover:shadow-2xl hover:scale-[1.05] transition-all">
                {{ $buttonText ?? 'Click Here' }}
            </a>
        @endif
    </div>
@break


    {{-- ======================================================================
    CODE BLOCK
====================================================================== --}}
    @case('code')
        <div class="my-6">
            <pre class="p-4 overflow-x-auto text-sm text-white bg-gray-800 rounded-lg shadow-md">
        <code>{{ $content }}</code>
    </pre>
        </div>
    @break

    {{-- ======================================================================
    TABLE (FIXED)
====================================================================== --}}
  @case('table')
    @php
        $tableData = $block['data'] ?? [];
    @endphp

    @if (!empty($tableData) && is_array($tableData))
        @php
            $headers = $tableData[0] ?? [];
            $rows = array_slice($tableData, 1);
        @endphp

        <div class="my-8 overflow-x-auto rounded-xl shadow-md border border-gray-300 bg-white">
            <table class="min-w-full text-sm text-gray-800 border border-gray-300">

                {{-- Header --}}
                <thead class="bg-[#013954] text-white">
                    <tr>
                        @foreach ($headers as $header)
                            <th class="px-6 py-3 font-semibold uppercase tracking-wide text-left border border-[#013954]">
                                {{-- Check: Agar Header array hai (new format) ya string (old format) --}}
                                @if(is_array($header))
                                    @if(!empty($header['href']))
                                        <a href="{{ $header['href'] }}" target="_blank" class="hover:underline">{{ $header['text'] ?? '' }}</a>
                                    @else
                                        {{ $header['text'] ?? '' }}
                                    @endif
                                @else
                                    {{ $header }}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>

                {{-- Body --}}
                <tbody>
                    @foreach ($rows as $index => $row)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition">
                            @foreach ($row as $cell)
                                <td class="px-6 py-4 align-top leading-relaxed border border-gray-300">
                                    <div class="prose prose-sm max-w-none">

                                        @if(is_array($cell))
                                            {{-- Support for Links --}}
                                            @php $cellLink = !empty($cell['href']) ? $cell['href'] : null; @endphp

                                            @if($cellLink)
                                                <a href="{{ $cellLink }}" target="_blank" class="hover:underline text-blue-600 block">
                                            @endif

                                            {{-- 1. New Format: Image Check --}}
                                            @if(!empty($cell['img']))
                                                <div class="mb-3">
                                                    <img src="{{ $cell['img'] }}"
                                                         alt="Image"
                                                         class="w-24 h-24 object-cover rounded-lg border border-gray-200 shadow-sm block">
                                                </div>
                                            @endif

                                            {{-- 2. New Format: Text --}}
                                            {!! nl2br(e($cell['text'] ?? '')) !!}

                                            @if($cellLink)
                                                </a>
                                            @endif
                                        @else
                                            {{-- 3. Old Format (Backward Compatibility) --}}
                                            {!! nl2br(e($cell)) !!}
                                        @endif

                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif
@break

    {{-- ======================================================================
    🖼️ PHOTO GALLERY (NEW)
    ====================================================================== --}}
    @case('photo_gallery')
        @php
            $cols = $block['columns_desktop'] ?? 4;
            $gridClass = match ((int)$cols) {
                2 => 'md:grid-cols-2',
                3 => 'md:grid-cols-3',
                5 => 'md:grid-cols-5',
                6 => 'md:grid-cols-6',
                default => 'md:grid-cols-4',
            };
            $images = $block['images'] ?? [];
        @endphp

        @if (!empty($images))
            <div class="my-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-4 lg:gap-6">
                    @foreach ($images as $img)
                        <div class="group relative flex flex-col items-center bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                            
                            {{-- Photo --}}
                            @if (!empty($img['src']))
                                <div class="relative w-full aspect-square overflow-hidden bg-gray-100">
                                    <img src="{{ $img['src'] }}" 
                                         alt="{{ $img['caption'] ?? 'Gallery Image' }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                         loading="lazy">
                                    
                                    {{-- Hover Overlay --}}
                                    <div class="absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                        @if (!empty($img['caption']))
                                            <p class="text-white text-xs font-medium line-clamp-2">
                                                {{ $img['caption'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Optional Visible Caption (Mobile/Accessibility) --}}
                            @if (!empty($img['caption']))
                                <div class="md:hidden p-3 w-full text-center">
                                    <p class="text-[11px] text-gray-500 font-medium italic">
                                        {{ $img['caption'] }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @break

    {{-- ======================================================================
    👥 STAFF GRID (NEW)
    ====================================================================== --}}
    @case('staff_grid')
        @php
            $cols = $block['columns_desktop'] ?? 4;
            $gridClass = match ((int)$cols) {
                3 => 'md:grid-cols-3',
                5 => 'md:grid-cols-5',
                default => 'md:grid-cols-4',
            };
            $profiles = $block['profiles'] ?? [];
        @endphp

        @if (!empty($profiles))
            <div class="my-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridClass }} gap-6 lg:gap-8">
                    @foreach ($profiles as $person)
                        <div class="group flex flex-col items-center p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:border-blue-100 transition-all duration-300">
                            
                            {{-- Profile Photo --}}
                            @if (!empty($person['photo']))
                                <div class="relative mb-4 w-32 h-32 lg:w-40 lg:h-40 shrink-0">
                                    <div class="absolute inset-0 bg-blue-600 rounded-full scale-[1.05] opacity-0 group-hover:opacity-10 transition-opacity"></div>
                                    <img src="{{ $person['photo'] }}" 
                                         alt="{{ $person['name'] }}"
                                         class="w-full h-full object-cover rounded-full border-4 border-white shadow-md group-hover:scale-[1.02] transition-transform duration-300"
                                         loading="lazy">
                                </div>
                            @endif

                            {{-- Info --}}
                            <div class="text-center w-full">
                                <h4 class="text-lg font-bold text-gray-900 leading-tight mb-1 group-hover:text-blue-700 transition-colors">
                                    {{ $person['name'] }}
                                </h4>
                                
                                @if (!empty($person['designation']))
                                    <p class="text-sm font-semibold text-blue-600 uppercase tracking-wider mb-2">
                                        {{ $person['designation'] }}
                                    </p>
                                @endif

                                @if (!empty($person['qualification']))
                                    <div class="inline-block px-3 py-1 bg-gray-50 text-gray-500 text-xs font-medium rounded-full border border-gray-100">
                                        {{ $person['qualification'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @break

    {{-- ======================================================================
    DEFAULT
====================================================================== --}}

    @default
        <div class="p-4 text-sm text-gray-500 bg-gray-100 rounded-lg">
            Unknown block type: <strong>{{ $type }}</strong>
        </div>

@endswitch
