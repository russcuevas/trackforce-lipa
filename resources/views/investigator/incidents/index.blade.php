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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">


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

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 2rem;
            text-align: center;
            border-radius: 0.5rem !important;
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

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">All Cases</p>
                    <p id="allCasesValue" class="text-xl font-black text-tf-blue">
                        {{ number_format((int) ($stats['all'] ?? 0)) }}</p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Pending</p>
                    <p id="pendingCasesValue" class="text-xl font-black text-tf-blue">
                        {{ number_format((int) ($stats['pending'] ?? 0)) }}</p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Accepted</p>
                    <p id="acceptedCasesValue" class="text-xl font-black text-tf-blue">
                        {{ number_format((int) ($stats['accepted'] ?? 0)) }}
                    </p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Declined</p>
                    <p id="declinedCasesValue" class="text-xl font-black text-tf-blue">
                        {{ number_format((int) ($stats['declined'] ?? 0)) }}
                    </p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Resolved</p>
                    <p id="resolvedCasesValue" class="text-xl font-black text-tf-blue">
                        {{ number_format((int) ($stats['resolved'] ?? 0)) }}
                    </p>
                </button>
                <button
                    class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-yellow-500 transition-all text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase">Under Investigation</p>
                    <p id="underInvestigationCasesValue" class="text-xl font-black text-tf-blue">
                        {{ number_format((int) ($stats['under_investigation'] ?? 0)) }}
                    </p>
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
                                @foreach ($incidents as $incident)
                                    @php
                                        $statusRaw = trim((string) ($incident->status ?? 'Pending'));
                                        $statusLower = strtolower($statusRaw);

                                        $statusStyles = 'bg-gray-100 text-gray-700';
                                        if (in_array($statusLower, ['pending', 'pending review'], true)) {
                                            $statusStyles = 'bg-red-100 text-red-700';
                                        } elseif ($statusLower === 'accepted') {
                                            $statusStyles = 'bg-blue-100 text-blue-700';
                                        } elseif ($statusLower === 'under investigation') {
                                            $statusStyles = 'bg-yellow-100 text-yellow-700';
                                        } elseif ($statusLower === 'declined') {
                                            $statusStyles = 'bg-gray-200 text-gray-700';
                                        } elseif ($statusLower === 'resolved') {
                                            $statusStyles = 'bg-green-100 text-green-700';
                                        }

                                        $borderColor = in_array($statusLower, ['pending', 'pending review'], true)
                                            ? 'border-tf-red'
                                            : ($statusLower === 'accepted'
                                                ? 'border-yellow-500'
                                                : ($statusLower === 'resolved'
                                                    ? 'border-green-500'
                                                    : 'border-gray-300'));

                                        $reportedAt = $incident->time_reported ?? $incident->created_at;
                                    @endphp

                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-4 border-l-4 {{ $borderColor }}">
                                            <span
                                                class="font-bold text-gray-400">#{{ $incident->report_number ?? 'INC-' . $incident->id }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-700">{{ $incident->incident_type ?? 'N/A' }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 uppercase">
                                                Reported:
                                                {{ $reportedAt ? \Illuminate\Support\Carbon::parse($reportedAt)->diffForHumans() : 'N/A' }}
                                            </p>
                                        </td>
                                        <td class="py-4 px-4 text-gray-500">
                                            <i class="fa-solid fa-location-dot mr-1 text-gray-300"></i>
                                            {{ $incident->location_name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <span
                                                class="{{ $statusStyles }} px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter">
                                                {{ $statusRaw !== '' ? $statusRaw : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex justify-center">
                                                <a href="{{ route('investigator.incident.view.case.page', ['incident' => $incident->id]) }}"
                                                    class="bg-tf-blue hover:bg-blue-900 text-white px-5 py-2 rounded-xl text-[10px] font-black transition-all shadow-md hover:shadow-blue-900/20 flex items-center gap-2 w-fit active:scale-95">
                                                    <i class="fa-solid fa-eye"></i>
                                                    <span class="uppercase tracking-wider">View Case</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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
        const numberFormatter = new Intl.NumberFormat();
        const incidentReportDataUrl = '{{ route('investigator.incident.report.data') }}';
        let reportsTable;

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function getStatusMeta(statusRaw) {
            const statusValue = String(statusRaw || 'Pending').trim().toLowerCase();

            let statusStyles = 'bg-gray-100 text-gray-700';
            if (['pending', 'pending review'].includes(statusValue)) {
                statusStyles = 'bg-red-100 text-red-700';
            } else if (statusValue === 'accepted') {
                statusStyles = 'bg-blue-100 text-blue-700';
            } else if (['under investigation', 'investigating', 'in progress'].includes(statusValue)) {
                statusStyles = 'bg-yellow-100 text-yellow-700';
            } else if (statusValue === 'declined') {
                statusStyles = 'bg-gray-200 text-gray-700';
            } else if (statusValue === 'resolved') {
                statusStyles = 'bg-green-100 text-green-700';
            }

            const borderColor = ['pending', 'pending review'].includes(statusValue) ?
                'border-tf-red' :
                (statusValue === 'accepted' ?
                    'border-yellow-500' :
                    (statusValue === 'resolved' ? 'border-green-500' : 'border-gray-300'));

            return {
                statusStyles,
                borderColor,
            };
        }

        function updateStats(stats) {
            const safeStats = stats || {};
            document.getElementById('allCasesValue').textContent = numberFormatter.format(Number(safeStats.all || 0));
            document.getElementById('pendingCasesValue').textContent = numberFormatter.format(Number(safeStats.pending ||
                0));
            document.getElementById('acceptedCasesValue').textContent = numberFormatter.format(Number(safeStats.accepted ||
                0));
            document.getElementById('declinedCasesValue').textContent = numberFormatter.format(Number(safeStats.declined ||
                0));
            document.getElementById('resolvedCasesValue').textContent = numberFormatter.format(Number(safeStats.resolved ||
                0));
            document.getElementById('underInvestigationCasesValue').textContent = numberFormatter.format(Number(safeStats
                .under_investigation || 0));
        }

        function updateReportsTable(incidents) {
            if (!reportsTable) {
                return;
            }

            const rows = (incidents || []).map((incident) => {
                const meta = getStatusMeta(incident.status);
                const safeReportNumber = escapeHtml(incident.report_number || `INC-${incident.id || ''}`);
                const safeType = escapeHtml(incident.incident_type || 'N/A');
                const safeReportedAt = escapeHtml(incident.reported_at_human || 'N/A');
                const safeLocation = escapeHtml(incident.location_name || 'N/A');
                const safeStatus = escapeHtml(incident.status || 'Pending');
                const safeCaseUrl = escapeHtml(incident.case_url || '#');

                return [
                    `<div class="py-1 px-0 border-l-4 ${meta.borderColor}"><span class="font-bold text-gray-400">#${safeReportNumber}</span></div>`,
                    `<p class="font-bold text-gray-700">${safeType}</p><p class="text-[10px] text-gray-400 uppercase">Reported: ${safeReportedAt}</p>`,
                    `<span class="text-gray-500"><i class="fa-solid fa-location-dot mr-1 text-gray-300"></i>${safeLocation}</span>`,
                    `<span class="${meta.statusStyles} px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter">${safeStatus}</span>`,
                    `<div class="flex justify-center"><a href="${safeCaseUrl}" class="bg-tf-blue hover:bg-blue-900 text-white px-5 py-2 rounded-xl text-[10px] font-black transition-all shadow-md hover:shadow-blue-900/20 flex items-center gap-2 w-fit active:scale-95"><i class="fa-solid fa-eye"></i><span class="uppercase tracking-wider">View Case</span></a></div>`,
                ];
            });

            reportsTable.clear();
            if (rows.length > 0) {
                reportsTable.rows.add(rows);
            }
            reportsTable.draw(false);
        }

        async function refreshIncidentReportsData() {
            try {
                const response = await fetch(incidentReportDataUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();
                updateStats(data.stats || {});
                updateReportsTable(data.incidents || []);
            } catch (error) {
                console.error('Realtime incident refresh failed:', error);
            }
        }

        $(document).ready(function() {
            reportsTable = $('#reportsTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search",
                    paginate: {
                        previous: '<',
                        next: '>'
                    }
                }
            });

            setInterval(refreshIncidentReportsData, 8000);
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

                await applyIncidentLocation(lat, lng);
            });
        }

        async function applyIncidentLocation(lat, lng) {
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const locationNameInput = document.getElementById('location_name');

            if (!latInput || !lngInput || !locationNameInput) {
                return;
            }

            latInput.value = Number(lat).toFixed(8);
            lngInput.value = Number(lng).toFixed(8);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else if (map) {
                marker = L.marker([lat, lng]).addTo(map);
            }

            if (map) {
                map.setView([lat, lng], 17, {
                    animate: true
                });
            }

            locationNameInput.value = 'Detecting address...';
            const address = await getAddress(lat, lng);
            locationNameInput.value = address;
        }

        async function useCurrentLocationForIncident() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported on this browser/device.');
                return;
            }

            if (!map) {
                initMap();
            }

            navigator.geolocation.getCurrentPosition(
                async function(position) {
                        const {
                            latitude,
                            longitude
                        } = position.coords;

                        await applyIncidentLocation(latitude, longitude);
                    },
                    function(error) {
                        let message = 'Unable to get your location. Please tap on the map instead.';

                        if (error.code === error.PERMISSION_DENIED) {
                            message = 'Location permission denied. Please allow location access and try again.';
                        } else if (error.code === error.POSITION_UNAVAILABLE) {
                            message = 'Location information is unavailable right now.';
                        } else if (error.code === error.TIMEOUT) {
                            message = 'Location request timed out. Please try again.';
                        }

                        alert(message);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    }
            );
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
