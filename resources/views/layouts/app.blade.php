<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- SEO & Styles (keep these as they are) --}}
    {{-- ... (meta tags, title, description, favicon) ... --}}
    <link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}" type="image/image">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
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
        /* Quill Rich Text Compatibility */
        .prose .ql-align-center { text-align: center; }
        .prose .ql-align-right { text-align: right; }
        .prose .ql-align-justify { text-align: justify; }
        .prose .ql-size-small { font-size: 0.75em; }
        .prose .ql-size-large { font-size: 1.5em; }
        .prose .ql-size-huge { font-size: 2.5em; }
        .prose ul, .prose ol { padding-left: 1.5rem !important; }
        .prose blockquote { border-left: 4px solid #ccc; padding-left: 1rem; font-style: italic; }
        .prose img { display: inline-block; margin: 0; }
    </style>
    @stack('styles')
</head>

<body class="flex flex-col min-h-screen bg-gray-50 font-sans antialiased overflow-x-hidden loading-active">

    @include('partials.loader')
    @include('partials.top-banner')
    @include('partials.menu')

    <main class="grow overflow-x-hidden lg:overflow-x-visible">
        @yield('content')
    </main>

    @include('partials.footer')

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

        function leadForms() {
            return {
                applyOpen: false,
                enquireOpen: false,
                otpSent: false,
                otpSending: false,
                otpVerified: false,
                otpFor: null,
                enteredOtp: '',
                sending: false,
                ad: { first_name: '', last_name: '', email: '', mobile_prefix: '+91', mobile_no: '', discipline: '', level: '', programme: '', authorised_contact: false },
                en: { first_name: '', last_name: '', email: '', mobile_prefix: '+91', mobile_no: '', level: '', discipline: '', programme: '', message: '', authorised_contact: false },

                init() { },
                openApply() { this.applyOpen = true; this.otpSent = false; this.otpVerified = false; this.enteredOtp = ''; this.otpFor = null; },
                closeApply() { this.applyOpen = false; },
                openEnquire() { this.enquireOpen = true; this.otpSent = false; this.otpVerified = false; this.enteredOtp = ''; this.otpFor = null; },
                closeEnquire() { this.enquireOpen = false; },

                async sendOtp(type) {
                    this.otpSending = true;
                    const email = (type === 'admission') ? this.ad.email : this.en.email;
                    try {
                        const res = await fetch('{{ route("send.otp") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ email, type })
                        });
                        const json = await res.json();
                        if (json.ok) {
                            this.otpSent = true;
                            this.otpFor = type;
                            alert('OTP sent to ' + email);
                        } else {
                            alert(json.message || 'Failed to send OTP');
                        }
                    } catch (e) {
                        alert('Error sending OTP');
                    } finally { this.otpSending = false; }
                },

                async verifyOtp(type) {
                    try {
                        const email = (type === 'admission') ? this.ad.email : this.en.email;
                        const res = await fetch('{{ route("verify.otp") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ email, otp: this.enteredOtp, type: type })
                        });
                        const json = await res.json();
                        if (json.ok) {
                            this.otpVerified = true;
                            alert('Email verified');
                        } else {
                            alert(json.message || 'Invalid OTP');
                        }
                    } catch (e) {
                        alert('Error verifying OTP');
                    }
                },

                async submitAdmission() {
                    if (!this.otpVerified) { alert('Please verify email first'); return; }
                    this.sending = true;
                    try {
                        const res = await fetch('{{ route("submit.admission") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(this.ad)
                        });
                        const json = await res.json();
                        if (json.ok) {
                            alert('Application submitted');
                            this.closeApply();
                            this.ad = { first_name: '', last_name: '', email: '', mobile_prefix: '+91', mobile_no: '', discipline: '', level: '', programme: '', authorised_contact: false };
                        } else {
                            alert(json.message || 'Submission failed');
                        }
                    } catch (e) {
                        alert('Error submitting');
                    } finally { this.sending = false; }
                },

                async submitEnquiry() {
                    if (!this.otpVerified) { alert('Please verify email first'); return; }
                    this.sending = true;
                    try {
                        const res = await fetch('{{ route("submit.enquiry") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(this.en)
                        });
                        const json = await res.json();
                        if (json.ok) {
                            alert('Enquiry submitted');
                            this.closeEnquire();
                            this.en = { first_name: '', last_name: '', email: '', mobile_prefix: '+91', mobile_no: '', level: '', discipline: '', programme: '', message: '', authorised_contact: false };
                        } else {
                            alert(json.message || 'Submission failed');
                        }
                    } catch (e) {
                        alert('Error submitting');
                    } finally { this.sending = false; }
                }
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.AOS) {
                AOS.init({ once: true });
            }
        });
    </script>
</body>

</html>
