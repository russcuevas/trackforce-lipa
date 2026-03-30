<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Case #{{ $incident->report_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 10mm;
            }

            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
                font-size: 11px;
            }

            .print-container {
                width: 100% !important;
                border: none !important;
                padding: 0 !important;
            }

            .evidence-sheet {
                page-break-before: always;
                break-before: page;
            }

            .evidence-item {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }

        body {
            font-family: 'Courier New', Courier, monospace;
        }

        /* Professional/Formal feel */
        .border-heavy {
            border: 2px solid #000;
        }

        .bg-gray-doc {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
        }

        .evidence-preview {
            width: 100%;
            height: 230px;
            object-fit: contain;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background: #fff;
        }

        .evidence-sheet {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #d1d5db;
            padding: 24px;
        }

        .evidence-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .evidence-item {
            border: 1px solid #000;
            padding: 8px;
        }

        .evidence-meta {
            margin-top: 6px;
            font-size: 10px;
            color: #374151;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body class="bg-gray-100 p-4">

    @php
        $incidentDateTime = $incident->time_completed ?? ($incident->time_documented ?? $incident->time_reported);
        $investigatorName = trim((string) ($incident->investigator_name ?? ''));
        $investigatorLabel = $investigatorName !== '' ? $investigatorName : 'Unassigned';
        if (!empty($incident->investigator_badge_number)) {
            $investigatorLabel .= ' (#' . $incident->investigator_badge_number . ')';
        }

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

        $printableEvidences = collect($evidences)
            ->map(function ($evidence) use ($imageExtensions) {
                $rawPath = trim((string) ($evidence->file_path ?? ''));
                $normalizedPath = ltrim($rawPath, '/');
                $fileType = strtolower((string) ($evidence->file_type ?? ''));
                $extension = strtolower(pathinfo($normalizedPath, PATHINFO_EXTENSION));

                $isImage = str_starts_with($fileType, 'image/') || in_array($extension, $imageExtensions, true);
                if (!$isImage || $rawPath === '') {
                    return null;
                }

                if (\Illuminate\Support\Str::startsWith($rawPath, ['http://', 'https://'])) {
                    $url = $rawPath;
                } elseif (\Illuminate\Support\Str::startsWith($rawPath, '/storage/')) {
                    $url = $rawPath;
                } elseif (\Illuminate\Support\Str::startsWith($normalizedPath, 'storage/')) {
                    $url = asset($normalizedPath);
                } elseif (str_contains($normalizedPath, '/')) {
                    $url = asset('storage/' . $normalizedPath);
                } else {
                    // DB stores filename only, while files are under public/storage/evidence.
                    $url = asset('storage/evidence/' . $normalizedPath);
                }

                return [
                    'url' => $url,
                    'name' => basename($rawPath),
                    'type' => $evidence->file_type,
                    'uploaded_at' => $evidence->uploaded_at,
                ];
            })
            ->filter()
            ->values();

        $evidenceSheets = $printableEvidences->chunk(3);
    @endphp

    <div class="max-w-3xl mx-auto mb-4 no-print flex justify-end">
        <button onclick="window.print()" class="bg-black text-white px-4 py-2 text-xs font-bold rounded">
            PRINT REPORT
        </button>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-8 print-container border border-gray-300">
        <!-- Your content remains the same -->
        <div class="flex justify-between items-start border-b-2 border-black pb-2 mb-4">
            <div>
                <h1 class="text-xl font-black uppercase">TrackForce - Lipa City</h1>
                <p class="text-[10px] font-bold">Public Safety</p>
                <p class="text-[9px]">Brgy. Sabang, Lipa City, Batangas, 4217</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold uppercase text-gray-500">Case Identifier</p>
                <p class="text-lg font-black italic">#{{ $incident->report_number }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4 text-[11px]">
            <div class="space-y-1">
                <p><strong>INCIDENT TYPE:</strong> {{ strtoupper($incident->incident_type ?? 'N/A') }}</p>
                <p><strong>DATE/TIME:</strong>
                    {{ $incidentDateTime ? \Illuminate\Support\Carbon::parse($incidentDateTime)->format('M d, Y / h:i A') : 'N/A' }}
                </p>
                <p><strong>LOCATION:</strong> {{ strtoupper($incident->location_name ?? 'N/A') }}</p>
            </div>
            <div class="space-y-1 text-right">
                <p><strong>WEATHER:</strong> {{ strtoupper($incident->weather_condition ?? 'N/A') }}</p>
                <p><strong>ROAD COND:</strong> {{ strtoupper($incident->road_condition ?? 'N/A') }}</p>
                <p><strong>INVESTIGATOR:</strong> {{ strtoupper($investigatorLabel) }}</p>
            </div>
        </div>

        <div class="mb-4 text-[11px] border border-gray-300 p-2">
            @if (empty($incident->reporter_name) &&
                    empty($incident->reporter_contact) &&
                    empty($incident->reporter_email) &&
                    empty($incident->reporter_address))
                <p><strong>REPORTER:</strong> NO REPORTER</p>
            @else
                <p><strong>REPORTER:</strong>
                    {{ strtoupper($incident->reporter_name ?? 'N/A') }}</p>
                <p><strong>CONTACT:</strong> {{ $incident->reporter_contact ?? 'N/A' }}</p>
                <p><strong>EMAIL:</strong> {{ $incident->reporter_email ?? 'N/A' }}</p>
                <p><strong>ADDRESS:</strong>
                    {{ strtoupper($incident->reporter_address ?? 'N/A') }}</p>
            @endif

            <p><strong>STATUS:</strong> {{ strtoupper($incident->status ?? 'RESOLVED') }}</p>
        </div>

        <div class="mb-4">
            <h3 class="bg-gray-doc px-2 py-1 text-[10px] font-black border-t border-b border-black mb-1 uppercase">
                Narrative Report</h3>
            @php
                $narratives = $involvedParties
                    ->map(function ($party) {
                        $statement = trim((string) ($party->statement ?? ''));
                        $name = trim((string) ($party->full_name ?? ''));

                        return [
                            'name' => $name !== '' ? $name : 'Unknown party',
                            'statement' => $statement,
                        ];
                    })
                    ->filter(fn($narrative) => $narrative['statement'] !== '')
                    ->values();
            @endphp

            @if ($narratives->isNotEmpty())
                <div class="space-y-2">
                    @foreach ($narratives as $narrative)
                        <p class="text-[11px] leading-tight text-justify italic">
                            <span class="font-bold not-italic">{{ $narrative['name'] }}:</span>
                            {{ $narrative['statement'] }}
                        </p>
                    @endforeach
                </div>
            @else
                <p class="text-[11px] leading-tight text-justify italic">
                    No narrative statement available for this incident.
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-4 mb-6">
            <div>
                <h3 class="text-[10px] font-black mb-1 uppercase">I. Involved Parties</h3>
                <table class="w-full text-[10px] border-collapse border border-black text-left">
                    <tr class="bg-gray-doc border-b border-black font-bold">
                        <th class="p-1 border-r border-black uppercase">Name</th>
                        <th class="p-1 border-r border-black uppercase">License #</th>
                        <th class="p-1 border-r border-black uppercase">Role</th>
                        <th class="p-1 border-r border-black uppercase">Status</th>
                    </tr>
                    @forelse ($involvedParties as $party)
                        <tr class="border-b border-black">
                            <td class="p-1 border-r border-black uppercase">
                                {{ $party->full_name ?? 'N/A' }}
                                @if (!is_null($party->sex) || !is_null($party->age))
                                    ({{ strtoupper((string) ($party->sex ?? 'N/A')) }}/{{ $party->age ?? 'N/A' }})
                                @endif
                            </td>
                            <td class="p-1 border-r border-black uppercase">{{ $party->license_number ?? 'N/A' }}</td>
                            <td class="p-1 border-r border-black uppercase">{{ $party->role ?? 'N/A' }}</td>
                            <td class="p-1 uppercase">{{ $party->injury_severity ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr class="border-b border-black">
                            <td class="p-1 border-r border-black" colspan="4">No involved parties recorded.</td>
                        </tr>
                    @endforelse
                </table>
            </div>

            <div>
                <h3 class="text-[10px] font-black mb-1 uppercase">II. Vehicles Involved</h3>
                <table class="w-full text-[10px] border-collapse border border-black text-left">
                    <tr class="bg-gray-doc border-b border-black font-bold">
                        <th class="p-1 border-r border-black uppercase">Plate #</th>
                        <th class="p-1 border-r border-black uppercase">Model/Type</th>
                        <th class="p-1 border-r border-black uppercase">Color</th>
                    </tr>
                    @forelse ($vehicles as $vehicle)
                        <tr class="border-b border-black">
                            <td class="p-1 border-r border-black uppercase">{{ $vehicle->plate_number ?? 'N/A' }}</td>
                            <td class="p-1 border-r border-black uppercase">{{ $vehicle->vehicle_type ?? 'N/A' }}</td>
                            <td class="p-1 uppercase">{{ $vehicle->color ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr class="border-b border-black">
                            <td class="p-1 border-r border-black" colspan="3">No vehicles recorded.</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-[10px] font-black mb-1 uppercase">III. Evidence Files</h3>
            <div class="border border-black p-2 text-[10px]">
                Total image evidence for printing: {{ $printableEvidences->count() }}
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-10">
            <div class="text-center">
                <p class="font-bold underline uppercase text-[11px]">{{ $investigatorLabel }}</p>
                <p class="text-[9px] font-bold uppercase text-gray-500 tracking-tighter">Reporting Officer</p>
            </div>
            <div class="text-center">
                <div class="border-b border-black w-3/4 mx-auto mb-1"></div>
                <p class="text-[9px] font-bold uppercase text-gray-500 tracking-tighter">Duty Officer Signature</p>
            </div>
        </div>

        <div class="mt-8 pt-2 border-t border-gray-200 flex justify-between text-[8px] font-bold text-gray-400 italic">
            <p>ID: {{ $incident->report_number }}</p>
            <p>Printed: {{ now()->format('M d, Y h:i A') }}</p>
            <p>Official Record - Confidential</p>
        </div>
    </div>

    @foreach ($evidenceSheets as $sheetIndex => $sheet)
        <div class="evidence-sheet mt-4">
            <div class="flex items-center justify-between border-b border-black pb-2 mb-3">
                <h2 class="text-sm font-black uppercase">Evidence Attachments</h2>
                <p class="text-[10px] font-bold text-gray-600">Case #{{ $incident->report_number }} | Page
                    {{ $sheetIndex + 1 }}</p>
            </div>

            <div class="evidence-list">
                @foreach ($sheet as $evidence)
                    <div class="evidence-item">
                        <img src="{{ $evidence['url'] }}" alt="Evidence {{ $evidence['name'] }}"
                            class="evidence-preview">
                        <div class="evidence-meta">
                            <span><strong>FILE:</strong> {{ $evidence['name'] }}</span>
                            <span><strong>TYPE:</strong>
                                {{ strtoupper((string) ($evidence['type'] ?? 'IMAGE')) }}</span>
                            <span><strong>UPLOADED:</strong>
                                {{ $evidence['uploaded_at'] ? \Illuminate\Support\Carbon::parse($evidence['uploaded_at'])->format('M d, Y h:i A') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- AUTOMATIC PRINT SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.print(); // auto open print dialog
            // Optional: close the tab after printing
            window.onafterprint = function() {
                window.close();
            };
        });
    </script>

</body>

</html>
