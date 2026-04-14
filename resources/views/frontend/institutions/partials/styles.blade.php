<style>
    :root {
        /* Premium Standard Theme Colors */
        --theme-navy: #1E234B;
        --theme-yellow: #FFD700;
        --theme-bg: #F8F9FA;
        --card-radius: 16px;
        --transition-timing: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Force Syne Font and Global Typography Reset */
    * {
        font-family: 'Syne', sans-serif !important;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 700;
        letter-spacing: -0.01em;
        text-transform: none !important;
    }

    /* Scroll wrappers for organized slider */
    /*.no-scrollbar::-webkit-scrollbar { display: none; }*/
    /*.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }*/

    /* 🌊 Premium Thin Scrollbar for Tab Slider */
    #category-scroll::-webkit-scrollbar {
        height: 4px;
    }

    #category-scroll::-webkit-scrollbar-track {
        background: transparent;
        margin: 0 20px;
    }

    #category-scroll::-webkit-scrollbar-thumb {
        background: #000165;
        border-radius: 10px;
        transition: background 0.3s;
    }

    #category-scroll:hover::-webkit-scrollbar-thumb {
        background: #000165;
    }

    .slider-track {
        @apply bg-white rounded-4xl p-2 relative overflow-visible shadow-sm border border-gray-100;
        min-height: 70px;
        display: flex;
        align-items: center;
    }

    /* 🖼️ Rich Text Content Image Handling */
    .rich-text-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem auto;
        display: block;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .rich-text-content p {
        margin-bottom: 1rem;
    }

    .scroll-container-wrapper {
        position: relative;
        width: 100%;
        border-radius: 1.5rem;
        overflow: hidden;
    }

    .scroll-container-wrapper::after,
    .scroll-container-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 80px;
        z-index: 20;
        pointer-events: none;
        transition: all 0.4s ease;
    }

    .scroll-container-wrapper::before {
        left: 0;
        background: linear-gradient(to right, rgba(255, 255, 255, 0.9) 0%, transparent 100%);
        opacity: 0;
        transform: translateX(-10px);
    }

    .scroll-container-wrapper::after {
        right: 0;
        background: linear-gradient(to left, rgba(255, 255, 255, 0.9) 0%, transparent 100%);
        opacity: 0;
        transform: translateX(10px);
    }

    .has-scroll-left.scroll-container-wrapper::before {
        opacity: 1;
        transform: translateX(0);
    }

    .has-scroll-right.scroll-container-wrapper::after {
        opacity: 1;
        transform: translateX(0);
    }

    .drag-active {
        cursor: grabbing !important;
        user-select: none;
    }

    .staff-card-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 100%;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .staff-avatar-placeholder {
        background: linear-gradient(135deg, #f8f9fa 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    /* Custom prose for Syne */
    .prose * {
        font-family: 'Syne', sans-serif !important;
        color: #4b5563;
    }

    .prose strong {
        color: var(--theme-navy);
        font-weight: 700;
    }

    /* 🎨 Premium Marquee Slider Styles */
    .marquee-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        overflow: hidden;
        user-select: none;
        padding: 0.5rem 0;
    }

    .marquee-track {
        display: flex;
        gap: 1rem;
        width: max-content;
        transition: animation-play-state 0.3s ease;
    }

    .marquee-item {
        flex-shrink: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.5s var(--transition-timing);
        cursor: pointer;
        position: relative;
        background: #F8F9FA;
    }

    .marquee-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s var(--transition-timing);
    }

    .marquee-item:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        z-index: 10;
        border: 2px solid var(--theme-yellow);
    }

    .marquee-item:hover img {
        transform: scale(1.1);
    }

    /* Animation Definitions */
    .animate-scroll-left {
        animation: scrollLeft 20s linear infinite;
    }

    .animate-scroll-right {
        animation: scrollRight 20s linear infinite;
    }

    /* Pause on tracker hover */
    .marquee-track:hover {
        animation-play-state: paused !important;
    }

    @keyframes scrollLeft {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(calc(-50% - 0.75rem));
        }
    }

    @keyframes scrollRight {
        0% {
            transform: translateX(calc(-50% - 0.75rem));
        }

        100% {
            transform: translateX(0);
        }
    }

    @media (max-width: 768px) {
        .marquee-wrapper {
            gap: 1rem;
        }

        .marquee-track {
            gap: 1rem;
        }
    }
</style>
