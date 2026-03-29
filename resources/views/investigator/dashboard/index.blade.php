<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce - Lipa</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        /* Your specific red */
        .text-tf-yellow {
            color: #FFD700;
        }

        /* Active State Class */
        .nav-active {
            background-color: #CE1126 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(11, 61, 145, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
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

        .recent-submission-list {
            max-height: 30rem;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .pulse-marker {
            background: transparent;
            border: none;
        }

        .pulse-pin {
            --pin-color: #CE1126;
            position: relative;
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 9999px;
            background: var(--pin-color);
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.15);
        }

        .pulse-pin::after {
            content: '';
            position: absolute;
            inset: -7px;
            border-radius: 9999px;
            border: 2px solid var(--pin-color);
            opacity: 0.8;
            animation: tfPulse 1.6s ease-out infinite;
        }

        .pulse-pin.pending {
            --pin-color: #CE1126;
        }

        .pulse-pin.investigation {
            --pin-color: #eab308;
        }

        .pulse-pin.resolved {
            --pin-color: #16a34a;
        }

        @keyframes tfPulse {
            0% {
                transform: scale(0.7);
                opacity: 0.75;
            }

            70% {
                transform: scale(1.6);
                opacity: 0;
            }

            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }

        .map-card-expanded {
            position: fixed;
            inset: 1rem;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .map-card-expanded #map {
            flex: 1 !important;
            height: auto !important;
        }

        .map-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.45);
            backdrop-filter: blur(2px);
            z-index: 90;
        }

        .hidden-backdrop {
            display: none;
        }

        .lock-scroll {
            overflow: hidden !important;
        }

        @media (max-width: 640px) {
            .map-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .map-actions {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .map-actions>* {
                flex: 1 1 auto;
                text-align: center;
                justify-content: center;
            }
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    <div id="mapBackdrop" class="map-backdrop hidden-backdrop"></div>

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-4 md:p-6 space-y-5 bg-gray-50">
            @php
                $maxAddressCount = max(1, (int) ($addressCounts->max('total_incidents') ?? 1));
            @endphp

            {{-- Stat Cards --}}
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card p-4 md:p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Total Incidents</p>
                    <div class="flex items-end justify-between">
                        <h2 id="totalIncidentsValue" class="text-2xl md:text-3xl font-black">
                            {{ number_format($totalIncidents) }}</h2>
                        <span class="text-yellow-500 text-xs font-bold"><i class="fa-solid fa-database"></i> Live</span>
                    </div>
                </div>
                <div class="stat-card p-4 md:p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Under Investigation</p>
                    <div class="flex items-end justify-between">
                        <h2 id="underInvestigationValue" class="text-2xl md:text-3xl font-black">
                            {{ number_format($underInvestigationCount) }}</h2>
                        <i class="fa-solid fa-hourglass-half text-yellow-500 text-lg md:text-xl"></i>
                    </div>
                </div>
                <div class="stat-card p-4 md:p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Resolved</p>
                    <div class="flex items-end justify-between">
                        <h2 id="resolvedTodayValue" class="text-2xl md:text-3xl font-black">
                            {{ number_format($resolvedTodayCount) }}</h2>
                        <i class="fa-solid fa-check-double text-yellow-500 text-lg md:text-xl"></i>
                    </div>
                </div>
                <div class="stat-card p-4 md:p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Pending Review</p>
                    <div class="flex items-end justify-between">
                        <h2 id="pendingReviewValue" class="text-2xl md:text-3xl font-black">
                            {{ number_format($pendingReviewCount) }}</h2>
                        <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-lg md:text-xl"></i>
                    </div>
                </div>
            </section>

            {{-- Map + Recent Submissions --}}
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Map Card --}}
                <div id="mapCard"
                    class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">

                    <div
                        class="map-header flex flex-wrap justify-between items-center gap-3 px-4 pt-4 pb-3 border-b border-gray-50">
                        <h3 class="font-bold text-base flex items-center gap-2">
                            <i class="fa-solid fa-map-pin text-tf-red"></i>Real-Time Incident Map
                        </h3>
                        <div class="map-actions flex items-center gap-2">
                            <span id="mapPinsCount"
                                class="text-xs bg-tf-blue text-white px-3 py-1 rounded-lg font-bold">
                                {{ $mapIncidents->count() }} Pins
                            </span>
                            <button id="expandMapBtn" type="button"
                                class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-lg font-bold hover:bg-gray-200 transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-expand"></i> Fullscreen
                            </button>
                        </div>
                    </div>

                    <div id="map" class="w-full h-52 sm:h-72 md:h-80 lg:h-[30rem]"></div>

                    <div
                        class="map-legend flex flex-wrap items-center gap-x-5 gap-y-1.5 px-4 py-3 border-t border-gray-100 text-xs font-semibold text-gray-500">
                        <span class="text-gray-400 uppercase tracking-wide text-[10px]">Legend:</span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-tf-red flex-shrink-0"></span> Pending
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-yellow-500 flex-shrink-0"></span>
                            Under Investigation
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-600 flex-shrink-0"></span>
                            Resolved
                        </span>
                    </div>
                </div>

                {{-- Recent Submissions --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="font-bold text-lg mb-4">Recent Submissions</h3>
                    <div id="recentSubmissionsList" class="space-y-4 recent-submission-list pr-2">
                        @forelse ($recentIncidents as $incident)
                            @php
                                $statusValue = strtolower((string) ($incident->status ?? 'pending'));
                                $isResolved = in_array($statusValue, ['resolved', 'completed', 'closed'], true);
                                $isInvestigation = in_array(
                                    $statusValue,
                                    ['under investigation', 'investigating', 'in progress'],
                                    true,
                                );

                                if ($isResolved) {
                                    $lineClass = 'bg-green-600';
                                    $badgeClass = 'status-badge-resolved';
                                } elseif ($isInvestigation) {
                                    $lineClass = 'bg-yellow-500';
                                    $badgeClass = 'status-badge-investigation';
                                } else {
                                    $lineClass = 'bg-tf-red';
                                    $badgeClass = 'status-badge-pending';
                                }
                            @endphp
                            <div class="flex gap-4 p-3 recent-item hover:bg-gray-50 rounded-lg border-b border-gray-50">
                                <div class="w-2 h-12 rounded-full {{ $lineClass }}"></div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-bold uppercase">Incident
                                        #{{ $incident->report_number }}</p>
                                    <p class="text-sm font-medium">{{ $incident->incident_type }} -
                                        {{ $incident->location_name }}</p>
                                    <div class="flex justify-between mt-2 items-center gap-4">
                                        <span
                                            class="text-[10px] px-2 py-0.5 rounded uppercase font-black tracking-tighter {{ $badgeClass }}">
                                            {{ $incident->status ?? 'Pending' }}
                                        </span>
                                        <span
                                            class="text-[10px] text-gray-400">{{ \Illuminate\Support\Carbon::parse($incident->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-sm text-gray-400 py-12">No submissions yet.</div>
                        @endforelse
                    </div>
                    <a href="{{ route('investigator.incident.report.page') }}"
                        class="mt-4 w-full border border-tf-blue text-tf-blue py-2 rounded text-sm font-bold hover:bg-tf-blue hover:text-black transition-colors text-center">View
                        All Reports</a>
                </div>
            </section>

            {{-- Address Counts --}}
            <section>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-bold text-base flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-tf-red"></i> Incident Count Per Address
                        </h3>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Top 10
                            Locations</span>
                    </div>

                    @if ($addressCounts->isEmpty())
                        <div id="addressCountsList">
                            <p class="text-sm text-gray-400 py-6 text-center">No incident addresses recorded yet.</p>
                        </div>
                    @else
                        <div id="addressCountsList" class="space-y-3">
                            @foreach ($addressCounts as $address)
                                <div>
                                    <div class="flex items-center justify-between gap-4 mb-1.5">
                                        <p class="text-sm font-semibold text-gray-700 truncate">
                                            {{ $address->location_name }}</p>
                                        <span class="text-xs font-black text-tf-blue uppercase shrink-0">
                                            {{ $address->total_incidents }}
                                            report{{ (int) $address->total_incidents === 1 ? '' : 's' }}
                                        </span>
                                    </div>
                                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-tf-blue transition-all duration-500"
                                            style="width: {{ min(100, ((int) $address->total_incidents / $maxAddressCount) * 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            {{-- Demographics --}}
            <section>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-base mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-chart-column text-tf-blue"></i> High-Risk Demographics
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">Age Groups</h4>
                            <canvas id="ageChart"></canvas>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">Sex Distribution
                            </h4>
                            <canvas id="sexChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const numberFormatter = new Intl.NumberFormat();

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function getStatusMeta(status) {
            const statusValue = (status || '').toLowerCase();
            const isResolved = ['resolved', 'completed', 'closed'].includes(statusValue);
            const isInvestigation = ['under investigation', 'investigating', 'in progress'].includes(statusValue);

            if (isResolved) {
                return {
                    lineClass: 'bg-green-600',
                    badgeClass: 'status-badge-resolved',
                    pinType: 'resolved',
                    popupStatusClass: 'text-green-600'
                };
            }

            if (isInvestigation) {
                return {
                    lineClass: 'bg-yellow-500',
                    badgeClass: 'status-badge-investigation',
                    pinType: 'investigation',
                    popupStatusClass: 'text-yellow-600'
                };
            }

            return {
                lineClass: 'bg-tf-red',
                badgeClass: 'status-badge-pending',
                pinType: 'pending',
                popupStatusClass: 'text-red-500'
            };
        }

        const ageChartData = @json($ageChartData);
        const ageMaxValue = Math.max(10, ...ageChartData, 0);
        const ageCtx = document.getElementById('ageChart').getContext('2d');
        const ageChart = new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: ['18-20', '21-30', '31-40', '41+'],
                datasets: [{
                    label: 'Count',
                    data: ageChartData,
                    backgroundColor: '#CE1126',
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: ageMaxValue + 2
                    }
                }
            }
        });

        // SEX CHART
        const sexChartData = @json($sexChartData);
        const sexMaxValue = Math.max(10, ...sexChartData, 0);
        const sexCtx = document.getElementById('sexChart').getContext('2d');
        const sexChart = new Chart(sexCtx, {
            type: 'bar',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    label: 'Count',
                    data: sexChartData,
                    backgroundColor: ['#0B3D91', '#FFD700', '#64748b'],
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: sexMaxValue + 2
                    }
                }
            }
        });

        let incidents = @json($mapIncidents);
        const map = L.map('map').setView([13.9419, 121.1644], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markerLayer = L.featureGroup().addTo(map);

        function renderMapIncidents(items, shouldFitBounds = false) {
            incidents = items || [];
            markerLayer.clearLayers();

            incidents.forEach(incident => {
                const statusMeta = getStatusMeta(incident.status);

                const pulseIcon = L.divIcon({
                    className: 'pulse-marker',
                    html: `<span class="pulse-pin ${statusMeta.pinType}"></span>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7],
                    popupAnchor: [0, -10]
                });

                const marker = L.marker([incident.lat, incident.lng], {
                    icon: pulseIcon
                }).addTo(markerLayer);
                marker.bindPopup(`
                    <div class="text-sm">
                        <b class="text-tf-blue">${escapeHtml(incident.id)}</b><br>
                        ${escapeHtml(incident.title)}<br>
                        <span class="text-xs font-bold uppercase ${statusMeta.popupStatusClass}">
                            ${escapeHtml(incident.status || 'Pending')}
                        </span>
                    </div>
                `);
            });

            const pinsBadge = document.getElementById('mapPinsCount');
            if (pinsBadge) {
                pinsBadge.textContent = `${incidents.length} Pins`;
            }

            if (shouldFitBounds && incidents.length > 0) {
                map.fitBounds(markerLayer.getBounds(), {
                    padding: [20, 20]
                });
            }
        }

        function renderRecentSubmissions(items) {
            const container = document.getElementById('recentSubmissionsList');
            if (!container) {
                return;
            }

            if (!items || !items.length) {
                container.innerHTML = '<div class="text-center text-sm text-gray-400 py-12">No submissions yet.</div>';
                return;
            }

            container.innerHTML = items.map((incident) => {
                const statusMeta = getStatusMeta(incident.status);

                return `
                    <div class="flex gap-4 p-3 recent-item hover:bg-gray-50 rounded-lg border-b border-gray-50">
                        <div class="w-2 h-12 rounded-full ${statusMeta.lineClass}"></div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-bold uppercase">Incident #${escapeHtml(incident.report_number)}</p>
                            <p class="text-sm font-medium">${escapeHtml(incident.incident_type)} - ${escapeHtml(incident.location_name)}</p>
                            <div class="flex justify-between mt-2 items-center gap-4">
                                <span class="text-[10px] px-2 py-0.5 rounded uppercase font-black tracking-tighter ${statusMeta.badgeClass}">
                                    ${escapeHtml(incident.status || 'Pending')}
                                </span>
                                <span class="text-[10px] text-gray-400">${escapeHtml(incident.created_at_human || '')}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderAddressCounts(items) {
            const container = document.getElementById('addressCountsList');
            if (!container) {
                return;
            }

            if (!items || !items.length) {
                container.innerHTML =
                    '<p class="text-sm text-gray-400 py-6 text-center">No incident addresses recorded yet.</p>';
                return;
            }

            const maxCount = Math.max(1, ...items.map((address) => Number(address.total_incidents || 0)));

            container.innerHTML = items.map((address) => {
                const total = Number(address.total_incidents || 0);
                const widthPercent = Math.min(100, (total / maxCount) * 100);

                return `
                    <div>
                        <div class="flex items-center justify-between gap-4 mb-1">
                            <p class="text-sm font-semibold text-gray-700 truncate">${escapeHtml(address.location_name)}</p>
                            <span class="text-xs font-black text-tf-blue uppercase">${total} report${total === 1 ? '' : 's'}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full bg-tf-blue" style="width: ${widthPercent}%"></div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function updateCharts(ageData, sexData) {
            ageChart.data.datasets[0].data = ageData;
            ageChart.options.scales.y.max = Math.max(10, ...ageData, 0) + 2;
            ageChart.update();

            sexChart.data.datasets[0].data = sexData;
            sexChart.options.scales.y.max = Math.max(10, ...sexData, 0) + 2;
            sexChart.update();
        }

        function updateStats(data) {
            document.getElementById('totalIncidentsValue').textContent = numberFormatter.format(data.total_incidents || 0);
            document.getElementById('underInvestigationValue').textContent = numberFormatter.format(data
                .under_investigation_count || 0);
            document.getElementById('resolvedTodayValue').textContent = numberFormatter.format(data.resolved_today_count ||
                0);
            document.getElementById('pendingReviewValue').textContent = numberFormatter.format(data.pending_review_count ||
                0);
        }

        const mapCard = document.getElementById('mapCard');
        const expandMapBtn = document.getElementById('expandMapBtn');
        const mapBackdrop = document.getElementById('mapBackdrop');
        let isMapExpanded = false;

        function setMapExpanded(expanded) {
            isMapExpanded = expanded;
            mapCard.classList.toggle('map-card-expanded', expanded);
            mapBackdrop.classList.toggle('hidden-backdrop', !expanded);
            document.body.classList.toggle('lock-scroll', expanded);

            expandMapBtn.innerHTML = expanded ?
                '<i class="fa-solid fa-compress mr-1"></i> Exit Fullscreen' :
                '<i class="fa-solid fa-expand mr-1"></i> Expand Map';

            setTimeout(() => {
                map.invalidateSize();
                if (incidents.length > 0) {
                    map.fitBounds(markerLayer.getBounds(), {
                        padding: [20, 20]
                    });
                }
            }, 220);
        }

        expandMapBtn.addEventListener('click', function() {
            setMapExpanded(!isMapExpanded);
        });

        mapBackdrop.addEventListener('click', function() {
            if (isMapExpanded) {
                setMapExpanded(false);
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && isMapExpanded) {
                setMapExpanded(false);
            }
        });

        async function refreshDashboardData() {
            try {
                const response = await fetch('{{ route('investigator.dashboard.data') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                updateStats(data);
                renderRecentSubmissions(data.recent_incidents || []);
                renderAddressCounts(data.address_counts || []);
                renderMapIncidents(data.map_incidents || [], false);
                updateCharts(data.age_chart_data || [0, 0, 0, 0], data.sex_chart_data || [0, 0, 0]);
            } catch (error) {
                console.error('Live dashboard refresh failed:', error);
            }
        }

        renderMapIncidents(incidents, true);
        setInterval(refreshDashboardData, 8000);
    </script>
</body>

</html>
