<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce - Lipa</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />


    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #FFFFFF;
            color: #1A1A1A;
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

        .nav-active {
            background-color: #CE1126 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight">Incident Reports</h1>
                    <p class="text-sm text-gray-500">Manage, review, and update submitted incident cases.</p>
                </div>
                <div class="flex gap-2">
                    @include('investigator.incidents.modals.add_incident_modal')
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-tf-blue transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">All Cases</p>
                    <p class="text-xl font-black text-tf-blue">1,284</p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Pending</p>
                    <p class="text-xl font-black text-tf-red">09</p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Under Investigation</p>
                    <p class="text-xl font-black text-yellow-600">42</p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Resolved</p>
                    <p class="text-xl font-black text-green-600">1,233</p>
                </button>
            </div>

            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="w-full overflow-x-auto">

                        <table id="reportsTable" class="display w-full text-sm">
                            <thead class="bg-gray-50 text-tf-blue uppercase text-[11px] font-black">
                                <tr>
                                    <th class="py-4 px-4 text-left">Case ID</th>
                                    <th class="py-4 px-4 text-left">Incident Details</th>
                                    <th class="py-4 px-4 text-left">Location</th>
                                    <th class="py-4 px-4 text-left">Status</th>
                                    <th class="py-4 px-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 border-l-4 border-tf-red">
                                        <span class="font-bold text-gray-400">#2026-0045</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <p class="font-bold text-gray-700">Road Accident</p>
                                        <p class="text-[10px] text-gray-400 uppercase">Reported: 2 mins ago</p>
                                    </td>
                                    <td class="py-4 px-4 text-gray-500">
                                        <i class="fa-solid fa-location-dot mr-1 text-gray-300"></i> Lipa City Proper
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="bg-red-100 text-tf-red px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter">Pending
                                            Review</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex justify-center">
                                            <a href="{{ route('investigator.incident.view.case.page') }}"
                                            class="bg-tf-blue hover:bg-blue-900 text-white px-5 py-2 rounded-xl text-[10px] font-black transition-all shadow-md hover:shadow-blue-900/20 flex items-center gap-2 w-fit active:scale-95">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="uppercase tracking-wider">View Case</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 border-l-4 border-yellow-500">
                                        <span class="font-bold text-gray-400">#2026-0044</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <p class="font-bold text-gray-700">Vehicle Violation</p>
                                        <p class="text-[10px] text-gray-400 uppercase">Reported: 1 hour ago</p>
                                    </td>
                                    <td class="py-4 px-4 text-gray-500">
                                        <i class="fa-solid fa-location-dot mr-1 text-gray-300"></i> Sabang, Lipa
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter">Under
                                            Investigation</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex justify-center">
                                            <a href="{{ route('investigator.incident.view.case.page') }}"
                                            class="bg-tf-blue hover:bg-blue-900 text-white px-5 py-2 rounded-xl text-[10px] font-black transition-all shadow-md hover:shadow-blue-900/20 flex items-center gap-2 w-fit active:scale-95">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="uppercase tracking-wider">View Case</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        $(document).ready(function() {
            $('#reportsTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search"
                }
            });
        });
    </script>
    <script>
        let map;
        let marker;

        // Wait for Alpine to open the modal before initializing the map
        // because Leaflet needs the container to have a height/width to render
        function initMap() {
            if (map) return; // Prevent double initialization

            map = L.map('map').setView([13.9414, 121.1644], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            map.on('click', async function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;

                // Update Input Fields
                document.getElementById('lat').value = lat.toFixed(8);
                document.getElementById('lng').value = lng.toFixed(8);

                // Update/Move Marker
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                // Reverse Geocoding (Address Lookup)
                document.getElementById('location_name').value = "Detecting address...";
                const address = await getAddress(lat, lng);
                document.getElementById('location_name').value = address;
            });
        }

        async function getAddress(lat, lng) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
                );
                const data = await response.json();
                // Simplify address to a cleaner format if possible
                return data.display_name || "Unknown Location";
            } catch (error) {
                console.error("Error fetching address:", error);
                return "Address not found";
            }
        }

        // Alpine Listener: Trigger map init when 'openIncident' becomes true
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                // Note: Replace with the actual data path if your x-data is isolated
                // This is a helper to watch the state
            });
        });
    </script>
</body>

</html>
