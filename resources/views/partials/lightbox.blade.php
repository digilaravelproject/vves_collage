{{-- Premium Global Lightbox --}}
<div id="global-lightbox" 
     class="fixed inset-0 z-9999 bg-black/90 backdrop-blur-sm hidden items-center justify-center p-4 md:p-10 transition-all duration-300 opacity-0"
     onclick="closeGlobalLightbox()"
     onkeydown="if(event.key === 'Escape') closeGlobalLightbox()"
     tabindex="0">
    
    {{-- Close Button --}}
    <button class="absolute top-6 right-6 z-10000 text-white/70 hover:text-white transition-colors p-2 bg-white/10 hover:bg-white/20 rounded-full"
            onclick="closeGlobalLightbox()">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    {{-- Content Wrapper --}}
    <div class="relative max-w-7xl w-full h-full flex flex-col items-center justify-center pointer-events-none">
        <img id="global-lightbox-img" 
             src="" 
             class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl scale-95 transition-transform duration-500 pointer-events-auto"
             alt="Zoomed Image">
        
        <div id="global-lightbox-caption" 
             class="mt-6 text-white text-center text-lg font-medium bg-black/40 px-6 py-2 rounded-full backdrop-blur-md opacity-0 transition-opacity duration-500 pointer-events-auto">
        </div>
    </div>
</div>

<script>
    function openGlobalLightbox(src, caption = '') {
        const lightbox = document.getElementById('global-lightbox');
        const img = document.getElementById('global-lightbox-img');
        const captionEl = document.getElementById('global-lightbox-caption');

        img.src = src;
        if (caption) {
            captionEl.innerText = caption;
            captionEl.classList.remove('hidden');
            captionEl.classList.add('opacity-100');
        } else {
            captionEl.innerText = '';
            captionEl.classList.add('hidden');
            captionEl.classList.remove('opacity-100');
        }

        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        
        // Trigger animations
        setTimeout(() => {
            lightbox.classList.add('opacity-100');
            img.classList.replace('scale-95', 'scale-100');
        }, 10);

        // Trap focus
        lightbox.focus();
        
        // Prevent scroll
        document.body.style.overflow = 'hidden';
    }

    function closeGlobalLightbox() {
        const lightbox = document.getElementById('global-lightbox');
        const img = document.getElementById('global-lightbox-img');
        
        lightbox.classList.remove('opacity-100');
        img.classList.replace('scale-100', 'scale-95');
        
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.style.overflow = '';
        }, 300);
    }
</script>
