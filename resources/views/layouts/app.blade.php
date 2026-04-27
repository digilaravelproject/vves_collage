<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        /**
         * Browser 'unload' Event Interceptor
         * Prevents third-party scripts (like Instagram/Facebook) from triggering 'unload' violations.
         * Redirects 'unload' to 'pagehide' for better bfcache support.
         */
        (function() {
            const originalAddEventListener = window.addEventListener;
            window.addEventListener = function(type, listener, options) {
                if (type === 'unload') {
                    console.warn('Intercepted "unload" event and redirected to "pagehide" for better browser compatibility.');
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

        /* Quill Rich Text Compatibility */
        .prose .ql-align-center {
            text-align: center;
        }

        .prose .ql-align-right {
            text-align: right;
        }

        .prose .ql-align-justify {
            text-align: justify;
        }

        .prose .ql-size-small {
            font-size: 0.75em;
        }

        .prose .ql-size-large {
            font-size: 1.5em;
        }

        .prose .ql-size-huge {
            font-size: 2.5em;
        }

        .prose ul,
        .prose ol {
            padding-left: 1.5rem !important;
        }

        .prose blockquote {
            border-left: 4px solid #ccc;
            padding-left: 1rem;
            font-style: italic;
        }

        .prose img {
            display: inline-block;
            margin: 0;
        }
    </style>
    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-gray-50 font-sans antialiased overflow-x-hidden loading-active">

    @include('partials.loader')
    {{-- @include('partials.top-banner') --}}
    {{-- @include('partials.menu') --}}
    @include('partials.header')

    <main class="grow overflow-x-hidden lg:overflow-x-visible">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- 🌟 Global Lead Generation Elements (Sticky Buttons & Modals) --}}
    <div x-data="leadForms">
        @include('partials.sticky-lead-buttons')
        @include('partials.lead-modals')
        @include('partials.notice-modal')
    </div>

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

                const pdfElements = document.querySelectorAll('embed[src$=".pdf"], iframe[src$=".pdf"]');
                pdfElements.forEach(function (el) {
                    if (!el.src.includes('#')) {
                        try {
                            el.src = el.src + '#toolbar=0&navpanes=0';
                        } catch (e) { }
                    }

                    if (el.tagName.toLowerCase() === 'iframe') {
                        el.setAttribute('sandbox', 'allow-scripts allow-same-origin');
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
