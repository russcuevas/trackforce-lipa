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
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-6 space-y-8 bg-gray-50">

            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stat-card p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Total Incidents</p>
                    <div class="flex items-end justify-between">
                        <h2 class="text-3xl font-black">1,284</h2>
                        <span class="text-yellow-500 text-sm font-bold"><i class="fa-solid fa-arrow-up"></i> 12%</span>
                    </div>
                </div>
                <div class="stat-card p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Under Investigation</p>
                    <div class="flex items-end justify-between">
                        <h2 class="text-3xl font-black">42</h2>
                        <i class="fa-solid fa-hourglass-half text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div class="stat-card p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Resolved Today</p>
                    <div class="flex items-end justify-between">
                        <h2 class="text-3xl font-black">18</h2>
                        <i class="fa-solid fa-check-double text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div class="stat-card p-5 rounded-xl border-l-4 border-yellow-500">
                    <p class="text-xs uppercase font-bold text-gray-500 mb-1">Pending Review</p>
                    <div class="flex items-end justify-between">
                        <h2 class="text-3xl font-black">09</h2>
                        <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-xl"></i>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 h-96">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg"><i class="fa-solid fa-map-pin text-tf-red mr-2"></i>Real Time Incident Map</h3>
                        <button class="text-xs bg-tf-blue text-white px-3 py-1 rounded">Expand Map</button>
                    </div>
                    <div id="map" class="w-full h-72 rounded-lg z-10"></div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="font-bold text-lg mb-4">Recent Submissions</h3>
                    <div class="space-y-4 flex-1 overflow-y-auto pr-2">
                        <div class="flex gap-4 p-3 hover:bg-gray-50 rounded-lg border-b border-gray-50">
                            <div class="w-2 h-12 rounded-full bg-tf-red"></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-bold uppercase">Incident #2026-0045</p>
                                <p class="text-sm font-medium">Road Accident - Lipa City Proper</p>
                                <div class="flex justify-between mt-2">
                                    <span
                                        class="text-[10px] bg-red-100 text-tf-red px-2 py-0.5 rounded uppercase font-black tracking-tighter">Pending</span>
                                    <span class="text-[10px] text-gray-400">2 mins ago</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 p-3 hover:bg-gray-50 rounded-lg border-b border-gray-50">
                            <div class="w-2 h-12 rounded-full bg-yellow-500"></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-bold uppercase">Incident #2026-0044</p>
                                <p class="text-sm font-medium">Vehicle Violation - Sabang</p>
                                <div class="flex justify-between mt-2">
                                    <span
                                        class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded uppercase font-black tracking-tighter">Under
                                        Investigation</span>
                                    <span class="text-[10px] text-gray-400">1 hour ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        class="mt-4 w-full border border-tf-blue text-tf-blue py-2 rounded text-sm font-bold hover:bg-tf-blue hover:text-white transition-colors">View
                        All Reports</button>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 lg:col-span-12">

                    <h3 class="font-bold text-lg mb-4"><i class="fa-solid fa-chart-column text-tf-blue">
                        </i> High-Risk
                        Demographics</h3>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 mb-2 uppercase">Age Groups</h4>
                            <canvas id="ageChart"></canvas>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-500 mb-2 uppercase">Sex Distribution</h4>
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
        const ageCtx = document.getElementById('ageChart').getContext('2d');
        new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: ['18-20', '21-30', '31-40', '41+'],
                datasets: [{
                    label: 'Percentage',
                    data: [20, 55, 15, 10],
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
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });

        // SEX CHART
        const sexCtx = document.getElementById('sexChart').getContext('2d');
        new Chart(sexCtx, {
            type: 'bar',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    label: 'Percentage',
                    data: [87, 13],
                    backgroundColor: ['#0B3D91', '#FFD700'],
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
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });
    </script>
    <script>
    // 1. Initialize the map centered on Lipa City
    const map = L.map('map').setView([13.9419, 121.1644], 13);

    // 2. Add the OpenStreetMap tiles (the actual map visuals)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 3. Sample Data (In a real app, you'd fetch this from your database)
    const incidents = [
        { id: "2026-0045", lat: 13.9410, lng: 121.1620, title: "Road Accident - Lipa Proper", status: "Pending" },
        { id: "2026-0044", lat: 13.9550, lng: 121.1550, title: "Vehicle Violation - Sabang", status: "Investigating" }
    ];

    // 4. Add pins to the map
    incidents.forEach(incident => {
        const marker = L.marker([incident.lat, incident.lng]).addTo(map);
        marker.bindPopup(`
            <div class="text-sm">
                <b class="text-tf-blue">${incident.id}</b><br>
                ${incident.title}<br>
                <span class="text-xs font-bold uppercase ${incident.status === 'Pending' ? 'text-red-500' : 'text-yellow-600'}">
                    ${incident.status}
                </span>
            </div>
        `);
    });
</script>
</body>

</html>
