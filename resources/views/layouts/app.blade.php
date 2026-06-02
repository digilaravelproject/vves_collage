<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Permissions-Policy" content="unload=(self *)">
    <script>
        /**
         * Browser 'unload' Event Interceptor
         * Prevents third-party scripts (like Instagram/Facebook) from triggering 'unload' violations.
         * Redirects 'unload' to 'pagehide' for better bfcache support and modern browser compatibility.
         */
        (function() {
            const originalAddEventListener = window.addEventListener;
            window.addEventListener = function(type, listener, options) {
                if (type === 'unload') {
                    // Silently redirect to pagehide to avoid console clutter while maintaining functionality
                    return originalAddEventListener.call(this, 'pagehide', listener, options);
                }
                return originalAddEventListener.apply(this, arguments);
            };

            Object.defineProperty(window, 'onunload', {
                set: function(fn) {
                    if (typeof fn === 'function') {
                        window.addEventListener('pagehide', fn);
                    }
                },
                configurable: true
            });
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- SEO & Styles (keep these as they are) --}}
    {{-- ... (meta tags, title, description, favicon) ... --}}
    <link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}" type="image/image">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @php
        $seoTitle = setting('meta_title');
        $seoDescription = setting('meta_description');
        $seoImage = setting('meta_image');
        $pageTitle = $seoTitle ?: setting('college_name', config('app.name'));
    @endphp

    <title>@yield('title', $pageTitle)</title>
    <meta name="description" content="@yield('description', $seoDescription)">
    <meta name="author" content="{{ setting('college_name') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', $pageTitle)">
    <meta property="og:description" content="@yield('description', $seoDescription)">
    @if ($seoImage)
        <meta property="og:image" content="{{ asset('storage/' . $seoImage) }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', $pageTitle)">
    <meta name="twitter:description" content="@yield('description', $seoDescription)">
    @if ($seoImage)
        <meta name="twitter:image" content="{{ asset('storage/' . $seoImage) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #000165;
            --primary-hover: #00014d;
        }

        body {
            font-family: 'Syne', sans-serif !important;
            font-size: 0.95rem;
            font-weight: 400;
            color: #000000;
            line-height: 1.6;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 700 !important;
            color: inherit;
            letter-spacing: -0.01em;
        }

        /* Quill Rich Text Compatibility & Global Rich Text Content Styling */
        .prose .ql-align-center, .rich-text-content .ql-align-center,
        .prose .text-center, .rich-text-content .text-center,
        .prose [style*="text-align: center"], .rich-text-content [style*="text-align: center"] {
            text-align: center !important;
        }

        .prose .ql-align-right, .rich-text-content .ql-align-right,
        .prose .text-right, .rich-text-content .text-right,
        .prose [style*="text-align: right"], .rich-text-content [style*="text-align: right"] {
            text-align: right !important;
        }

        .prose .ql-align-justify, .rich-text-content .ql-align-justify,
        .prose .text-justify, .rich-text-content .text-justify,
        .prose [style*="text-align: justify"], .rich-text-content [style*="text-align: justify"] {
            text-align: justify !important;
        }

        .prose .ql-align-left, .rich-text-content .ql-align-left,
        .prose .text-left, .rich-text-content .text-left,
        .prose [style*="text-align: left"], .rich-text-content [style*="text-align: left"] {
            text-align: left !important;
        }

        /* Bold & Strong */
        .prose strong, .rich-text-content strong,
        .prose b, .rich-text-content b {
            font-weight: bold !important;
            font-weight: 700 !important;
        }

        /* Underline & Italic */
        .prose u, .rich-text-content u {
            text-decoration: underline !important;
        }
        .prose em, .rich-text-content em,
        .prose i, .rich-text-content i {
            font-style: italic !important;
        }

        /* Font Sizes */
        .prose .ql-size-small, .rich-text-content .ql-size-small { font-size: 0.75em !important; }
        .prose .ql-size-large, .rich-text-content .ql-size-large { font-size: 1.5em !important; }
        .prose .ql-size-huge, .rich-text-content .ql-size-huge { font-size: 2.5em !important; }

        /* Lists Formatting */
        .prose ul, .rich-text-content ul,
        .prose ol, .rich-text-content ol {
            padding-left: 2.5rem !important;
            margin-top: 0.75rem !important;
            margin-bottom: 0.75rem !important;
        }
        .prose ul, .rich-text-content ul {
            list-style-type: disc !important;
        }
        .prose ol, .rich-text-content ol {
            list-style-type: decimal !important;
        }
        .prose li, .rich-text-content li {
            display: list-item !important;
            list-style: inherit !important;
            margin-bottom: 0.25rem !important;
        }

        /* Blockquotes */
        .prose blockquote, .rich-text-content blockquote {
            border-left: 4px solid #ccc !important;
            padding-left: 1rem !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            font-style: italic !important;
        }

        /* Image Styling */
        .prose img, .rich-text-content img {
            display: inline-block !important;
            max-width: 100% !important;
            height: auto !important;
        }

        /* Paragraph margins */
        .prose p, .rich-text-content p {
            margin-bottom: 1rem !important;
        }
    </style>
    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-gray-50 font-sans antialiased overflow-x-hidden loading-active">

    @include('partials.loader')
    {{-- @include('partials.top-banner') --}}
    {{-- @include('partials.menu') --}}
    @if(!request()->query('minimal'))
        @include('partials.header')
    @endif

    <main class="grow overflow-x-hidden lg:overflow-x-visible">
        @yield('content')
    </main>

    @if(!request()->query('minimal'))
        @include('partials.footer')

        {{-- 🌟 Global Lead Generation Elements (Sticky Buttons & Modals) --}}
        <div x-data="leadForms">
            @include('partials.sticky-lead-buttons')
            @include('partials.lead-modals')
            @include('partials.notice-modal')
            @include('partials.lightbox')
        </div>
    @endif

    {{-- Alpine.js is now bundled in app.js --}}

    <style>
        img,
        video,
        embed,
        iframe {
            -webkit-user-drag: none;
            user-drag: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media print {
            body {
                display: none !important;
                visibility: hidden !important;
            }
        }
    </style>

    <script>
        (function () {
            document.addEventListener('keydown', function (event) {
                if (event.ctrlKey || event.metaKey) {
                    if (event.keyCode === 83 || event.keyCode === 80) {
                        event.preventDefault();
                    }
                }
            });

            document.addEventListener('contextmenu', function (event) {
                event.preventDefault();
            });

            document.addEventListener('DOMContentLoaded', function () {
                const videos = document.querySelectorAll('video');
                videos.forEach(function (video) {
                    video.setAttribute('controlslist', 'nodownload');
                });

                const pdfElements = document.querySelectorAll('embed[src*=".pdf"], iframe[src*=".pdf"]');
                pdfElements.forEach(function (el) {
                    if (!el.src.includes('#')) {
                        try {
                            el.src = el.src + '#toolbar=0&navpanes=0';
                        } catch (e) { }
                    }
                });
            });
        })();


    </script>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.AOS) {
                AOS.init({ once: true });
            }
        });
    </script>
</body>

</html>
