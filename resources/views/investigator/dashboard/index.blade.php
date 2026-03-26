<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce - Lipa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <header class="bg-tf-blue h-16 flex items-center justify-between px-6 shadow-lg z-20">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo.png') }}" alt="Trackforce Lipa Logo" class="h-10 w-auto object-contain">
            <h3 class="text-white font-bold tracking-wider">TRACKFORCE <br> LIPA - PNP</h3>
        </div>
        <div class="flex items-center gap-6">
            <div class="relative cursor-pointer">
                <i class="fa-solid fa-bell text-white text-xl"></i>
                <span
                    class="absolute -top-2 -right-2 bg-tf-red text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">3</span>
            </div>
            <div class="flex items-center gap-3 border-l border-blue-800 pl-6 text-white">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-medium">Investigator Alpha</p>
                    <p class="text-[10px] opacity-75 uppercase">Badge #0421</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-tf-blue font-bold">IA
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <aside class="bg-tf-blue w-20 lg:w-64 flex flex-col transition-all duration-300">
            <nav class="flex-1 px-3 py-6 space-y-2">
                <a href="#" class="flex items-center gap-4 p-3 rounded-lg nav-active group">
                    <i class="fa-solid fa-chart-line text-tf-yellow group-hover:scale-110 transition-transform"></i>
                    <span class="hidden lg:block font-medium">Dashboard</span>
                </a>

                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-users group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Accounts</span>
                </a>

                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-file-signature group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Documentations</span>
                </a>

                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-map-location-dot group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Incident Map View</span>
                </a>

                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-list-check group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Incident Reports</span>
                </a>

                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-history group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Audit Trail Logs</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <button
                    class="w-full bg-tf-red text-white py-2 rounded font-bold text-sm uppercase flex items-center justify-center gap-2">
                    <i class="fa-solid fa-power-off"></i>
                    <span class="hidden lg:block">Logout</span>
                </button>
            </div>
        </aside>

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
                        <h3 class="font-bold text-lg"><i class="fa-solid fa-map-pin text-tf-red mr-2"></i>Live Incident
                            Map</h3>
                        <button class="text-xs bg-tf-blue text-white px-3 py-1 rounded">Expand Map</button>
                    </div>
                    <div
                        class="w-full h-72 bg-gray-200 rounded-lg flex items-center justify-center relative overflow-hidden">
                        <div
                            class="absolute inset-0 opacity-40 bg-[url('https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&q=80&w=1000')] bg-cover">
                        </div>
                        <i class="fa-solid fa-map-location-dot text-5xl text-gray-400 z-10"></i>
                    </div>
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

            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-8 border-b pb-4">
                    <h3 class="font-bold text-lg mb-4"> <i
                            class="fa-solid fa-file-signature text-tf-blue text-3xl"></i>
                        Incident Reporting Form</h3>
                </div>

                <form class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Incident Type &
                                Vehicle</label>
                            <div class="grid grid-cols-2 gap-2">
                                <select
                                    class="w-full border p-2 text-sm rounded-lg bg-gray-50 outline-none focus:ring-2 focus:ring-tf-blue">
                                    <option>Traffic Accident</option>
                                    <option>Violation</option>
                                </select>
                                <select
                                    class="w-full border p-2 text-sm rounded-lg bg-gray-50 outline-none focus:ring-2 focus:ring-tf-blue">
                                    <option>Motorcycle</option>
                                    <option>Private Car</option>
                                    <option>PUJ/Bus</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Driver
                                Demographics</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" placeholder="Age"
                                    class="w-full border p-2 text-sm rounded-lg outline-none">
                                <select class="w-full border p-2 text-sm rounded-lg outline-none">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Environmental
                                Factors</label>
                            <div class="grid grid-cols-2 gap-2">
                                <select class="border p-2 text-[11px] rounded-lg bg-white">
                                    <option>Weather: Clear</option>
                                    <option>Weather: Raining</option>
                                </select>
                                <select class="border p-2 text-[11px] rounded-lg bg-white">
                                    <option>Road: Dry</option>
                                    <option>Road: Wet/Slippery</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Narrative
                                Description</label>
                            <textarea rows="4" placeholder="Enter witness statements or situational details..."
                                class="w-full border p-2 text-sm rounded-lg bg-gray-50 outline-none"></textarea>
                        </div>
                        <div class="flex gap-4">
                            <button type="button"
                                class="flex-1 border-2 border-dashed border-tf-blue rounded-lg p-2 text-tf-blue text-xs font-bold hover:bg-blue-50">
                                <i class="fa-solid fa-camera mr-1"></i> PHOTOS
                            </button>
                            <button type="submit"
                                class="flex-1 bg-tf-red text-white rounded-lg font-black shadow-lg hover:shadow-red-200 transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-shield-check"></i> SUBMIT REPORT
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
</body>

</html>
