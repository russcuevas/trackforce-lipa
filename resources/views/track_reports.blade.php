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
    <style>
        :root {
            --tf-blue: #0B3D91;
            --tf-blue-dark: #08275e;
            --tf-red: #CE1126;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(206, 17, 38, 0.09), transparent 26%),
                radial-gradient(circle at top right, rgba(11, 61, 145, 0.11), transparent 30%),
                #f8fafc;
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

        .search-container {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 20px 50px -10px rgba(11, 61, 145, 0.15);
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
                radial-gradient(circle at center, rgba(255, 255, 255, 0.14), transparent 44%),
                linear-gradient(135deg, rgba(8, 39, 94, 0.98), rgba(11, 61, 145, 0.94) 55%, rgba(206, 17, 38, 0.88));
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
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            animation: loaderPulse 1.9s ease-out infinite;
        }

        .loader-rings span:nth-child(2) {
            inset: 0.75rem;
            animation-delay: 0.2s;
        }

        .loader-rings span:nth-child(3) {
            inset: 1.5rem;
            animation-delay: 0.4s;
        }

        .loader-logo {
            position: absolute;
            inset: 50%;
            width: 3.75rem;
            height: 3.75rem;
            object-fit: contain;
            transform: translate(-50%, -50%);
            animation: loaderFloat 1.8s ease-in-out infinite;
        }

        .loader-label {
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.34em;
            text-transform: uppercase;
        }

        .status-badge-pending {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-badge-investigation {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .status-badge-resolved {
            background-color: #dcfce7;
            color: #166534;
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

        .interactive-panel {
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease,
                background-color 0.35s ease;
        }

        .interactive-panel:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 50px -26px rgba(11, 61, 145, 0.3);
        }

        .timeline-step {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .timeline-step:hover {
            transform: translateX(6px);
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

        @media (prefers-reduced-motion: reduce) {

            .page-loader,
            .page-loader *,
            [data-reveal],
            .interactive-panel,
            .timeline-step,
            .lift-button {
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
            <p id="pageLoaderLabel" class="loader-label">Tracking live case status</p>
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
                    class="nav-link font-bold text-gray-500 hover:text-tf-blue text-sm uppercase tracking-wider">Home</a>
                <a href="{{ route('track.case.page') }}"
                    class="nav-link font-bold text-tf-blue text-sm uppercase tracking-wider">Track Case</a>
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
                    class="font-bold text-gray-500 hover:text-tf-blue text-sm uppercase tracking-wider py-2">Home</a>
                <a href="{{ route('track.case.page') }}"
                    class="font-bold text-tf-blue text-sm uppercase tracking-wider py-2">Track Case</a>
                <a href="{{ route('report.page') }}"
                    class="bg-tf-red text-white px-6 py-3 rounded-xl font-black text-xs uppercase shadow-lg shadow-red-200 hover:bg-red-700 transition-all text-center mt-1">
                    Report Incident
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-1 flex flex-col items-center py-12 px-6">
        @php
            $normalizedStatus = strtolower((string) ($incident->status ?? 'pending'));
            $isPendingReview = in_array($normalizedStatus, ['pending', 'pending review'], true);
            $isAccepted = $normalizedStatus === 'accepted';
            $isResolved = in_array($normalizedStatus, ['resolved', 'completed', 'closed'], true);
            $isInvestigation = in_array(
                $normalizedStatus,
                ['under investigation', 'investigating', 'in progress'],
                true,
            );
            $statusLabel = $incident->status ?? 'Pending';

            if ($isResolved) {
                $statusBadgeClass = 'status-badge-resolved';
            } elseif ($isInvestigation) {
                $statusBadgeClass = 'status-badge-investigation';
            } elseif ($isAccepted) {
                $statusBadgeClass = 'bg-blue-100 text-blue-700';
            } else {
                $statusBadgeClass = 'status-badge-pending';
            }

            $assignedStepComplete = $isAccepted || $isInvestigation || $isResolved;
            $investigationStepComplete = $isInvestigation || $isResolved;
            $pendingReviewComplete = $isPendingReview || $assignedStepComplete;
        @endphp

        <div class="max-w-3xl w-full text-center mb-12" data-reveal data-reveal-delay="1">
            <h1 class="text-3xl md:text-4xl font-black text-tf-blue mb-4 uppercase tracking-tight">Track Your Report
            </h1>
            <p class="text-gray-500 mb-8">Enter your unique tracking ID to view the current status of your submitted
                incident.</p>
            <p class="text-sm text-gray-500 -mt-5 mb-7">
                You can find your Tracking ID in your email.
            </p>

            <form action="{{ route('track.case.page') }}" method="GET" data-loader-text="Searching your report"
                class="search-container p-2 flex items-center border border-gray-100 interactive-panel">
                <div class="pl-6 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" id="trackInput" name="reference_number" value="{{ $referenceNumber }}"
                    placeholder="Enter ID (e.g., TFL-2026-0045)"
                    class="w-full py-4 px-4 outline-none font-bold text-tf-blue placeholder:text-gray-300" required>
                <button type="submit"
                    class="bg-tf-blue text-white px-8 py-4 rounded-full font-black text-sm uppercase hover:bg-blue-800 transition-all">
                    Search
                </button>
            </form>
        </div>

        @if ($wasSearched && !$incident)
            <div id="notFoundCard" class="max-w-3xl w-full mb-8" data-reveal data-reveal-delay="2">
                <div class="bg-red-50 border border-red-100 rounded-2xl p-5 text-center">
                    <p class="text-sm font-black text-red-700 uppercase">No report found</p>
                    <p class="text-sm text-red-700 mt-2">No case matched tracking ID <span
                            class="font-bold">{{ $referenceNumber }}</span>. Please check and try again.</p>
                </div>
            </div>
        @endif

        <div id="resultCard" class="max-w-3xl w-full {{ $incident ? '' : 'hidden' }}" data-reveal
            data-reveal-delay="2">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden interactive-panel">

                <div class="bg-gray-50 p-6 border-b flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Report ID</p>
                        <h2 class="text-xl font-black text-tf-blue" id="resID">{{ $incident->report_number ?? '' }}
                        </h2>
                    </div>
                    <span id="resStatusBadge"
                        class="{{ $statusBadgeClass }} px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-tighter">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Incident
                                Type</label>
                            <p class="font-bold text-gray-800">{{ $incident->incident_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Date Filed</label>
                            <p class="font-bold text-gray-800">
                                {{ $incident?->created_at ? \Illuminate\Support\Carbon::parse($incident->created_at)->format('F d, Y - h:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Location</label>
                            <p class="font-bold text-gray-800"><i
                                    class="fa-solid fa-location-dot text-tf-red mr-1"></i>
                                {{ $incident->location_name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div
                        class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">

                        <div class="relative flex items-center justify-between md:justify-start timeline-step">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-tf-blue text-white shadow shrink-0 z-10">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-black text-tf-blue">Report Submitted</p>
                                <p class="text-xs text-gray-500">Your report has been successfully logged into the PNP
                                    system.</p>
                            </div>
                        </div>

                        <div
                            class="relative flex items-center justify-between md:justify-start timeline-step {{ $pendingReviewComplete ? '' : 'opacity-30' }}">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border border-white {{ $pendingReviewComplete ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-500' }} shadow shrink-0 z-10">
                                <i
                                    class="fa-solid {{ $pendingReviewComplete ? 'fa-clipboard-check text-xs' : 'fa-circle text-[6px]' }}"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p
                                    class="text-sm font-black {{ $pendingReviewComplete ? 'text-tf-blue' : 'text-gray-400' }}">
                                    Pending Review</p>
                                <p class="text-xs {{ $pendingReviewComplete ? 'text-gray-500' : 'text-gray-400' }}">
                                    @if ($pendingReviewComplete)
                                        Your report is queued for investigator review and validation.
                                    @else
                                        Waiting for review.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div
                            class="relative flex items-center justify-between md:justify-start timeline-step {{ $assignedStepComplete ? '' : 'opacity-30' }}">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border border-white {{ $assignedStepComplete ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-500' }} shadow shrink-0 z-10">
                                <i
                                    class="fa-solid {{ $assignedStepComplete ? 'fa-hourglass-half text-xs' : 'fa-circle text-[6px]' }}"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p
                                    class="text-sm font-black {{ $assignedStepComplete ? 'text-tf-blue' : 'text-gray-400' }}">
                                    Assigned to Investigator</p>
                                <p class="text-xs {{ $assignedStepComplete ? 'text-gray-500' : 'text-gray-400' }}">
                                    @if (($incident->investigator_name ?? null) && ($incident->investigator_badge ?? null))
                                        {{ $incident->investigator_name }} [Badge
                                        #{{ $incident->investigator_badge }}] is currently reviewing the evidence.
                                    @elseif ($assignedStepComplete)
                                        Case is currently under investigation.
                                    @else
                                        Waiting to be assigned to an investigator.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div
                            class="relative flex items-center justify-between md:justify-start timeline-step {{ $investigationStepComplete ? '' : 'opacity-30' }}">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border border-white {{ $investigationStepComplete ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-500' }} shadow shrink-0 z-10">
                                <i
                                    class="fa-solid {{ $investigationStepComplete ? 'fa-hourglass-half text-xs' : 'fa-circle text-[6px]' }}"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p
                                    class="text-sm font-black {{ $investigationStepComplete ? 'text-tf-blue' : 'text-gray-400' }}">
                                    Under Investigation</p>
                                <p
                                    class="text-xs {{ $investigationStepComplete ? 'text-gray-500' : 'text-gray-400' }}">
                                    @if ($investigationStepComplete)
                                        The assigned investigator is currently handling this case on-site and gathering
                                        findings.
                                    @else
                                        Waiting for investigation to start.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div
                            class="relative flex items-center justify-between md:justify-start timeline-step {{ $isResolved ? '' : 'opacity-30' }}">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border border-white {{ $isResolved ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-500' }} shadow shrink-0 z-10">
                                <i
                                    class="fa-solid {{ $isResolved ? 'fa-flag-checkered text-xs' : 'fa-circle text-[6px]' }}"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-black {{ $isResolved ? 'text-tf-blue' : 'text-gray-400' }}">
                                    Completed</p>
                                <p class="text-xs {{ $isResolved ? 'text-gray-500' : 'text-gray-400' }}">
                                    @if ($isResolved)
                                        Case resolved on
                                        {{ $incident?->time_completed ? \Illuminate\Support\Carbon::parse($incident->time_completed)->format('F d, Y - h:i A') : 'recorded completion date' }}.
                                    @else
                                        Waiting for final resolution.
                                    @endif
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="p-6 bg-blue-50 text-center">
                    <p class="text-[11px] text-tf-blue font-bold uppercase tracking-wide">
                        Need help? Contact Lipa PNP: (043) 756-2245
                    </p>
                </div>
            </div>
        </div>

    </main>

    @if ($incident || ($wasSearched && !$incident))
        <script>
            window.addEventListener('load', function() {
                const target = document.getElementById('resultCard') || document.getElementById('notFoundCard');
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        </script>
    @endif
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

                document.querySelectorAll('form').forEach((form) => {
                    form.addEventListener('submit', () => {
                        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                            return;
                        }

                        showPageLoader(form.dataset.loaderText || 'Processing request');
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
</body>

</html>
