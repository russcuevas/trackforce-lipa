<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce Lipa</title>
    <script>
        document.documentElement.classList.add('js');
    </script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        :root {
            --tf-blue: #0B3D91;
            --tf-blue-dark: #08275e;
            --tf-red: #CE1126;
            --tf-ink: #0f172a;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(206, 17, 38, 0.12), transparent 28%),
                radial-gradient(circle at top right, rgba(11, 61, 145, 0.12), transparent 30%),
                #f8fafc;
            color: var(--tf-ink);
        }

        .bg-tf-blue {
            background-color: #0B3D91;
        }

        .bg-tf-red {
            background-color: #CE1126;
        }

        .text-tf-blue {
            color: #0B3D91;
        }

        .text-tf-red {
            color: #CE1126;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0B3D91 0%, #1e4ba1 100%);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(18px);
        }

        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at center, rgba(255, 255, 255, 0.16), transparent 45%),
                linear-gradient(135deg, rgba(8, 39, 94, 0.98), rgba(11, 61, 145, 0.94) 55%, rgba(206, 17, 38, 0.9));
            transition: opacity 0.45s ease, visibility 0.45s ease;
        }

        .page-loader.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .loader-core {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            text-align: center;
        }

        .loader-rings {
            position: relative;
            width: 8rem;
            height: 8rem;
        }

        .loader-rings span {
            position: absolute;
            inset: 0;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 9999px;
            animation: loaderPulse 1.9s ease-out infinite;
        }

        .loader-rings span:nth-child(2) {
            inset: 0.75rem;
            animation-delay: 0.22s;
        }

        .loader-rings span:nth-child(3) {
            inset: 1.5rem;
            animation-delay: 0.44s;
        }

        .loader-logo {
            position: absolute;
            inset: 50%;
            width: 3.75rem;
            height: 3.75rem;
            object-fit: contain;
            transform: translate(-50%, -50%);
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.28));
            animation: loaderFloat 1.8s ease-in-out infinite;
        }

        .loader-label {
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.34em;
            text-transform: uppercase;
        }

        .nav-link {
            position: relative;
            transition: all 0.3s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #CE1126;
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .mobile-menu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transform: translateY(-8px);
            transition: max-height 0.35s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.25s ease, transform 0.35s ease;
        }

        .mobile-menu.open {
            max-height: 320px;
            opacity: 1;
            transform: translateY(0);
        }

        .hero-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(10px);
            opacity: 0.32;
            pointer-events: none;
            animation: floatOrb 10s ease-in-out infinite;
        }

        .hero-orb-one {
            width: 17rem;
            height: 17rem;
            top: -4rem;
            right: -3rem;
            background: rgba(255, 212, 59, 0.22);
        }

        .hero-orb-two {
            width: 12rem;
            height: 12rem;
            bottom: -3rem;
            left: -2rem;
            background: rgba(206, 17, 38, 0.18);
            animation-delay: -4s;
        }

        .interactive-panel {
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease,
                background-color 0.35s ease;
        }

        .interactive-panel:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 50px -24px rgba(11, 61, 145, 0.32);
        }

        .lift-button {
            transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
        }

        .lift-button:hover {
            transform: translateY(-3px);
        }

        .js [data-reveal] {
            opacity: 0;
            transform: translateY(28px) scale(0.98);
            transition: opacity 0.75s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.75s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .js [data-reveal].is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .js [data-reveal-delay="1"] {
            transition-delay: 0.08s;
        }

        .js [data-reveal-delay="2"] {
            transition-delay: 0.16s;
        }

        .js [data-reveal-delay="3"] {
            transition-delay: 0.24s;
        }

        @keyframes loaderPulse {
            0% {
                transform: scale(0.86);
                opacity: 0;
            }

            40% {
                opacity: 1;
            }

            100% {
                transform: scale(1.08);
                opacity: 0;
            }
        }

        @keyframes loaderFloat {

            0%,
            100% {
                transform: translate(-50%, -50%);
            }

            50% {
                transform: translate(-50%, calc(-50% - 8px));
            }
        }

        @keyframes floatOrb {

            0%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            50% {
                transform: translate3d(0, 18px, 0);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .page-loader,
            .page-loader *,
            [data-reveal],
            .interactive-panel,
            .lift-button,
            .hero-orb {
                animation: none !important;
                transition: none !important;
            }

            .js [data-reveal] {
                opacity: 1;
                transform: none;
            }
        }
    </style>
    <noscript>
        <style>
            #pageLoader {
                display: none !important;
            }
        </style>
    </noscript>
</head>

<body class="min-h-screen flex flex-col">
    <div id="pageLoader" class="page-loader" role="status" aria-live="polite">
        <div class="loader-core">
            <div class="loader-rings">
                <span></span>
                <span></span>
                <span></span>
                <img src="{{ asset('images/logo.png') }}" alt="TrackForce Lipa" class="loader-logo">
            </div>
            <p id="pageLoaderLabel" class="loader-label">Preparing safer journeys</p>
        </div>
    </div>

    <nav class="glass-nav border-b sticky top-0 z-50" data-reveal>
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
                <div class="leading-none">
                    <span class="font-black text-tf-blue tracking-tighter text-xl uppercase block">TRACKFORCE
                        LIPA</span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home.page') }}"
                    class="nav-link font-bold text-tf-blue text-sm uppercase tracking-wider">Home</a>
                <a href="{{ route('track.case.page') }}"
                    class="nav-link font-bold text-gray-500 hover:text-tf-blue text-sm uppercase tracking-wider">Track
                    Case</a>
                <a href="{{ route('report.page') }}"
                    class="bg-tf-red text-white px-6 py-2.5 rounded-full font-black text-xs uppercase shadow-lg shadow-red-200 hover:bg-red-700 transition-all">
                    Report Incident
                </a>
            </div>

            <button id="mobileMenuButton" type="button" class="md:hidden text-tf-blue text-2xl"
                aria-label="Toggle menu" aria-controls="mobileMenu" aria-expanded="false">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </div>

        <div id="mobileMenu" class="mobile-menu md:hidden border-t border-gray-100 bg-white px-6">
            <div class="py-4 flex flex-col gap-3">
                <a href="{{ route('home.page') }}"
                    class="font-bold text-tf-blue text-sm uppercase tracking-wider py-2">Home</a>
                <a href="{{ route('track.case.page') }}"
                    class="font-bold text-gray-500 hover:text-tf-blue text-sm uppercase tracking-wider py-2">Track
                    Case</a>
                <a href="{{ route('report.page') }}"
                    class="bg-tf-red text-white px-6 py-3 rounded-xl font-black text-xs uppercase shadow-lg shadow-red-200 hover:bg-red-700 transition-all text-center mt-1">
                    Report Incident
                </a>
            </div>
        </div>
    </nav>

    <header class="hero-gradient py-20 px-6 relative overflow-hidden">
        <div class="hero-orb hero-orb-one"></div>
        <div class="hero-orb hero-orb-two"></div>
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="text-center lg:text-left" data-reveal data-reveal-delay="1">
                <span
                    class="inline-block bg-white/10 text-white border border-white/20 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-6">
                    Lipa City Safety Initiative
                </span>
                <h1 class="text-white text-4xl md:text-6xl font-black mb-6 leading-tight">
                    Ensuring Safer Roads <br>for every <span class="text-yellow-400">Lipeño.</span>
                </h1>
                <p class="text-blue-100 text-lg mb-10 max-w-xl mx-auto lg:mx-0">
                    TrackForce Lipa is the official digital bridge between the community and the PNP. Report traffic
                    incidents and track case resolutions in real-time.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                    <a href="{{ route('report.page') }}"
                        class="w-full sm:w-auto bg-tf-red text-white px-10 py-4 rounded-2xl font-black shadow-2xl shadow-red-900/40 hover:bg-red-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                        REPORT AN INCIDENT <i class="fa-solid fa-paper-plane"></i>
                    </a>
                    <a href="{{ route('track.case.page') }}"
                        class="w-full sm:w-auto bg-white/10 backdrop-blur-md text-white border border-white/30 px-10 py-4 rounded-2xl font-bold hover:bg-white/20 transition-all text-center">
                        Track Case
                    </a>
                </div>
            </div>

            <div class="hidden lg:flex justify-center relative" data-reveal data-reveal-delay="3">
                <div class="absolute inset-0 bg-tf-red rounded-full filter blur-[120px] opacity-20 animate-pulse"></div>
                <div
                    class="bg-white/5 backdrop-blur-xl border border-white/10 p-4 rounded-3xl shadow-2xl rotate-3 interactive-panel">
                    <img src="https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&q=80&w=600"
                        alt="Lipa City Map" class="rounded-2xl shadow-inner w-full h-[400px] object-cover">
                </div>
            </div>
        </div>
    </header>

    <section class="py-20 px-6 bg-white relative overflow-hidden" data-reveal>
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-8 rounded-3xl hover:bg-gray-50 transition-colors interactive-panel" data-reveal
                    data-reveal-delay="1">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-bolt-lightning text-tf-blue text-2xl"></i>
                    </div>
                    <h3 class="font-black text-tf-blue text-xl mb-3 uppercase">Fast Reporting</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Submit evidence, locations, and vehicle details in
                        under 2 minutes without an account.</p>
                </div>

                <div class="p-8 rounded-3xl hover:bg-gray-50 transition-colors interactive-panel" data-reveal
                    data-reveal-delay="2">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-location-crosshairs text-tf-red text-2xl"></i>
                    </div>
                    <h3 class="font-black text-tf-blue text-xl mb-3 uppercase">GPS Tracking</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Pinpoint exact incident coordinates using our
                        integrated map system for faster response.</p>
                </div>

                <div class="p-8 rounded-3xl hover:bg-gray-50 transition-colors interactive-panel" data-reveal
                    data-reveal-delay="3">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-magnifying-glass-chart text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="font-black text-tf-blue text-xl mb-3 uppercase">Transparency</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Use your unique TFL ID to monitor the
                        investigation
                        status of your report from any device.</p>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        (function() {
            const loader = document.getElementById('pageLoader');
            const loaderLabel = document.getElementById('pageLoaderLabel');
            const menuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            function showPageLoader(message) {
                if (!loader) {
                    return;
                }

                if (message && loaderLabel) {
                    loaderLabel.textContent = message;
                }

                loader.classList.remove('is-hidden');
            }

            function hidePageLoader() {
                if (!loader) {
                    return;
                }

                loader.classList.add('is-hidden');
            }

            function initRevealAnimations() {
                const revealElements = document.querySelectorAll('[data-reveal]');

                if (!revealElements.length) {
                    return;
                }

                if (prefersReducedMotion || !('IntersectionObserver' in window)) {
                    revealElements.forEach((element) => element.classList.add('is-visible'));
                    return;
                }

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.16,
                    rootMargin: '0px 0px -40px 0px'
                });

                revealElements.forEach((element) => observer.observe(element));
            }

            function initPageTransitions() {
                document.querySelectorAll('a[href]').forEach((link) => {
                    link.addEventListener('click', (event) => {
                        const href = link.getAttribute('href');

                        if (!href || href.startsWith('#') || link.target === '_blank' || link
                            .hasAttribute('download')) {
                            return;
                        }

                        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                            return;
                        }

                        const url = new URL(link.href, window.location.href);

                        if (url.origin !== window.location.origin) {
                            return;
                        }

                        showPageLoader(link.dataset.loaderText || 'Opening TrackForce Lipa');
                    });
                });
            }

            window.showPageLoader = showPageLoader;
            window.hidePageLoader = hidePageLoader;

            if (!menuButton || !mobileMenu) {
                initRevealAnimations();
                initPageTransitions();
                window.addEventListener('load', function() {
                    window.setTimeout(hidePageLoader, prefersReducedMotion ? 0 : 420);
                });
                return;
            }

            const menuIcon = menuButton.querySelector('i');

            function setMenuState(isOpen) {
                mobileMenu.classList.toggle('open', isOpen);
                menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                if (menuIcon) {
                    menuIcon.classList.toggle('fa-bars-staggered', !isOpen);
                    menuIcon.classList.toggle('fa-xmark', isOpen);
                }
            }

            menuButton.addEventListener('click', function() {
                setMenuState(!mobileMenu.classList.contains('open'));
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    setMenuState(false);
                }
            });

            initRevealAnimations();
            initPageTransitions();

            window.addEventListener('load', function() {
                window.setTimeout(hidePageLoader, prefersReducedMotion ? 0 : 420);
            });
        })();
    </script>
    <script>
        (function() {
            const notyf = new Notyf({
                duration: 5000,
                position: {
                    x: 'right',
                    y: 'top'
                },
                dismissible: true,
                types: [{
                        type: 'success',
                        background: '#198754',
                        icon: {
                            className: 'fa-solid fa-circle-check',
                            tagName: 'i',
                            color: 'white'
                        }
                    },
                    {
                        type: 'error',
                        background: '#dc3545',
                        icon: {
                            className: 'fa-solid fa-triangle-exclamation',
                            tagName: 'i',
                            color: 'white'
                        }
                    }
                ]
            });

            const redirectedSuccess = localStorage.getItem('tfFlashSuccess');
            if (redirectedSuccess) {
                notyf.open({
                    type: 'success',
                    message: redirectedSuccess
                });
                localStorage.removeItem('tfFlashSuccess');
            }

            @if (session('success'))
                notyf.open({
                    type: 'success',
                    message: @json(session('success'))
                });
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    notyf.open({
                        type: 'error',
                        message: @json($error)
                    });
                @endforeach
            @endif
        })();
    </script>
</body>

</html>
