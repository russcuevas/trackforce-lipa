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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

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
                    <p class="text-sm text-gray-500">
                        {{ \Illuminate\Support\Carbon::createFromDate((int) $selectedYear, (int) $selectedMonth, 1)->format('F Y') }}
                    </p>
                </div>
                <a href="{{ route('investigator.documentation.page') }}"
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-gray-100 transition-colors flex items-center gap-2 w-fit">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to Documentations</span>
                </a>
            </div>

            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="w-full overflow-x-auto">
                        <table id="resolvedReportsTable" class="display w-full text-sm">
                            <thead class="bg-gray-50 text-tf-blue uppercase text-[11px] font-black">
                                <tr>
                                    <th class="py-4 px-4 text-left">Report No.</th>
                                    <th class="py-4 px-4 text-left">Incident Type</th>
                                    <th class="py-4 px-4 text-left">Location</th>
                                    <th class="py-4 px-4 text-left">Status</th>
                                    <th class="py-4 px-4 text-left">Reported At</th>
                                    <th class="py-4 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($incidents as $incident)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-4 font-bold text-gray-500">#{{ $incident->report_number }}
                                        </td>
                                        <td class="py-4 px-4 font-medium text-gray-700">{{ $incident->incident_type }}
                                        </td>
                                        <td class="py-4 px-4 text-gray-600">{{ $incident->location_name }}</td>
                                        <td class="py-4 px-4">
                                            @php
                                                $statusValue = trim((string) ($incident->status ?? 'Resolved'));
                                                $statusKey = strtolower(str_replace(['_', '-'], ' ', $statusValue));

                                                $statusClass = match ($statusKey) {
                                                    'pending' => 'bg-red-100 text-red-700',
                                                    'under investigation' => 'bg-yellow-100 text-yellow-700',
                                                    'resolved' => 'bg-green-100 text-green-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span
                                                class="{{ $statusClass }} px-2 py-1 rounded text-[10px] font-black uppercase">
                                                {{ $statusValue }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-gray-500">
                                            {{ \Illuminate\Support\Carbon::parse($incident->created_at ?? $incident->time_completed)->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <a href="{{ route('investigator.documentation.print.report.page', ['incident' => (int) $incident->id]) }}"
                                                target="_blank"
                                                class="bg-tf-blue hover:bg-blue-900 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-colors inline-flex items-center gap-2">
                                                <i class="fa-solid fa-print"></i>
                                                <span>Print Details</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 px-4 text-center text-sm text-gray-400">
                                            No incident reports found for this period.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resolvedReportsTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search incident reports...",
                    paginate: {
                        previous: '<',
                        next: '>'
                    }
                }
            });
        });
    </script>
</body>

</html>
