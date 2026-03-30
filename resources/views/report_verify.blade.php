<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Report OTP | TrackForce Lipa</title>
    <script>
        document.documentElement.classList.add('js');
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --tf-blue: #0B3D91;
            --tf-red: #CE1126;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(206, 17, 38, 0.11), transparent 28%),
                radial-gradient(circle at top right, rgba(11, 61, 145, 0.12), transparent 32%),
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

        .verify-hero {
            background: linear-gradient(135deg, rgba(8, 39, 94, 0.96), rgba(11, 61, 145, 0.92));
            position: relative;
            overflow: hidden;
        }

        .verify-hero::before,
        .verify-hero::after {
            content: '';
            position: absolute;
            border-radius: 9999px;
            filter: blur(10px);
            opacity: 0.3;
            animation: floatOrb 9s ease-in-out infinite;
        }

        .verify-hero::before {
            width: 14rem;
            height: 14rem;
            right: -4rem;
            top: -5rem;
            background: rgba(255, 216, 82, 0.22);
        }

        .verify-hero::after {
            width: 11rem;
            height: 11rem;
            left: -3rem;
            bottom: -4rem;
            background: rgba(206, 17, 38, 0.2);
            animation-delay: -3s;
        }

        .verify-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            box-shadow: 0 30px 70px -36px rgba(11, 61, 145, 0.38);
        }

        .interactive-panel {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .interactive-panel:hover {
            transform: translateY(-8px);
            box-shadow: 0 28px 60px -34px rgba(11, 61, 145, 0.34);
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
            .verify-hero::before,
            .verify-hero::after,
            .interactive-panel,
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

<body>
    <div id="pageLoader" class="page-loader" role="status" aria-live="polite">
        <div class="loader-core">
            <div class="loader-rings">
                <span></span>
                <span></span>
                <span></span>
                <img src="{{ asset('images/logo.png') }}" alt="TrackForce Lipa" class="loader-logo">
            </div>
            <p id="pageLoaderLabel" class="loader-label">Preparing OTP verification</p>
        </div>
    </div>

    <header class="verify-hero py-10 px-6" data-reveal>
        <div class="max-w-3xl mx-auto text-center relative z-10">
            <h1 class="text-white text-3xl md:text-4xl font-black">Verify Your Report</h1>
            <p class="text-blue-200 mt-2 text-sm md:text-base">Enter the OTP sent to your email to confirm your report.
            </p>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-6 -mt-8 pb-14">
        <div class="verify-card border border-gray-200 rounded-2xl p-7 md:p-8 interactive-panel" data-reveal
            data-reveal-delay="1">
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 text-green-700 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('report.verify.submit') }}" method="POST" class="space-y-4"
                data-loader-text="Verifying your report">
                @csrf

                <div>
                    <label for="report_number" class="block text-xs font-bold text-gray-500 uppercase mb-2">Report
                        Number</label>
                    <input id="report_number" name="report_number" type="text"
                        value="{{ old('report_number', $reportNumber) }}" required readonly
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                </div>

                <div>
                    <label for="reporter_email" class="block text-xs font-bold text-gray-500 uppercase mb-2">Reporter
                        Email</label>
                    <input id="reporter_email" name="reporter_email" type="email" value="{{ old('reporter_email') }}"
                        required required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none"
                        placeholder="youremail@example.com">
                </div>

                <div>
                    <label for="otp" class="block text-xs font-bold text-gray-500 uppercase mb-2">One-Time Password
                        (OTP)</label>
                    <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" required
                        value="{{ old('otp') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm tracking-[0.3em] font-bold outline-none"
                        placeholder="123456">
                </div>

                <button type="submit"
                    class="w-full bg-tf-red text-white py-3 rounded-xl font-black hover:bg-red-700 transition-colors lift-button">
                    VERIFY OTP
                </button>
            </form>

            <p class="text-xs text-gray-500 mt-5">
                Did not receive OTP? Submit your report again or contact support.
            </p>

            <a href="{{ route('report.page') }}" class="inline-block text-sm font-bold text-tf-blue mt-4">Back to Report
                Form</a>
        </div>
    </main>
    <script>
        (function() {
            const loader = document.getElementById('pageLoader');
            const loaderLabel = document.getElementById('pageLoaderLabel');
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

            initRevealAnimations();
            initPageTransitions();

            window.addEventListener('load', function() {
                window.setTimeout(hidePageLoader, prefersReducedMotion ? 0 : 420);
            });
        })();
    </script>
</body>

</html>
