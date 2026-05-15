@props(['src', 'height' => '700px'])

@php
    $uniqueId = 'pdf_v_' . uniqid();
    $finalSrc = $src;
    // Add view=raw if not present for local files
    if (str_contains($finalSrc, 'storage/') && !str_contains($finalSrc, 'view=raw')) {
        $finalSrc .= (str_contains($finalSrc, '?') ? '&' : '?') . 'view=raw';
    }
@endphp

<div class="pdf-viewer-wrapper-{{ $uniqueId }} my-4 w-full border rounded-lg overflow-hidden bg-gray-50 relative" style="height: {{ $height }};">
    <div id="{{ $uniqueId }}_container" class="w-full h-full overflow-auto custom-scrollbar" data-pdf-url="{{ $finalSrc }}">
        <div id="{{ $uniqueId }}_loading" class="flex flex-col items-center justify-center h-full bg-white z-20">
            <div class="animate-pulse flex flex-col items-center">
                <div class="h-10 w-10 bg-gray-200 rounded-full mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-32 mb-2"></div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Loading Document</p>
            </div>
        </div>
    </div>
</div>

@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    window.pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    if (!window.pdfJsGlobalProtectionAdded) {
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 's' || e.key === 'c')) {
                e.preventDefault();
            }
        });
        window.pdfJsGlobalProtectionAdded = true;
    }
</script>
@endonce

<script>
    (function() {
        const container = document.getElementById('{{ $uniqueId }}_container');
        const loading = document.getElementById('{{ $uniqueId }}_loading');
        const url = container.dataset.pdfUrl;
        let pdfDoc = null;
        let renderedPages = new Set();
        const scale = window.devicePixelRatio || 1;
        const baseScale = window.innerWidth < 768 ? 1.0 : 1.4;

        container.addEventListener('contextmenu', e => e.preventDefault());
        container.style.userSelect = 'none';

        function renderPage(num) {
            if (renderedPages.has(num)) return;
            renderedPages.add(num);

            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({ scale: baseScale * scale });
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.className = 'w-full h-auto mb-6 shadow-md mx-auto block bg-white';
                canvas.style.maxWidth = '100%';

                const pageWrapper = document.createElement('div');
                pageWrapper.className = 'pdf-page-container p-2 md:p-6';
                pageWrapper.setAttribute('data-page-number', num);
                pageWrapper.appendChild(canvas);
                container.appendChild(pageWrapper);

                page.render({ canvasContext: ctx, viewport: viewport });
            });
        }

        function initViewer() {
            pdfjsLib.getDocument(url).promise.then(pdf => {
                pdfDoc = pdf;
                if (loading) loading.remove();
                
                // Initial pages
                for (let i = 1; i <= Math.min(3, pdf.numPages); i++) {
                    renderPage(i);
                }

                // Lazy load remaining pages
                container.addEventListener('scroll', () => {
                    if (renderedPages.size >= pdf.numPages) return;
                    
                    const scrollBottom = container.scrollTop + container.clientHeight;
                    if (scrollBottom > container.scrollHeight - 1200) {
                        for (let i = 1; i <= pdf.numPages; i++) {
                            if (!renderedPages.has(i)) {
                                renderPage(i);
                                break;
                            }
                        }
                    }
                });
            }).catch(err => {
                console.error('PDF JS Error:', err);
                if (loading) loading.innerHTML = '<div class="p-10 text-red-500 font-bold">Failed to load PDF document.</div>';
            });
        }

        // Use IntersectionObserver to start loading only when visible
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                initViewer();
                observer.unobserve(container);
            }
        }, { rootMargin: '200px' });
        
        observer.observe(container);
    })();
</script>