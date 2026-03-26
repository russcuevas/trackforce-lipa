<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce Lipa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        .text-tf-yellow {
            color: #FFD700;
        }

        .section-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>

    <nav class="bg-white border-b sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Trackforce Lipa Logo" class="h-10 w-auto object-contain">
                <span class="font-black text-tf-blue tracking-tighter text-lg uppercase">TRACKFORCE LIPA -
                    <span style="color: #CE1126">PNP</span></span>
            </div>
        </div>
    </nav>

    <header class="bg-tf-blue py-12 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-white text-3xl md:text-5xl font-black mb-4">Public Incident Reporting</h1>
            <p class="text-blue-200 text-lg">Report traffic accidents or violations in Lipa City. No account required.
            </p>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 -mt-8 mb-20">
        <form action="/submit-report" method="POST" enctype="multipart/form-data" class="space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="section-card p-8">
                    <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-camera"></i> Evidence
                    </h3>
                    <div
                        class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:bg-gray-50 transition cursor-pointer">
                        <input type="file" name="evidence[]" multiple class="hidden" id="fileUpload">
                        <label for="fileUpload" class="cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-tf-blue mb-2"></i>
                            <p class="text-xs font-bold text-gray-500 uppercase">Upload Photos or Video</p>
                            <p class="text-[10px] text-gray-400 mt-1">Files will be saved in the system</p>
                        </label>
                    </div>
                </div>

                <div class="section-card p-8">
                    <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-car"></i> Vehicle Involved
                    </h3>
                    <div class="space-y-4">
                        <select name="vehicle_type"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="Private Car">Private Car</option>
                            <option value="PUJ">PUJ (Jeepney)</option>
                            <option value="Truck">Truck</option>
                            <option value="Bicycle">Bicycle</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="text" name="plate_number" placeholder="Plate Number (If visible)"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                    </div>
                </div>


            </div>

            <div class="section-card p-8">
                <div class="flex items-center gap-3 mb-6 border-b pb-4">
                    <i class="fa-solid fa-map-location-dot text-tf-red text-xl"></i>
                    <h2 class="font-bold text-gray-800 uppercase tracking-wide">Incident Location</h2>
                </div>

                <div class="mb-6">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Tap/Click on the map to pin
                        the exact location</label>
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

                            <textarea id="location_name" name="location_name" rows="3" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:ring-2 focus:ring-tf-blue outline-none resize-none"
                                placeholder="Click on the map to get address..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-50">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Incident Type</label>
                        <select name="incident_type"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                            <option value="Accident">Accident</option>
                            <option value="Violation">Violation</option>
                            <option value="Public Disturbance">Public Disturbance</option>
                            <option value="Other">Other</option>
                        </select>
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


            <div class="section-card p-8">
                <div class="flex items-center gap-3 mb-6 border-b pb-4">
                    <i class="fa-solid fa-user-pen text-tf-blue text-xl"></i>
                    <h2 class="font-bold text-gray-800 uppercase tracking-wide">Reporter Details (Optional)</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="text" name="reporter_name" placeholder="Full Name"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                    <input type="text" name="reporter_contact" placeholder="Contact Number"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">

                </div>
                <div class="mt-6">
                    <input type="text" name="reporter_address" placeholder="Address"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                </div>
            </div>



            <div class="flex flex-col items-center">
                <button type="submit"
                    class="w-full md:w-auto md:px-20 bg-tf-red text-white py-4 rounded-2xl font-black shadow-xl shadow-red-200 hover:bg-red-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                    SUBMIT TO LIPA PNP <i class="fa-solid fa-paper-plane"></i>
                </button>
                <p class="text-[11px] text-gray-400 mt-4 text-center max-w-md">
                    By submitting, a unique <span class="font-bold">Report Number (TFL-YYYY-XXXX)</span> will be
                    generated for your reference.
                </p>
            </div>

        </form>
    </main>

    <footer class="bg-white border-t py-8 text-center">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Official TrackForce Lipa Security
            Portal &copy; 2026</p>
    </footer>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([13.9414, 121.1644], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker;

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

        map.on('click', async function(e) {
            const {
                lat,
                lng
            } = e.latlng;

            document.getElementById('lat').value = lat.toFixed(8);
            document.getElementById('lng').value = lng.toFixed(8);

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('location_name').value = "Detecting address...";
            const address = await getAddress(lat, lng);
            document.getElementById('location_name').value = address;
        });
    </script>
</body>

</html>
