<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initial Investigation Report #{{ $incident->report_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .print-container {
                width: 100% !important;
                height: 11in !important;
                /* Force exact paper height */
                border: none !important;
                padding: 0.5in !important;
                box-shadow: none !important;
                margin: 0 !important;
                position: relative !important;
                display: block !important;
                /* Switch from flex to block for absolute children */
            }

            .signature-section {
                position: absolute !important;
                bottom: 0.8in !important;
                left: 0.5in !important;
                right: 0.5in !important;
            }

            .footer-info {
                position: absolute !important;
                bottom: 0.4in !important;
                left: 0.5in !important;
                right: 0.5in !important;
            }

            textarea {
                border: none !important;
                resize: none !important;
                padding: 0 !important;
                overflow: hidden !important;
                min-height: auto !important;
            }
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f3f4f6;
        }

        .print-container {
            width: 8.5in;
            height: 11in;
            margin: 10px auto;
            background: white;
            padding: 0.5in;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .underline-heavy {
            border-bottom: 2px solid black;
        }

        textarea {
            width: 100%;
            border: 1px solid #ddd;
            padding: 4px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.3;
        }

        .signature-block {
            text-align: center;
            width: 180px;
        }

        .signature-line {
            border-bottom: 1px solid black;
            margin-bottom: 2px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
    </style>
</head>

<body class="p-4">

    <div class="max-w-4xl mx-auto mb-2 no-print flex justify-end gap-2">
        <form action="{{ route('investigator.documentation.save.narrative', $incident->id) }}" method="POST"
            id="saveForm">
            @csrf
            <input type="hidden" name="involved_vehicles_narrative" id="hidden_involved">
            <input type="hidden" name="narration_of_accidents" id="hidden_narration">
            <button type="button" onclick="submitForm()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-xs font-bold rounded shadow transition">
                SAVE CHANGES
            </button>
        </form>
        <button onclick="window.print()"
            class="bg-black hover:bg-gray-800 text-white px-4 py-2 text-xs font-bold rounded shadow transition">
            PRINT REPORT
        </button>
    </div>

    @if (session('success'))
        <div class="max-w-4xl mx-auto mb-2 no-print bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative"
            role="alert">
            <span class="block sm:inline text-xs">{{ session('success') }}</span>
        </div>
    @endif

    <div class="print-container">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <div class="w-16">
                <img src="{{ asset('images/logo.png') }}" alt="PNP Logo" class="w-full">
            </div>
            <div class="text-center flex-1">
                <p class="text-[10px]">Republic of the Philippines</p>
                <p class="text-[10px] font-bold">NATIONAL POLICE COMMISSION</p>
                <p class="text-[10px] font-bold leading-tight">PHILIPPINE NATIONAL POLICE, POLICE REGIONAL OFFICE
                    4A<br>BATANGAS POLICE PROVINCIAL OFFICE</p>
                <p class="text-[10px] font-bold underline">LIPA COMPONENT CITY POLICE STATION</p>
                <p class="text-[9px]">B Morada Ave., Brgy. 1, Lipa City</p>
            </div>
            <div class="w-16 flex justify-end">
                <div
                    class="w-12 h-12 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center text-[7px] text-gray-400">
                    STATION SEAL
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <h1 class="text-md font-bold underline decoration-2 underline-offset-4">INITIAL INVESTIGATION REPORT</h1>
        </div>

        <div class="space-y-2 text-[12px] mb-4">
            <div class="flex items-start">
                <span class="font-bold w-16">WHAT</span>
                <span class="mr-2">:</span>
                <div class="flex-1 border-b border-black">
                    {{ strtoupper($incident->incident_type ?? 'N/A') }}
                </div>
            </div>
            <div class="flex items-start">
                <span class="font-bold w-16">WHEN</span>
                <span class="mr-2">:</span>
                <div class="flex-1 border-b border-black text-[11px]">
                    @php
                        $incidentDateTime =
                            $incident->time_completed ?? ($incident->time_documented ?? $incident->time_reported);
                    @endphp
                    On or about
                    {{ $incidentDateTime ? \Illuminate\Support\Carbon::parse($incidentDateTime)->format('h:i A \o\f F d, Y') : 'N/A' }}
                </div>
            </div>
            <div class="flex items-start">
                <span class="font-bold w-16">WHERE</span>
                <span class="mr-2">:</span>
                <div class="flex-1 border-b border-black">
                    At {{ strtoupper($incident->location_name ?? 'N/A') }}
                </div>
            </div>
        </div>

        <div class="mb-3">
            <h2 class="font-bold text-[12px] mb-1 uppercase">INVOLVED VEHICLES:</h2>
            <textarea id="involved_vehicles_narrative" rows="3"
                class="w-full text-[12px] border-none focus:ring-0 leading-relaxed"
                placeholder="Enter involved vehicles details...">{{ $incident->involved_vehicles_narrative ?? '' }}</textarea>
        </div>

        <div class="mb-3 flex items-center text-[12px]">
            <span class="font-bold uppercase mr-2">WEATHER CONDITION:</span>
            <div class="min-w-[80px]">
                {{ strtoupper($incident->weather_condition ?? 'FAIR') }}
            </div>
        </div>

        <div class="mb-3">
            <h2 class="font-bold text-[12px] mb-1 uppercase">NARRATION OF THE ACCIDENT:</h2>
            <textarea id="narration_of_accidents" rows="8"
                class="w-full text-[11px] border-none focus:ring-0 text-justify leading-relaxed"
                placeholder="Enter narration of the accident...">{{ $incident->narration_of_accidents ?? '' }}</textarea>
        </div>

        <div class="mb-3 flex items-center text-[12px]">
            <span class="font-bold uppercase mr-2">PURPOSE:</span>
            <div class="flex-1">
                For Any Legal purposes.
            </div>
        </div>

        <div class="signature-section">
            <div class="flex items-center gap-2 mb-3">
                <p class="text-[12px]" style="font-weight: 900;">Prepared by:</p>
                <button type="button" onclick="addInvestigator()"
                    class="no-print bg-green-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs hover:bg-green-700 shadow-sm transition">
                    +
                </button>
            </div>

            <div id="investigators-container" class="flex flex-wrap justify-center items-start gap-x-12 gap-y-8">
                <div class="signature-block relative group">
                    <button type="button" onclick="this.parentElement.remove()"
                        class="no-print absolute -top-2 -right-2 bg-red-500 text-white w-4 h-4 rounded-full hidden group-hover:flex items-center justify-center text-[8px]">
                        X
                    </button>
                    <div class="h-8 flex items-end justify-center">
                        <!-- Space for signature -->
                    </div>
                    <div class="signature-line" contenteditable="true" spellcheck="false">
                        {{ $incident->investigator_name ?? 'INVESTIGATOR NAME' }}
                    </div>
                    <p class="text-[9px] font-bold">Traffic Investigator</p>
                </div>
            </div>
        </div>

        <div
            class="footer-info pt-4 border-t border-gray-100 flex justify-between text-[8px] text-gray-400 italic no-print">
            <p>Report ID: {{ $incident->report_number }}</p>
            <p>Printed: {{ now()->format('M d, Y h:i A') }}</p>
            <p>Official Record - Lipa City Police Station</p>
        </div>
    </div>

    <script>
        function submitForm() {
            document.getElementById('hidden_involved').value = document.getElementById('involved_vehicles_narrative').value;
            document.getElementById('hidden_narration').value = document.getElementById('narration_of_accidents').value;
            document.getElementById('saveForm').submit();
        }

        function addInvestigator() {
            const container = document.getElementById('investigators-container');
            const newBlock = document.createElement('div');
            newBlock.className = 'signature-block relative group';
            newBlock.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="no-print absolute -top-2 -right-2 bg-red-500 text-white w-4 h-4 rounded-full hidden group-hover:flex items-center justify-center text-[8px]">
                    <i class="fa-solid fa-x"></i>
                </button>
                <div class="h-8 flex items-end justify-center"></div>
                <div class="signature-line" contenteditable="true" spellcheck="false">INVESTIGATOR NAME</div>
                <p class="text-[9px] font-bold">Traffic Investigator</p>
            `;

            // Insert in the middle if there are already investigators
            if (container.children.length >= 1) {
                container.insertBefore(newBlock, container.children[1]);
            } else {
                container.appendChild(newBlock);
            }
        }

        // Auto-resize textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            // Initial resize
            textarea.style.height = (textarea.scrollHeight) + 'px';
        });
    </script>
</body>

</html>
