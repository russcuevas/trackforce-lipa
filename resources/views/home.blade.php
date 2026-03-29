<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce Lipa</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8fafc;
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
    </style>
</head>

<body class="min-h-screen flex flex-col">

    <nav class="bg-white border-b sticky top-0 z-50">
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
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="text-center lg:text-left">
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

            <div class="hidden lg:flex justify-center relative">
                <div class="absolute inset-0 bg-tf-red rounded-full filter blur-[120px] opacity-20 animate-pulse"></div>
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-4 rounded-3xl shadow-2xl rotate-3">
                    <img src="https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&q=80&w=600"
                        alt="Lipa City Map" class="rounded-2xl shadow-inner w-full h-[400px] object-cover">
                </div>
            </div>
        </div>
    </header>

    <section class="py-20 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-8 rounded-3xl hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-bolt-lightning text-tf-blue text-2xl"></i>
                    </div>
                    <h3 class="font-black text-tf-blue text-xl mb-3 uppercase">Fast Reporting</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Submit evidence, locations, and vehicle details in
                        under 2 minutes without an account.</p>
                </div>

                <div class="p-8 rounded-3xl hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-location-crosshairs text-tf-red text-2xl"></i>
                    </div>
                    <h3 class="font-black text-tf-blue text-xl mb-3 uppercase">GPS Tracking</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Pinpoint exact incident coordinates using our
                        integrated map system for faster response.</p>
                </div>

                <div class="p-8 rounded-3xl hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-magnifying-glass-chart text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="font-black text-tf-blue text-xl mb-3 uppercase">Transparency</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Use your unique TFL ID to monitor the investigation
                        status of your report from any device.</p>
                </div>
            </div>
        </div>
    </section>


    <script>
        (function() {
            const menuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');

            if (!menuButton || !mobileMenu) {
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
        })();
    </script>
</body>

</html>
