<style>
    /* ========================================================
       🎨 LOADER THEME COLORS
       Aapka red theme color yahan define kiya gaya hai
       ======================================================== */
    :root {
        --loader-theme: var(--primary-color, #000165);
        --loader-theme-light: color-mix(in srgb, var(--primary-color, #000165) 15%, transparent);
    }

    /* Full Screen Overlay */
    #global-loader {
        position: fixed;
        z-index: 9999999;
        /* Topmost layer */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.6s ease-out;
    }

    /* Hidden State */
    .loader-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
    }

    /* Container for ring and logo */
    .loader-container {
        position: relative;
        width: 120px;
        /* Slightly larger for a premium feel */
        height: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* 🌟 Premium Double Rotating Ring */
    .loader-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid var(--loader-theme-light);
        /* Light base ring */
        border-top: 3px solid var(--loader-theme);
        /* Main Brand Color Ring */
        border-bottom: 3px solid var(--loader-theme);
        box-shadow: 0 0 20px rgba(0, 1, 101, 0.2);
        animation: spin-glow 1.5s ease-in-out infinite;
    }

    /* Inner Ring (Spins in opposite direction) */
    .loader-ring::before {
        content: "";
        position: absolute;
        top: 8px;
        left: 8px;
        right: 8px;
        bottom: 8px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-left: 3px solid var(--loader-theme);
        border-right: 3px solid var(--loader-theme);
        opacity: 0.6;
        animation: spin-reverse 2s linear infinite;
    }

    /* 🌟 Beep/Blink Logo inside */
    .loader-logo {
        width: 50%;
        /* Logo size relative to ring */
        height: 50%;
        object-fit: contain;
        border-radius: 50%;
        position: relative;
        z-index: 2;
        /* Beep/Blink animation */
        animation: beepBlink 1.2s infinite;
    }

    /* Animations */
    @keyframes spin-glow {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes spin-reverse {
        0% {
            transform: rotate(360deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    /* Fade In Out (Beep effect) */
    @keyframes beepBlink {
        0% {
            transform: scale(0.85);
            opacity: 0.2;
        }

        50% {
            transform: scale(1.15);
            opacity: 1;
            filter: drop-shadow(0 0 12px rgba(0, 1, 101, 0.4));
        }

        100% {
            transform: scale(0.85);
            opacity: 0.2;
        }
    }

    /* Lock Scroll initially */
    body.loading-active {
        overflow: hidden !important;
    }
</style>

<div id="global-loader">
    <div class="loader-container">
        <div class="loader-ring"></div>
        {{-- College Logo Fetch --}}
        <img src="{{ asset('storage/' . setting('college_logo')) }}" alt="Loading..." class="loader-logo">
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.body.classList.add('loading-active');
    });

    window.addEventListener("load", function () {
        const loader = document.getElementById("global-loader");
        const body = document.body;

        // Thoda delay taaki smooth lage
        setTimeout(function () {
            loader.classList.add("loader-hidden");
            body.classList.remove("loading-active");

            // Remove from DOM after fade out
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300); // Wait for the transition duration
        }, 400);
    });
</script>
