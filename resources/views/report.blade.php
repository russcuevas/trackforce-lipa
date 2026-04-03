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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 1rem;
            z-index: 1;
        }

        .marker-pin {
            color: #CE1126;
        }
    </style>
    <style>
        :root {
            --tf-blue: #0B3D91;
            --tf-blue-dark: #08275e;
            --tf-red: #CE1126;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(206, 17, 38, 0.1), transparent 28%),
                radial-gradient(circle at top right, rgba(11, 61, 145, 0.11), transparent 31%),
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

        .section-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
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

        .report-hero {
            background: linear-gradient(135deg, rgba(8, 39, 94, 0.96), rgba(11, 61, 145, 0.9));
            position: relative;
            overflow: hidden;
        }

        .report-hero::before,
        .report-hero::after {
            content: '';
            position: absolute;
            border-radius: 9999px;
            filter: blur(10px);
            opacity: 0.3;
            animation: floatOrb 9s ease-in-out infinite;
        }

        .report-hero::before {
            width: 16rem;
            height: 16rem;
            right: -4rem;
            top: -6rem;
            background: rgba(255, 216, 82, 0.2);
        }

        .report-hero::after {
            width: 11rem;
            height: 11rem;
            left: -3rem;
            bottom: -4rem;
            background: rgba(206, 17, 38, 0.2);
            animation-delay: -3s;
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
            box-shadow: 0 26px 60px -34px rgba(11, 61, 145, 0.32);
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
            .report-hero::before,
            .report-hero::after,
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
            <p id="pageLoaderLabel" class="loader-label">Preparing your incident report</p>
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
                    class="font-bold text-gray-500 hover:text-tf-blue text-sm uppercase tracking-wider py-2">Home</a>
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

    <header class="report-hero py-12 px-6" data-reveal>
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h1 class="text-white text-3xl md:text-5xl font-black mb-4">Public Incident Reporting</h1>
            <p class="text-blue-200 text-lg">Report traffic accidents or violations in Lipa City. No account required.
            </p>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 -mt-8 mb-20">
        <form id="reportForm" action="/submit-report" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="section-card p-8 mt-5 interactive-panel" data-reveal data-reveal-delay="1">
                    <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-camera"></i> Evidence
                    </h3>
                    <div
                        class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:bg-gray-50 transition cursor-pointer">
                        <input type="file" name="evidence[]" multiple accept="image/*,video/*" class="hidden"
                            id="fileUpload">
                        <label for="fileUpload" class="cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-tf-blue mb-2"></i>
                            <p class="text-xs font-bold text-gray-500 uppercase">Upload Photos or Video</p>
                            <p class="text-[10px] text-gray-400 mt-1">Files will be saved in the system</p>
                        </label>
                        <div class="text-red-500 text-xs mt-1 hidden" id="error-evidence"></div>
                    </div>

                    <div id="evidencePreview" class="mt-4 hidden">
                        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-500 mb-3">Selected Evidence
                        </p>
                        <p class="text-[10px] text-gray-400 mb-2">Tap any preview to open full view.</p>
                        <div id="evidencePreviewGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-3"></div>
                    </div>
                </div>

                <div class="section-card p-8 mt-5 interactive-panel" data-reveal data-reveal-delay="2">
                    <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-car"></i> Vehicle Involved
                    </h3>

                    <div id="vehiclesContainer" class="space-y-4">
                        <!-- Single vehicle template -->
                        <div
                            class="vehicle-entry relative grid grid-cols-1 md:grid-cols-2 gap-3 p-4 border border-gray-200 rounded-2xl bg-gray-50/70">
                            <button type="button"
                                class="removeVehicle absolute -top-2 -right-2 md:top-3 md:right-3 h-8 w-8 rounded-full bg-red-500 text-white flex items-center justify-center shadow hover:bg-red-600 transition-all"
                                aria-label="Remove vehicle" title="Remove vehicle">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>

                            <select name="vehicle_type[]"
                                class="vehicle_type w-full bg-white border border-gray-200 rounded-xl p-3 text-sm outline-none">
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Private Car">Private Car</option>
                                <option value="PUJ">PUJ (Jeepney)</option>
                                <option value="Truck">Truck</option>
                                <option value="Bicycle">Bicycle</option>
                                <option value="Other">Other</option>
                            </select>

                            <input type="text" name="plate_number[]" placeholder="Plate Number (If visible)"
                                class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm outline-none">

                            <input type="text" name="vehicle_specific_name[]" placeholder="(e.g. Honda Click)"
                                class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm outline-none">

                            <input type="text" name="vehicle_color[]" placeholder="Vehicle Color"
                                class="vehicle_color w-full bg-white border border-gray-200 rounded-xl p-3 text-sm outline-none">

                            <input type="text" name="vehicle_type_other[]" placeholder="Specify Other Vehicle"
                                class="vehicle_type_other w-full bg-white border border-gray-200 rounded-xl p-3 text-sm outline-none hidden md:col-span-2">
                        </div>
                    </div>

                    <button type="button" id="addVehicleBtn"
                        class="mt-4 ml-auto h-11 w-11 bg-tf-blue text-white rounded-xl text-lg font-bold hover:bg-blue-700 transition-all flex items-center justify-center lift-button"
                        aria-label="Add vehicle" title="Add vehicle">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="section-card p-8 interactive-panel" data-reveal data-reveal-delay="2">
                <div class="flex items-center gap-3 mb-6 border-b pb-4">
                    <i class="fa-solid fa-map-location-dot text-tf-red text-xl"></i>
                    <h2 class="font-bold text-gray-800 uppercase tracking-wide">Incident Location</h2>
                </div>

                <div class="mb-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase">Tap/Click on the map to
                            pin
                            the exact location</label>
                        <button type="button" id="useCurrentLocationBtn"
                            class="inline-flex items-center gap-2 text-[11px] font-bold bg-blue-50 text-tf-blue border border-blue-100 px-3 py-2 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fa-solid fa-location-crosshairs"></i>
                            Use My Current Location
                        </button>
                    </div>
                    <p id="locationHint" class="text-[11px] text-gray-400 mb-2">
                        On mobile, tap "Use My Current Location" and allow location permission.
                    </p>
                    <div id="map" class="border-2 border-gray-100 shadow-inner"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Latitude</label>
                        <input type="text" id="lat" name="latitude" readonly required
                            class="w-full bg-gray-100 border border-gray-200 rounded-xl p-3 text-sm text-gray-500 outline-none cursor-not-allowed"
                            placeholder="0.00000000">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Longitude</label>
                        <input type="text" id="lng" name="longitude" readonly required
                            class="w-full bg-gray-100 border border-gray-200 rounded-xl p-3 text-sm text-gray-500 outline-none cursor-not-allowed"
                            placeholder="0.00000000">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">
                            Location Name / Detected Address
                        </label>

                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-4 text-tf-red text-xs"></i>

                            <textarea id="location_name" name="location_name" rows="3" required readonly
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:ring-2 focus:ring-tf-blue outline-none resize-none"
                                placeholder="Click on the map to get address..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-50">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Incident Type</label>
                        <select id="incident_type" name="incident_type"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                            <option value="Accident">Accident</option>
                            <option value="Violation">Violation</option>
                            <option value="Public Disturbance">Public Disturbance</option>
                            <option value="Other">Other</option>
                        </select>

                        <input type="text" id="incident_type_other" name="incident_type_other"
                            placeholder="Specify Incident Type"
                            class="hidden mt-3 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Road Condition</label>
                        <select name="road_condition"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                            <option value="Dry">Dry</option>
                            <option value="Wet">Wet</option>
                            <option value="Under Construction">Under Construction</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Weather
                            Condition</label>
                        <select name="weather_condition"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                            <option value="Clear">Clear</option>
                            <option value="Raining">Raining</option>
                            <option value="Foggy">Foggy</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="section-card p-8 interactive-panel" data-reveal data-reveal-delay="3">
                <div class="flex items-center gap-3 mb-4 border-b pb-4">
                    <i class="fa-solid fa-user-pen text-tf-blue text-xl"></i>
                    <h2 class="font-bold text-gray-800 uppercase tracking-wide">Your Details</h2>
                </div>

                <!-- ✅ Info / Warning Box -->
                <div
                    class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-xl p-4 mb-6">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i>
                    <p>
                        <span class="font-semibold">Important:</span>
                        Please enter a valid email address. This will be used for OTP verification to confirm your
                        submission.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="text" name="reporter_name" placeholder="Full Name"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">

                    <input type="text" name="reporter_contact" placeholder="Contact Number"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">

                    <input required type="text" name="reporter_email" placeholder="Email Address"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">

                    <textarea name="reporter_address" placeholder="Address (Optional)"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none" rows="3"></textarea>
                </div>

                <div class="mt-6">
                </div>
            </div>



            <div class="flex flex-col items-center" data-reveal data-reveal-delay="3">
                <button type="submit"
                    class="w-full md:w-auto md:px-20 bg-tf-red text-white py-4 rounded-2xl font-black shadow-xl shadow-red-200 hover:bg-red-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 lift-button">
                    SUBMIT TO LIPA PNP <i class="fa-solid fa-paper-plane"></i>
                </button>
                <p class="text-[11px] text-gray-400 mt-4 text-center max-w-md">
                    By submitting, a unique <span class="font-bold">Report Number (TFL-YYYY-XXXX)</span> will be
                    generated for your reference.
                </p>
            </div>

        </form>

        <div id="otpModal" class="fixed inset-0 z-[70] hidden">
            <div id="otpModalBackdrop" class="absolute inset-0 bg-black/50"></div>

            <div class="relative h-full w-full overflow-y-auto">
                <div class="min-h-full flex items-center justify-center p-4">
                    <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-2xl p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-lg font-black text-gray-800">Verify Your Report</h3>
                                <p class="text-xs text-gray-500 mt-1">Enter the OTP sent to your email.</p>
                            </div>
                            <button type="button" id="closeOtpModalBtn"
                                class="h-8 w-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors"
                                aria-label="Close OTP modal">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <form id="otpVerifyForm" class="space-y-3" action="{{ route('report.verify.submit') }}"
                            method="POST">
                            @csrf
                            <div>
                                <label for="otp_report_number"
                                    class="block text-[10px] font-black text-gray-400 uppercase mb-2">Report
                                    Number</label>
                                <input id="otp_report_number" name="report_number" type="text" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                            </div>

                            <div>
                                <label for="otp_reporter_email"
                                    class="block text-[10px] font-black text-gray-400 uppercase mb-2">Reporter
                                    Email</label>
                                <input id="otp_reporter_email" name="reporter_email" type="email" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                            </div>

                            <div>
                                <label for="otp_code"
                                    class="block text-[10px] font-black text-gray-400 uppercase mb-2">OTP</label>
                                <input id="otp_code" name="otp" type="text" inputmode="numeric"
                                    maxlength="6" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm tracking-[0.3em] font-bold outline-none"
                                    placeholder="123456">
                            </div>

                            <button type="submit" id="verifyOtpBtn"
                                class="w-full bg-tf-red text-white py-3 rounded-xl font-black hover:bg-red-700 transition-colors">
                                VERIFY OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                window.addEventListener('load', function() {
                    window.setTimeout(hidePageLoader, prefersReducedMotion ? 0 : 420);
                });
                initPageTransitions();
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
        document.addEventListener('DOMContentLoaded', function() {
            const fileUpload = document.getElementById('fileUpload');
            const evidencePreview = document.getElementById('evidencePreview');
            const evidencePreviewGrid = document.getElementById('evidencePreviewGrid');
            let selectedEvidenceFiles = [];
            let activePreviewUrls = [];

            function syncEvidenceInputFiles() {
                const dataTransfer = new DataTransfer();
                selectedEvidenceFiles.forEach(file => dataTransfer.items.add(file));
                fileUpload.files = dataTransfer.files;
            }

            function clearActivePreviewUrls() {
                activePreviewUrls.forEach(url => URL.revokeObjectURL(url));
                activePreviewUrls = [];
            }

            function renderEvidencePreview() {
                clearActivePreviewUrls();
                evidencePreviewGrid.innerHTML = '';

                if (!selectedEvidenceFiles.length) {
                    evidencePreview.classList.add('hidden');
                    return;
                }

                evidencePreview.classList.remove('hidden');

                selectedEvidenceFiles.forEach((file, index) => {
                    const objectUrl = URL.createObjectURL(file);
                    activePreviewUrls.push(objectUrl);

                    const card = document.createElement('div');
                    card.className =
                        'relative border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm';

                    const openLink = document.createElement('a');
                    openLink.href = objectUrl;
                    openLink.target = '_blank';
                    openLink.rel = 'noopener noreferrer';
                    openLink.title = `Open ${file.name}`;
                    openLink.className = 'block';

                    if (file.type.startsWith('image/')) {
                        const image = document.createElement('img');
                        image.src = objectUrl;
                        image.alt = file.name;
                        image.className = 'h-24 w-full object-cover';
                        openLink.appendChild(image);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = objectUrl;
                        video.className = 'h-24 w-full object-cover bg-black';
                        video.muted = true;
                        video.playsInline = true;
                        video.preload = 'metadata';
                        openLink.appendChild(video);
                    } else {
                        const fileBox = document.createElement('div');
                        fileBox.className =
                            'h-24 w-full flex items-center justify-center text-gray-500 bg-gray-50 text-xs font-semibold';
                        fileBox.textContent = 'FILE';
                        openLink.appendChild(fileBox);
                    }

                    const fileName = document.createElement('p');
                    fileName.className = 'text-[10px] text-gray-600 px-2 py-2 truncate';
                    fileName.textContent = file.name;

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className =
                        'absolute top-1 right-1 h-6 w-6 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center hover:bg-red-600 transition-all';
                    removeBtn.setAttribute('aria-label', `Remove ${file.name}`);
                    removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';

                    removeBtn.addEventListener('click', () => {
                        selectedEvidenceFiles.splice(index, 1);
                        syncEvidenceInputFiles();
                        renderEvidencePreview();
                    });

                    card.appendChild(openLink);
                    card.appendChild(fileName);
                    card.appendChild(removeBtn);
                    evidencePreviewGrid.appendChild(card);
                });
            }

            fileUpload.addEventListener('change', function() {
                const incomingFiles = Array.from(fileUpload.files || []);
                if (!incomingFiles.length) {
                    syncEvidenceInputFiles();
                    return;
                }

                selectedEvidenceFiles = selectedEvidenceFiles.concat(incomingFiles);
                syncEvidenceInputFiles();
                renderEvidencePreview();
            });

            window.clearEvidencePreview = function() {
                selectedEvidenceFiles = [];
                syncEvidenceInputFiles();
                renderEvidencePreview();
            };

            function toggleOtherField(selectEl, inputEl) {
                selectEl.addEventListener('change', () => {
                    if (selectEl.value === 'Other') {
                        inputEl.classList.remove('hidden');
                        inputEl.required = true;
                    } else {
                        inputEl.classList.add('hidden');
                        inputEl.required = false;
                        inputEl.value = '';
                    }
                });
            }

            const incidentTypeSelect = document.getElementById('incident_type');
            const incidentTypeOtherInput = document.getElementById('incident_type_other');

            function syncIncidentTypeOther() {
                if (!incidentTypeSelect || !incidentTypeOtherInput) return;

                if (incidentTypeSelect.value === 'Other') {
                    incidentTypeOtherInput.classList.remove('hidden');
                    incidentTypeOtherInput.required = true;
                } else {
                    incidentTypeOtherInput.classList.add('hidden');
                    incidentTypeOtherInput.required = false;
                    incidentTypeOtherInput.value = '';
                }
            }

            if (incidentTypeSelect && incidentTypeOtherInput) {
                incidentTypeSelect.addEventListener('change', syncIncidentTypeOther);
                syncIncidentTypeOther();
            }

            const vehiclesContainer = document.getElementById('vehiclesContainer');
            const addVehicleBtn = document.getElementById('addVehicleBtn');

            function updateRemoveButtons() {
                const entries = vehiclesContainer.querySelectorAll('.vehicle-entry');
                const shouldHideRemove = entries.length <= 1;

                entries.forEach(entry => {
                    const removeBtn = entry.querySelector('.removeVehicle');
                    if (!removeBtn) return;

                    removeBtn.classList.toggle('hidden', shouldHideRemove);
                });
            }

            function initializeVehicleEntry(entry) {
                toggleOtherField(entry.querySelector('.vehicle_type'), entry.querySelector('.vehicle_type_other'));

                // Remove button
                entry.querySelector('.removeVehicle').addEventListener('click', () => {
                    entry.remove();
                    updateRemoveButtons();
                });
            }

            // Initialize existing entries
            vehiclesContainer.querySelectorAll('.vehicle-entry').forEach(initializeVehicleEntry);
            updateRemoveButtons();

            // Add new vehicle entry
            addVehicleBtn.addEventListener('click', () => {
                const template = vehiclesContainer.querySelector('.vehicle-entry');
                const clone = template.cloneNode(true);

                // Reset fields
                clone.querySelectorAll('select, input').forEach(input => input.value = '');
                clone.querySelector('.vehicle_type').value = 'Motorcycle';
                clone.querySelector('.vehicle_type_other').classList.add('hidden');
                clone.querySelector('.vehicle_type_other').required = false;

                initializeVehicleEntry(clone);

                vehiclesContainer.appendChild(clone);
                updateRemoveButtons();
            });

            window.resetVehicleEntries = function() {
                const entries = vehiclesContainer.querySelectorAll('.vehicle-entry');

                entries.forEach((entry, index) => {
                    if (index > 0) {
                        entry.remove();
                    }
                });

                const baseEntry = vehiclesContainer.querySelector('.vehicle-entry');
                if (!baseEntry) return;

                baseEntry.querySelectorAll('select, input').forEach(input => input.value = '');
                baseEntry.querySelector('.vehicle_type').value = 'Motorcycle';
                baseEntry.querySelector('.vehicle_type_other').classList.add('hidden');
                baseEntry.querySelector('.vehicle_type_other').required = false;

                updateRemoveButtons();
            };
        });
    </script>
    <script>
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top'
            },
            dismissible: true,
            types: [{
                    type: 'success',
                    background: '#198754',
                    icon: {
                        // Changed from bi bi-check-circle-fill
                        className: 'fa-solid fa-circle-check',
                        tagName: 'i',
                        color: 'white'
                    }
                },
                {
                    type: 'error',
                    background: '#dc3545',
                    icon: {
                        // Changed from bi bi-exclamation-triangle-fill
                        className: 'fa-solid fa-triangle-exclamation',
                        tagName: 'i',
                        color: 'white'
                    }
                }
            ]
        });

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
    </script>
    <script>
        document.getElementById('reportForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const otpModal = document.getElementById('otpModal');
            const otpModalBackdrop = document.getElementById('otpModalBackdrop');
            const closeOtpModalBtn = document.getElementById('closeOtpModalBtn');
            const otpVerifyForm = document.getElementById('otpVerifyForm');
            const otpReportNumber = document.getElementById('otp_report_number');
            const otpReporterEmail = document.getElementById('otp_reporter_email');
            const otpCode = document.getElementById('otp_code');
            const verifyOtpBtn = document.getElementById('verifyOtpBtn');

            function openOtpModal() {
                otpModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                setTimeout(() => otpCode.focus(), 150);
            }

            function closeOtpModal() {
                otpModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (otpModalBackdrop && !otpModalBackdrop.dataset.bound) {
                otpModalBackdrop.addEventListener('click', closeOtpModal);
                otpModalBackdrop.dataset.bound = '1';
            }

            if (closeOtpModalBtn && !closeOtpModalBtn.dataset.bound) {
                closeOtpModalBtn.addEventListener('click', closeOtpModal);
                closeOtpModalBtn.dataset.bound = '1';
            }

            if (otpVerifyForm && !otpVerifyForm.dataset.bound) {
                otpVerifyForm.addEventListener('submit', async function(verifyEvent) {
                    verifyEvent.preventDefault();

                    if (!otpVerifyForm.checkValidity()) {
                        otpVerifyForm.reportValidity();
                        return;
                    }

                    verifyOtpBtn.disabled = true;
                    verifyOtpBtn.textContent = 'Verifying...';

                    if (window.showPageLoader) {
                        window.showPageLoader('Verifying your one-time password');
                    }

                    try {
                        const verifyFormData = new FormData(otpVerifyForm);
                        const verifyResponse = await fetch(otpVerifyForm.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]')
                                    .value
                            },
                            body: verifyFormData
                        });

                        const verifyData = await verifyResponse.json();

                        if (verifyResponse.status === 422 && verifyData.errors) {
                            Object.values(verifyData.errors).forEach((messages) => {
                                if (messages && messages.length) {
                                    notyf.open({
                                        type: 'error',
                                        message: messages[0]
                                    });
                                }
                            });
                            return;
                        }

                        if (verifyResponse.ok && verifyData.success) {
                            notyf.open({
                                type: 'success',
                                message: verifyData.message ||
                                    'OTP verified successfully. Your report is now confirmed.'
                            });

                            closeOtpModal();
                            form.reset();
                            otpVerifyForm.reset();

                            if (window.clearEvidencePreview) {
                                window.clearEvidencePreview();
                            }
                            if (window.resetVehicleEntries) {
                                window.resetVehicleEntries();
                            }

                            const incidentTypeSelect = document.getElementById('incident_type');
                            const incidentTypeOtherInput = document.getElementById(
                                'incident_type_other');
                            if (incidentTypeSelect && incidentTypeOtherInput) {
                                incidentTypeOtherInput.classList.add('hidden');
                                incidentTypeOtherInput.required = false;
                                incidentTypeOtherInput.value = '';
                            }

                            if (marker) {
                                map.removeLayer(marker);
                                marker = null;
                            }

                            document.getElementById('lat').value = '';
                            document.getElementById('lng').value = '';
                            document.getElementById('location_name').value = '';
                        } else {
                            notyf.open({
                                type: 'error',
                                message: verifyData.message ||
                                    'Unable to verify OTP. Please try again.'
                            });
                        }
                    } catch (verifyError) {
                        console.error(verifyError);
                        notyf.open({
                            type: 'error',
                            message: 'Network error while verifying OTP.'
                        });
                    } finally {
                        verifyOtpBtn.disabled = false;
                        verifyOtpBtn.textContent = 'VERIFY OTP';

                        if (window.hidePageLoader) {
                            window.hidePageLoader();
                        }
                    }
                });

                otpVerifyForm.dataset.bound = '1';
            }

            // Keep native required/type validation before AJAX submit.
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Hide previous file errors
            const fileErrorEl = document.getElementById('error-evidence');
            fileErrorEl.textContent = '';
            fileErrorEl.classList.add('hidden');
            const formData = new FormData(form);

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            if (window.showPageLoader) {
                window.showPageLoader('Submitting your report securely');
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.status === 422) {
                    if (data.errors) {
                        const allErrors = Object.entries(data.errors);

                        for (const [field, messages] of allErrors) {
                            if (messages && messages.length) {
                                notyf.open({
                                    type: 'error',
                                    message: messages[0]
                                });
                            }

                            // evidence validation commonly comes as evidence.0, evidence.1, etc.
                            if ((field === 'evidence' || field.startsWith('evidence.')) && messages && messages
                                .length) {
                                fileErrorEl.textContent = messages[0];
                                fileErrorEl.classList.remove('hidden');
                            }
                        }
                    }
                } else if (response.status === 200) {
                    notyf.open({
                        type: 'success',
                        message: `Report submitted. Check your email for OTP. Reference: ${data.report_number}`
                    });

                    otpReportNumber.value = data.report_number || '';
                    otpReporterEmail.value = data.reporter_email || form.querySelector(
                        'input[name="reporter_email"]').value;


                    openOtpModal();
                } else {
                    notyf.open({
                        type: 'error',
                        message: data.message || 'Something went wrong while submitting the report.'
                    });
                }
            } catch (err) {
                console.error(err);
                notyf.open({
                    type: 'error',
                    message: 'An unexpected network error occurred.'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'SUBMIT TO LIPA PNP';

                if (window.hidePageLoader) {
                    window.hidePageLoader();
                }
            }
        });
    </script>
    <script>
        const map = L.map('map').setView([13.9414, 121.1644], 14);
        const useCurrentLocationBtn = document.getElementById('useCurrentLocationBtn');
        const locationHint = document.getElementById('locationHint');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker;

        function setLocationHint(message, isError = false) {
            if (!locationHint) return;
            locationHint.textContent = message;
            locationHint.classList.toggle('text-red-500', isError);
            locationHint.classList.toggle('text-gray-400', !isError);
        }

        async function getAddress(lat, lng) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
                const data = await response.json();
                return data.display_name || "Unknown Location";
            } catch (error) {
                console.error("Error fetching address:", error);
                return "Address not found";
            }
        }

        async function applyMapLocation(lat, lng) {
            document.getElementById('lat').value = Number(lat).toFixed(8);
            document.getElementById('lng').value = Number(lng).toFixed(8);

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], 17, {
                animate: true
            });

            document.getElementById('location_name').value = "Detecting address...";
            const address = await getAddress(lat, lng);
            document.getElementById('location_name').value = address;
        }

        map.on('click', async function(e) {
            const {
                lat,
                lng
            } = e.latlng;
            await applyMapLocation(lat, lng);
            setLocationHint("Location selected from map.");
        });

        if (useCurrentLocationBtn) {
            useCurrentLocationBtn.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    setLocationHint("Geolocation is not supported on this browser/device.", true);
                    return;
                }

                const originalLabel = useCurrentLocationBtn.innerHTML;
                useCurrentLocationBtn.disabled = true;
                useCurrentLocationBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Getting Location...';
                setLocationHint("Waiting for location permission...");

                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                            const {
                                latitude,
                                longitude
                            } = position.coords;

                            await applyMapLocation(latitude, longitude);
                            setLocationHint("Current location captured successfully.");
                            useCurrentLocationBtn.disabled = false;
                            useCurrentLocationBtn.innerHTML = originalLabel;
                        },
                        function(error) {
                            let message = "Unable to get your location. Please tap on the map instead.";

                            if (error.code === error.PERMISSION_DENIED) {
                                message =
                                    "Location permission denied. Please allow location access and try again.";
                            } else if (error.code === error.POSITION_UNAVAILABLE) {
                                message = "Location information is unavailable right now.";
                            } else if (error.code === error.TIMEOUT) {
                                message = "Location request timed out. Please try again.";
                            }

                            setLocationHint(message, true);
                            useCurrentLocationBtn.disabled = false;
                            useCurrentLocationBtn.innerHTML = originalLabel;
                        }, {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0
                        }
                );
            });
        }
    </script>
</body>

</html>
