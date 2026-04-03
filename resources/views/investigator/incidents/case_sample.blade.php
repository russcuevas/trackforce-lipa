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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
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

        .text-tf-blue {
            color: #0B3D91;
        }

        .text-tf-red {
            color: #CE1126;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #0B3D91;
            border-radius: 10px;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')

        @php
            $statusRaw = trim((string) ($incident->status ?? 'Pending'));
            $statusLower = strtolower($statusRaw);
            $isPending = in_array($statusLower, ['pending', 'pending review'], true);
            $isAccepted = $statusLower === 'accepted';
            $isUnderInvestigation = in_array(
                $statusLower,
                ['under investigation', 'investigating', 'in progress'],
                true,
            );

            $statusStyles = 'bg-gray-100 text-gray-700 border-gray-200';
            if ($isPending) {
                $statusStyles = 'bg-red-100 text-tf-red border-red-200';
            } elseif ($isAccepted) {
                $statusStyles = 'bg-blue-100 text-blue-700 border-blue-200';
            } elseif (in_array($statusLower, ['under investigation', 'investigating', 'in progress'], true)) {
                $statusStyles = 'bg-yellow-100 text-yellow-700 border-yellow-200';
            } elseif ($statusLower === 'declined') {
                $statusStyles = 'bg-gray-200 text-gray-700 border-gray-300';
            } elseif (in_array($statusLower, ['resolved', 'completed', 'closed'], true)) {
                $statusStyles = 'bg-green-100 text-green-700 border-green-200';
            }

            $incidentTypeOptions = ['Accident', 'Violation', 'Public Disturbance'];
            $currentIncidentType = (string) old('incident_type', $incident->incident_type ?? '');
            $selectedIncidentType = in_array($currentIncidentType, $incidentTypeOptions, true)
                ? $currentIncidentType
                : 'Other';
            $incidentTypeOther = old(
                'incident_type_other',
                $selectedIncidentType === 'Other' ? $currentIncidentType : '',
            );

            $editableParties = collect(old('party', $involvedParties->all()))
                ->map(function ($party) {
                    return [
                        'uid' => (string) \Illuminate\Support\Str::uuid(),
                        'name' => (string) data_get($party, 'name', data_get($party, 'full_name', '')),
                        'age' => data_get($party, 'age', ''),
                        'sex' => (string) data_get($party, 'sex', ''),
                        'role' => (string) data_get($party, 'role', ''),
                        'severity' => (string) data_get($party, 'severity', data_get($party, 'injury_severity', '')),
                        'license_number' => (string) data_get($party, 'license_number', ''),
                        'statement' => (string) data_get($party, 'statement', ''),
                    ];
                })
                ->values()
                ->all();

            if (empty($editableParties)) {
                $editableParties[] = [
                    'uid' => (string) \Illuminate\Support\Str::uuid(),
                    'name' => '',
                    'age' => '',
                    'sex' => '',
                    'role' => 'Driver',
                    'severity' => 'Unharmed',
                    'license_number' => '',
                    'statement' => '',
                ];
            }

            $showEditModalOnLoad = old('edit_case_details') === '1';
            $assignedInvestigator = $incident->assignedInvestigator;

            $reportedAt = $incident->time_reported ?? $incident->created_at;
            $acceptedAt = $incident->time_accepted;
            if (
                !$acceptedAt &&
                $assignedInvestigator &&
                in_array(
                    $statusLower,
                    ['accepted', 'under investigation', 'investigating', 'in progress', 'resolved', 'completed'],
                    true,
                )
            ) {
                $acceptedAt = $statusLower === 'accepted' ? $incident->time_documented ?? null : null;
            }

            $underInvestigationAt = $incident->time_under_investigation;
            if (
                !$underInvestigationAt &&
                in_array(
                    $statusLower,
                    ['under investigation', 'investigating', 'in progress', 'resolved', 'completed'],
                    true,
                )
            ) {
                $underInvestigationAt = $incident->time_documented ?? null;
            }

            $completedAt = in_array($statusLower, ['resolved', 'completed'], true) ? $incident->time_completed : null;

            if ($statusLower === 'declined') {
                $declinedAt =
                    $incident->time_declined ?? ($incident->time_documented ?? ($incident->updated_at ?? null));

                $caseHistory = [
                    [
                        'label' => 'Incident Reported',
                        'timestamp' => $reportedAt,
                        'color' => 'bg-tf-red',
                    ],
                    [
                        'label' => 'Case Declined',
                        'timestamp' => $declinedAt,
                        'color' => 'bg-gray-200',
                    ],
                ];
            } else {
                $caseHistory = [
                    [
                        'label' => 'Incident Reported',
                        'timestamp' => $reportedAt,
                        'color' => 'bg-tf-red',
                    ],
                    [
                        'label' => 'Case Accepted',
                        'timestamp' => $acceptedAt,
                        'color' => 'bg-tf-blue',
                    ],
                    [
                        'label' => 'Under Investigation',
                        'timestamp' => $underInvestigationAt,
                        'color' => 'bg-yellow-400',
                    ],
                    [
                        'label' => 'Case Completed',
                        'timestamp' => $completedAt,
                        'color' => 'bg-emerald-500',
                    ],
                ];
            }

            $partyAvatarImage =
                'https://static.vecteezy.com/system/resources/previews/019/879/186/original/user-icon-on-transparent-background-free-png.png';
        @endphp

        <main
            x-data='caseDetailsEditor({ initialParties: @json($editableParties), initialIncidentType: @json($selectedIncidentType), openOnLoad: @json($showEditModalOnLoad) })'
            class="flex-1 overflow-y-auto p-6 bg-gray-50">

            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <nav class="flex text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2">
                        <a href="{{ route('investigator.incident.report.page') }}"
                            class="hover:text-tf-blue transition-colors">Incidents</a>
                        <span class="mx-2 text-gray-300">/</span>
                        <span class="text-tf-red">Case Details</span>
                    </nav>
                    <h1
                        class="text-2xl font-black text-tf-blue uppercase tracking-tight flex items-center gap-3 flex-wrap">
                        CASE #{{ $incident->report_number ?? 'INC-' . $incident->id }}
                        <span
                            class="{{ $statusStyles }} px-3 py-1 rounded-full text-[10px] tracking-tighter shadow-sm border uppercase">
                            {{ $statusRaw !== '' ? $statusRaw : 'Pending' }}
                        </span>
                    </h1>
                </div>
                <div class="flex gap-2 flex-wrap">
                    @if ($isAccepted)
                        <button type="button" @click="openEditModal = true"
                            class="bg-tf-blue hover:bg-blue-950 text-white px-5 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-pen-to-square text-tf-yellow"></i> EDIT DETAILS
                        </button>
                    @endif
                    @if ($isUnderInvestigation)
                        <form method="POST" class="js-resolve-case-form"
                            action="{{ route('investigator.incident.status.update', ['incident' => $incident->id]) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Resolved">
                            <button type="submit"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2 shadow-sm">
                                <i class="fa-solid fa-circle-check"></i> CASE RESOLVED
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('investigator.documentation.print.report.page', ['incident' => $incident->id]) }}"
                        target="_blank"
                        class="bg-white hover:bg-gray-50 text-gray-600 px-5 py-2.5 rounded-xl text-xs font-black border border-gray-200 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-print"></i> PRINT REPORT
                    </a>
                    @if ($isPending)
                        <form method="POST"
                            action="{{ route('investigator.incident.status.update', ['incident' => $incident->id]) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Accepted">
                            <button type="submit"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2 shadow-sm">
                                <i class="fa-solid fa-check"></i> ACCEPT
                            </button>
                        </form>
                        <form method="POST"
                            action="{{ route('investigator.incident.status.update', ['incident' => $incident->id]) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Declined">
                            <button type="submit"
                                class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2 shadow-sm">
                                <i class="fa-solid fa-xmark"></i> DECLINE
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pb-12">
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-tf-blue px-6 py-4">
                            <h3
                                class="text-white text-[11px] font-black uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-tf-yellow"></i> Core Information
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Incident
                                    Type</label>
                                <p class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                    <i class="fa-solid fa-car-burst text-tf-red"></i>
                                    {{ $incident->incident_type ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Road
                                        Condition</label>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-tight">
                                        {{ $incident->road_condition ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase block mb-1">Weather</label>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-tight">
                                        {{ $incident->weather_condition ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-50">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase block mb-2">Reporter</label>
                                <p class="text-xs font-black text-tf-blue">{{ $incident->reporter_name ?? 'N/A' }}</p>
                                <p class="text-[10px] text-gray-400">
                                    {{ $incident->reporter_contact ?? 'No contact provided' }}</p>
                            </div>

                            <div class="pt-4 border-t border-gray-50">
                                <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">
                                    Investigator Handling This Case
                                </label>
                                @if ($assignedInvestigator)
                                    <p class="text-xs font-black text-tf-blue uppercase">
                                        {{ $assignedInvestigator->full_name }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">
                                        Badge #: {{ $assignedInvestigator->badge_number ?? 'N/A' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400">
                                        {{ $assignedInvestigator->email ?? 'No email available' }}
                                    </p>
                                @else
                                    <p class="text-xs font-bold text-gray-400">No investigator assigned yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-[11px] font-black text-tf-blue uppercase tracking-widest mb-4">Case History</h3>
                        <div class="space-y-4">
                            @foreach ($caseHistory as $historyItem)
                                <div class="flex gap-3 {{ !$loop->last ? 'relative' : '' }}">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-4 h-4 rounded-full {{ $historyItem['timestamp'] ? $historyItem['color'] : 'bg-gray-200' }} shadow-sm z-10 flex-shrink-0 mt-1 border-2 border-white">
                                        </div>
                                        @if (!$loop->last)
                                            <div class="w-px flex-1 bg-gray-200 mt-1"></div>
                                        @endif
                                    </div>
                                    <div class="pb-2">
                                        <p
                                            class="text-[11px] font-bold {{ $historyItem['timestamp'] ? 'text-gray-700' : 'text-gray-400' }}">
                                            {{ $historyItem['label'] }}
                                        </p>
                                        <p
                                            class="text-[9px] {{ $historyItem['timestamp'] ? 'text-gray-400' : 'text-gray-300 uppercase' }}">
                                            {{ $historyItem['timestamp'] ? \Illuminate\Support\Carbon::parse($historyItem['timestamp'])->format('M d, Y - h:i A') : 'Waiting for update' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h3 class="text-[11px] font-black text-tf-blue uppercase flex items-center gap-2">
                                <i class="fa-solid fa-map-location-dot text-tf-red"></i> Incident Scene
                            </h3>
                            <span
                                class="text-[9px] font-mono text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                                {{ number_format((float) $incident->latitude, 6) }},
                                {{ number_format((float) $incident->longitude, 6) }}
                            </span>
                        </div>
                        <div id="viewMap"
                            class="w-full h-[300px] bg-slate-100 rounded-2xl border border-gray-100 overflow-hidden z-0">
                        </div>
                        <div class="mt-4 p-4 bg-blue-50/50 border border-blue-100 rounded-2xl flex items-start gap-4">
                            <i class="fa-solid fa-location-dot text-tf-red mt-1"></i>
                            <div>
                                <label class="text-[9px] font-black text-tf-blue uppercase">Verified Landmark /
                                    Address</label>
                                <p class="text-sm font-bold text-gray-700 leading-tight">
                                    {{ $incident->location_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if ($isAccepted)
                            <div class="mt-3 p-3 rounded-2xl border border-emerald-100 bg-emerald-50">
                                <p id="routeStatus" class="text-xs font-bold text-emerald-700">Detecting your current
                                    location...</p>
                                <p id="routeMeta" class="text-[10px] text-emerald-600 mt-1"></p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h3
                            class="text-[11px] font-black text-tf-blue uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-users text-tf-red"></i> Involved Parties
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse ($involvedParties as $party)
                                <div
                                    class="p-4 rounded-2xl bg-gray-50 border border-gray-100 relative overflow-hidden">
                                    <div
                                        class="absolute top-0 right-0 bg-tf-blue text-white text-[8px] px-3 py-1 font-black rounded-bl-lg uppercase">
                                        {{ $party->role ?? 'N/A' }}
                                    </div>
                                    <div class="flex items-center gap-3 pr-16">
                                        <img src="{{ $partyAvatarImage }}" alt="Party Avatar"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm"
                                            onerror="this.style.display='none';">
                                        <div>
                                            <p class="text-xs font-black text-tf-blue uppercase leading-tight">
                                                {{ $party->full_name ?? 'N/A' }}</p>
                                            <p
                                                class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter mt-0.5">
                                                {{ $party->age ?? 'N/A' }} • {{ $party->sex ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-3 font-bold uppercase tracking-tighter">
                                        License: {{ $party->license_number ?? 'N/A' }}
                                    </p>
                                    <span
                                        class="inline-flex mt-2 bg-yellow-100 text-yellow-700 text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest">
                                        {{ $party->injury_severity ?? 'N/A' }}
                                    </span>
                                    @if (filled($party->statement))
                                        <p class="mt-3 text-[10px] font-bold text-gray-500 leading-relaxed">
                                            {{ $party->statement }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm font-bold text-gray-400">No involved parties recorded.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h3
                            class="text-[11px] font-black text-tf-blue uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-car-side text-tf-red"></i> Vehicles Involved
                        </h3>
                        <div class="space-y-3">
                            @forelse ($vehicles as $vehicle)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-tf-blue shadow-sm border border-gray-100">
                                            <i class="fa-solid fa-car text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-tf-blue uppercase">
                                                {{ $vehicle->plate_number ?? 'N/A' }}</p>
                                            <p class="text-[10px] font-bold text-gray-500 uppercase">
                                                <span style="color: #CE1126">{{ $vehicle->vehicle_type ?? 'N/A' }} •
                                                    {{ $vehicle->specific_name ?? 'N/A' }}
                                                    [{{ $vehicle->color ?? 'N/A' }}]</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm font-bold text-gray-400">No vehicles recorded.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3
                                class="text-[11px] font-black text-tf-blue uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-camera text-tf-red"></i> Scene Evidence
                            </h3>
                            <p class="text-[9px] font-black text-gray-400 uppercase">{{ $evidences->count() }} File/s
                                Uploaded</p>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @forelse ($evidences as $evidence)
                                @php
                                    $rawPath = trim((string) ($evidence->file_path ?? ''));
                                    $isAbsolute = \Illuminate\Support\Str::startsWith($rawPath, [
                                        'http://',
                                        'https://',
                                        '/storage/',
                                    ]);
                                    $assetPath = $isAbsolute
                                        ? $rawPath
                                        : asset('storage/evidence/' . ltrim($rawPath, '/'));
                                @endphp
                                <a href="{{ $assetPath }}" target="_blank"
                                    class="aspect-square rounded-2xl bg-gray-100 border border-gray-200 overflow-hidden relative group cursor-pointer">
                                    <img src="{{ $assetPath }}" class="w-full h-full object-cover"
                                        alt="Evidence">
                                    <div
                                        class="absolute inset-0 bg-tf-blue/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-magnifying-glass-plus text-white"></i>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm font-bold text-gray-400">No evidence uploaded.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @if ($isAccepted || $showEditModalOnLoad)
                <template x-teleport="body">
                    <div x-show="openEditModal"
                        class="fixed inset-0 z-[120] flex items-center justify-center p-0 sm:p-4" x-cloak>
                        <div @click="openEditModal = false" x-show="openEditModal" x-transition.opacity
                            class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

                        <div x-show="openEditModal" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-10"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="relative bg-white w-full max-w-5xl h-full sm:h-[92vh] flex flex-col rounded-t-[2rem] sm:rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

                            <div
                                class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
                                <div>
                                    <h2 class="text-base sm:text-xl font-black text-tf-blue uppercase">Edit Case
                                        Details</h2>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                        Submitting will move this case to Under Investigation
                                    </p>
                                </div>
                                <button type="button" @click="openEditModal = false"
                                    class="p-2 hover:bg-gray-100 rounded-full text-gray-400">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>
                            </div>

                            <form id="editIncidentDetailsForm" method="POST"
                                action="{{ route('investigator.incident.details.update', ['incident' => $incident->id]) }}"
                                class="flex-1 overflow-y-auto bg-gray-50/60">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="edit_case_details" value="1">

                                <div class="p-4 sm:p-8 space-y-6">
                                    <section class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                        <div
                                            class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                                            <div>
                                                <h3
                                                    class="text-[10px] font-black text-tf-blue uppercase tracking-widest">
                                                    Case Details</h3>
                                                <p class="text-[10px] font-bold text-gray-400 mt-1">Update the incident
                                                    summary before starting the investigation.</p>
                                            </div>
                                            <div class="px-4 py-3 rounded-2xl border border-blue-100 bg-blue-50">
                                                <p class="text-[9px] font-black text-tf-blue uppercase">Case Number</p>
                                                <p class="text-xs font-black text-gray-600">
                                                    {{ $incident->report_number ?? 'INC-' . $incident->id }}</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="md:col-span-2">
                                                <label
                                                    class="text-[10px] font-black text-gray-400 uppercase ml-1">Incident
                                                    Type</label>
                                                <select name="incident_type" x-model="incidentType"
                                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold"
                                                    required>
                                                    <option value="Accident">Accident</option>
                                                    <option value="Violation">Violation</option>
                                                    <option value="Public Disturbance">Public Disturbance</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <input type="text" name="incident_type_other"
                                                    x-show="incidentType === 'Other'" x-cloak
                                                    value="{{ $incidentTypeOther }}"
                                                    placeholder="Specify Incident Type"
                                                    class="mt-3 w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Road
                                                    Condition</label>
                                                <select name="road_condition"
                                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold">
                                                    <option value="" @selected(old('road_condition', $incident->road_condition) === '')>Select Road
                                                        Condition</option>
                                                    <option value="Dry" @selected(old('road_condition', $incident->road_condition) === 'Dry')>Dry</option>
                                                    <option value="Wet" @selected(old('road_condition', $incident->road_condition) === 'Wet')>Wet</option>
                                                    <option value="Under Construction" @selected(old('road_condition', $incident->road_condition) === 'Under Construction')>
                                                        Under Construction</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="text-[10px] font-black text-gray-400 uppercase ml-1">Weather
                                                    Condition</label>
                                                <select name="weather_condition"
                                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold">
                                                    <option value="" @selected(old('weather_condition', $incident->weather_condition) === '')>Select Weather
                                                        Condition</option>
                                                    <option value="Clear" @selected(old('weather_condition', $incident->weather_condition) === 'Clear')>Clear</option>
                                                    <option value="Raining" @selected(old('weather_condition', $incident->weather_condition) === 'Raining')>Raining
                                                    </option>
                                                    <option value="Foggy" @selected(old('weather_condition', $incident->weather_condition) === 'Foggy')>Foggy</option>
                                                </select>
                                            </div>
                                        </div>
                                    </section>

                                    <section class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                        <div class="flex justify-between items-center mb-4 gap-3 flex-wrap">
                                            <div>
                                                <h3
                                                    class="text-[10px] font-black text-tf-blue uppercase tracking-widest">
                                                    Involved Parties</h3>
                                                <p class="text-[10px] font-bold text-gray-400 mt-1">Add, remove, or
                                                    revise the party statements for this case.</p>
                                            </div>
                                            <button type="button" @click="addParty()"
                                                class="bg-tf-blue hover:bg-blue-950 text-white px-4 py-2 rounded-xl text-[11px] font-black flex items-center gap-2 shadow-sm">
                                                <i class="fa-solid fa-plus text-tf-yellow"></i> ADD PARTY
                                            </button>
                                        </div>

                                        <div class="space-y-4">
                                            <template x-for="(party, index) in involvedParties" :key="party.uid">
                                                <div
                                                    class="p-4 bg-gray-50 rounded-2xl border border-gray-200 relative">
                                                    <button type="button" @click="removeParty(index)"
                                                        class="absolute top-3 right-3 text-gray-300 hover:text-red-500">
                                                        <i class="fa-solid fa-circle-xmark text-lg"></i>
                                                    </button>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div class="md:col-span-2">
                                                            <label
                                                                class="text-[10px] font-black text-gray-400 uppercase ml-1">Full
                                                                Name</label>
                                                            <input type="text" :name="`party[${index}][name]`"
                                                                x-model="party.name" placeholder="Full Name"
                                                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="text-[10px] font-black text-gray-400 uppercase ml-1">Age</label>
                                                            <input type="number" min="0"
                                                                :name="`party[${index}][age]`" x-model="party.age"
                                                                placeholder="Age"
                                                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="text-[10px] font-black text-gray-400 uppercase ml-1">Sex</label>
                                                            <select :name="`party[${index}][sex]`" x-model="party.sex"
                                                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                                                <option value="">Select Sex</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="text-[10px] font-black text-gray-400 uppercase ml-1">Role</label>
                                                            <select :name="`party[${index}][role]`"
                                                                x-model="party.role"
                                                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                                                <option value="">Select Role</option>
                                                                <option value="Driver">Driver</option>
                                                                <option value="Passenger">Passenger</option>
                                                                <option value="Pedestrian">Pedestrian</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="text-[10px] font-black text-gray-400 uppercase ml-1">Injury
                                                                Severity</label>
                                                            <select :name="`party[${index}][severity]`"
                                                                x-model="party.severity"
                                                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                                                <option value="">Select Severity</option>
                                                                <option value="Unharmed">Unharmed</option>
                                                                <option value="Minor">Minor</option>
                                                                <option value="Serious">Serious</option>
                                                                <option value="Fatal">Fatal</option>
                                                            </select>
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <label
                                                                class="text-[10px] font-black text-gray-400 uppercase ml-1">License
                                                                Number</label>
                                                            <input type="text"
                                                                :name="`party[${index}][license_number]`"
                                                                x-model="party.license_number"
                                                                placeholder="License Number"
                                                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <label
                                                                class="text-[10px] font-black text-gray-400 uppercase ml-1">Statement</label>
                                                            <textarea :name="`party[${index}][statement]`" x-model="party.statement" rows="4" placeholder="Party statement"
                                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold resize-none"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </section>
                                </div>
                            </form>

                            <div
                                class="p-4 sm:p-6 bg-white border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3 shrink-0">
                                <button type="button" @click="openEditModal = false"
                                    class="order-2 sm:order-1 px-8 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest">
                                    Cancel
                                </button>
                                <button type="submit" form="editIncidentDetailsForm"
                                    class="order-1 sm:order-2 bg-tf-blue text-white px-10 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-900/20 flex items-center justify-center gap-3 transition-all active:scale-95">
                                    <i class="fa-solid fa-floppy-disk text-tf-yellow"></i>
                                    SAVE AND START INVESTIGATION
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            @endif
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        function caseDetailsEditor(config) {
            return {
                openEditModal: Boolean(config.openOnLoad),
                incidentType: config.initialIncidentType || 'Accident',
                involvedParties: [],
                init() {
                    const parties = Array.isArray(config.initialParties) ? config.initialParties : [];

                    this.involvedParties = parties.length ?
                        parties.map((party, index) => ({
                            uid: party.uid || `${Date.now()}-${index}`,
                            name: party.name || '',
                            age: party.age ?? '',
                            sex: party.sex || '',
                            role: party.role || 'Driver',
                            severity: party.severity || 'Unharmed',
                            license_number: party.license_number || '',
                            statement: party.statement || ''
                        })) : [this.makeParty()];
                },
                makeParty() {
                    return {
                        uid: `${Date.now()}-${Math.random()}`,
                        name: '',
                        age: '',
                        sex: '',
                        role: 'Driver',
                        severity: 'Unharmed',
                        license_number: '',
                        statement: ''
                    };
                },
                addParty() {
                    this.involvedParties.push(this.makeParty());
                },
                removeParty(index) {
                    if (this.involvedParties.length === 1) {
                        this.involvedParties.splice(0, 1, this.makeParty());
                        return;
                    }

                    this.involvedParties.splice(index, 1);
                }
            };
        }
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
        document.addEventListener('DOMContentLoaded', function() {
            const resolveCaseForms = document.querySelectorAll('.js-resolve-case-form');

            resolveCaseForms.forEach((form) => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to resolve this case?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#059669',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, resolve case',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            const incidentLat = Number(@json((float) $incident->latitude));
            const incidentLng = Number(@json((float) $incident->longitude));
            const isAccepted = @json($isAccepted);

            const map = L.map('viewMap', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([incidentLat, incidentLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const incidentMarker = L.marker([incidentLat, incidentLng]).addTo(map);
            incidentMarker.bindPopup(
                "<b class='text-tf-blue font-black uppercase text-[10px]'>Incident Location</b>");

            if (!isAccepted || !navigator.geolocation) {
                return;
            }

            const routeStatusEl = document.getElementById('routeStatus');
            const routeMetaEl = document.getElementById('routeMeta');

            let currentMarker = null;
            let routeLine = null;
            let lastRouteFetchAt = 0;

            const updateRoute = async (currentLat, currentLng) => {
                const now = Date.now();
                if (now - lastRouteFetchAt < 10000) {
                    return;
                }
                lastRouteFetchAt = now;

                try {
                    const response = await fetch(
                        `https://router.project-osrm.org/route/v1/driving/${currentLng},${currentLat};${incidentLng},${incidentLat}?overview=full&geometries=geojson`
                    );

                    const data = await response.json();
                    const route = data?.routes?.[0];
                    if (!route) {
                        routeStatusEl.textContent = 'Unable to get route to incident location right now.';
                        return;
                    }

                    const latLngs = route.geometry.coordinates.map(([lng, lat]) => [lat, lng]);
                    if (routeLine) {
                        routeLine.remove();
                    }

                    routeLine = L.polyline(latLngs, {
                        color: '#0B3D91',
                        weight: 5,
                        opacity: 0.9
                    }).addTo(map);

                    const distanceKm = (route.distance / 1000).toFixed(2);
                    const durationMin = Math.ceil(route.duration / 60);

                    routeStatusEl.textContent = 'Live route to incident is active.';
                    routeMetaEl.textContent = `Distance: ${distanceKm} km • ETA: ${durationMin} min`;

                    const group = L.featureGroup([incidentMarker, currentMarker, routeLine]);
                    map.fitBounds(group.getBounds(), {
                        padding: [24, 24]
                    });
                } catch (error) {
                    routeStatusEl.textContent =
                        'Routing service unavailable. Retrying with your next location update.';
                }
            };

            navigator.geolocation.watchPosition((position) => {
                const currentLat = position.coords.latitude;
                const currentLng = position.coords.longitude;

                if (!currentMarker) {
                    currentMarker = L.circleMarker([currentLat, currentLng], {
                        radius: 8,
                        color: '#16a34a',
                        fillColor: '#22c55e',
                        fillOpacity: 0.8
                    }).addTo(map);
                } else {
                    currentMarker.setLatLng([currentLat, currentLng]);
                }

                updateRoute(currentLat, currentLng);
            }, (error) => {
                routeStatusEl.textContent = 'Please allow location permission to show the live route.';
                routeMetaEl.textContent = error?.message || '';
            }, {
                enableHighAccuracy: true,
                maximumAge: 5000,
                timeout: 15000
            });
        });
    </script>
</body>

</html>
