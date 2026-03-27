<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce - Case Details</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #FFFFFF; color: #1A1A1A; }
        .bg-tf-blue { background-color: #0B3D91; }
        .bg-tf-red { background-color: #CE1126; }
        .text-tf-yellow { color: #FFD700; }
        .text-tf-blue { color: #0B3D91; }
        .text-tf-red { color: #CE1126; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #0B3D91; border-radius: 10px; }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <nav class="flex text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2">
                        <a href="#" class="hover:text-tf-blue transition-colors">Incidents</a>
                        <span class="mx-2 text-gray-300">/</span>
                        <span class="text-tf-red">Case Details</span>
                    </nav>
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight flex items-center gap-3">
                        CASE #2026-0045 
                        <span class="bg-red-100 text-tf-red px-3 py-1 rounded-full text-[10px] tracking-tighter shadow-sm border border-red-200">
                            PENDING REVIEW
                        </span>
                    </h1>
                </div>
                <div class="flex gap-2">
<a href="{{ route('investigator.incident.print.case.page') }}" target="_blank"
   class="bg-white hover:bg-gray-50 text-gray-600 px-5 py-2.5 rounded-xl text-xs font-black border border-gray-200 transition-all flex items-center gap-2 shadow-sm">
   <i class="fa-solid fa-print"></i> PRINT REPORT
</a>
                    <button class="bg-tf-blue hover:bg-blue-900 text-white px-6 py-2.5 rounded-xl text-xs font-black transition-all shadow-lg hover:shadow-blue-900/20 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> EDIT CASE
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pb-12">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-tf-blue px-6 py-4">
                            <h3 class="text-white text-[11px] font-black uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-tf-yellow"></i> Core Information
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Incident Type</label>
                                <p class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                    <i class="fa-solid fa-car-burst text-tf-red"></i> Road Accident
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Road Condition</label>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-tight">Wet / Slippery</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Weather</label>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-tight">Raining</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-50">
                                <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Assigned Investigator</label>
                                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                    <div class="w-10 h-10 bg-tf-blue rounded-full flex items-center justify-center text-white font-black text-xs">RC</div>
                                    <div>
                                        <p class="text-xs font-black text-tf-blue">Cpl. Russel Cuevas</p>
                                        <p class="text-[10px] text-gray-400">Badge #LPA-9921</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-[11px] font-black text-tf-blue uppercase tracking-widest mb-4">Case History</h3>
                        <div class="space-y-4">
                            <div class="flex gap-3 relative before:absolute before:left-2 before:top-6 before:bottom-0 before:w-0.5 before:bg-gray-100">
                                <div class="w-4 h-4 rounded-full bg-tf-red shadow-sm z-10 flex-shrink-0 mt-1 border-2 border-white"></div>
                                <div>
                                    <p class="text-[11px] font-bold text-gray-700">Incident Reported</p>
                                    <p class="text-[9px] text-gray-400">March 21, 2026 - 02:30 PM</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="w-4 h-4 rounded-full bg-gray-200 z-10 flex-shrink-0 mt-1 border-2 border-white"></div>
                                <div>
                                    <p class="text-[11px] font-bold text-gray-400 italic">Awaiting Review</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h3 class="text-[11px] font-black text-tf-blue uppercase flex items-center gap-2">
                                <i class="fa-solid fa-map-location-dot text-tf-red"></i> Incident Scene
                            </h3>
                            <span class="text-[9px] font-mono text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">13.9414, 121.1644</span>
                        </div>
                        <div id="viewMap" class="w-full h-[300px] bg-slate-100 rounded-2xl border border-gray-100 overflow-hidden z-0"></div>
                        <div class="mt-4 p-4 bg-blue-50/50 border border-blue-100 rounded-2xl flex items-start gap-4">
                            <i class="fa-solid fa-location-dot text-tf-red mt-1"></i>
                            <div>
                                <label class="text-[9px] font-black text-tf-blue uppercase">Verified Landmark / Address</label>
                                <p class="text-sm font-bold text-gray-700 leading-tight">Sabang-Lipa Road, Brgy. Sabang, Lipa City, Batangas, 4217</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-[11px] font-black text-tf-blue uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-users text-tf-red"></i> Involved Parties
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-tf-red text-white text-[8px] px-3 py-1 font-black rounded-bl-lg uppercase">Driver</div>
                                <p class="text-xs font-black text-tf-blue mb-1 uppercase">Juan Dela Cruz</p>
                                <p class="text-[10px] text-gray-500 mb-2 font-bold uppercase tracking-tighter">28 • Male • License: B01-XX-XXXX</p>
                                <span class="bg-green-100 text-green-700 text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest">Unharmed</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-tf-blue text-white text-[8px] px-3 py-1 font-black rounded-bl-lg uppercase">Passenger</div>
                                <p class="text-xs font-black text-tf-blue mb-1 uppercase">Maria Clara</p>
                                <p class="text-[10px] text-gray-500 mb-2 font-bold uppercase tracking-tighter">24 • Female</p>
                                <span class="bg-yellow-100 text-yellow-700 text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest">Minor Injury</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-[11px] font-black text-tf-blue uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-car-side text-tf-red"></i> Vehicles Involved
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-tf-blue shadow-sm border border-gray-100">
                                        <i class="fa-solid fa-car text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-tf-blue uppercase">NCO-1234</p>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase">SUV • Gray • Toyota Fortuner</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-tf-blue shadow-sm border border-gray-100">
                                        <i class="fa-solid fa-motorcycle text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-tf-blue upperwcase">123-ABC</p>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase">Motorcycle • Red/Black • Honda Click</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-[11px] font-black text-tf-blue uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-camera text-tf-red"></i> Scene Evidence
                            </h3>
                            <p class="text-[9px] font-black text-gray-400 uppercase">1 File/s Uploaded</p>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="aspect-square rounded-2xl bg-gray-100 border border-gray-200 overflow-hidden relative group cursor-pointer">
                                <img src="https://images.unsplash.com/photo-1594909122845-11baa439b7bf?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-tf-blue/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-magnifying-glass-plus text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = 13.9414;
            const lng = 121.1644;
            const map = L.map('viewMap', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup("<b class='text-tf-blue font-black uppercase text-[10px]'>Incident Ground Zero</b>").openPopup();
        });
    </script>
</body>

</html>