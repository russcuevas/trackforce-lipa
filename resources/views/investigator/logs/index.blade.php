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

        /* Custom Table Styling for Logs */
        table.dataTable thead th,
        table.dataTable thead td {
            border-bottom: 2px solid #e5e7eb !important;
        }

        table.dataTable tbody tr {
            background-color: transparent !important;
            border-bottom: 1px solid #f3f4f6 !important;
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #0B3D91;
            box-shadow: 0 0 0 3px rgba(11, 61, 145, 0.12);
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.3rem 0.5rem;
            font-size: 0.85rem;
            outline: none;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            min-width: 2rem;
            text-align: center;
            border-radius: 0.5rem !important;
            font-size: 0.8rem !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0B3D91 !important;
            color: #fff !important;
            border: 1px solid #0B3D91 !important;
            border-radius: 0.5rem !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e8edf7 !important;
            color: #0B3D91 !important;
            border: 1px solid #e8edf7 !important;
        }

        #logsTable thead th,
        #logsTable thead td {
            background-color: #0B3D91 !important;
            color: #ffffff !important;
        }

        #logsTable thead th.sorting,
        #logsTable thead th.sorting_asc,
        #logsTable thead th.sorting_desc {
            background-color: #0B3D91 !important;
            color: #ffffff !important;
        }

        #logsTable thead th:hover {
            background-color: #0a3480 !important;
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            @php
                $actionIcons = [
                    'incident_status' => 'fa-circle-check text-green-500',
                    'incident_update' => 'fa-pen text-yellow-500',
                    'incident_create' => 'fa-file-circle-plus text-tf-blue',
                    'account_create' => 'fa-user-plus text-tf-blue',
                    'account_update' => 'fa-user-pen text-yellow-500',
                    'account_delete' => 'fa-user-xmark text-red-500',
                    'profile_update' => 'fa-id-card text-tf-blue',
                    'password_change' => 'fa-key text-red-500',
                    'public_report' => 'fa-triangle-exclamation text-yellow-500',
                    'public_report_verify' => 'fa-badge-check text-green-500',
                    'auth_login' => 'fa-right-to-bracket text-green-500',
                    'auth_logout' => 'fa-right-from-bracket text-gray-500',
                ];
            @endphp
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight">Audit Trail Logs</h1>
                    <p class="text-sm text-gray-500">System-wide activity and incident modification history.</p>
                </div>
            </div>

            <section class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <!-- Table header bar -->
                <div
                    class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0B3D91]/5 to-white">
                    <div class="flex items-center gap-2">
                        <span class="h-5 w-1 rounded-full bg-[#0B3D91] inline-block"></span>
                        <span class="text-xs font-bold text-[#0B3D91] uppercase tracking-widest">Audit Trail
                            Records</span>
                    </div>
                    <span class="text-xs text-gray-400 italic">System-wide activity log</span>
                </div>
                <div class="p-6">
                    <div class="w-full overflow-x-auto">

                        <table id="logsTable" class="display w-full text-sm">
                            <thead>
                                <tr class="bg-[#0B3D91] text-white uppercase text-[11px] font-black">
                                    <th class="py-4 px-4 text-left rounded-tl-lg">Log ID</th>
                                    <th class="py-4 px-4 text-left">Ref</th>
                                    <th class="py-4 px-4 text-left">Investigator</th>
                                    <th class="py-4 px-4 text-left">Action Performed</th>
                                    <th class="py-4 px-4 text-left rounded-tr-lg">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($logs as $log)
                                    @php
                                        $incidentReference = $log->incident?->report_number;
                                        $investigatorName = $log->investigator?->full_name;
                                        $initials = collect(explode(' ', (string) $investigatorName))
                                            ->filter()
                                            ->take(2)
                                            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                            ->implode('');
                                        $iconClass =
                                            $actionIcons[$log->action_type] ?? 'fa-clock-rotate-left text-gray-500';
                                    @endphp
                                    <tr
                                        class="group border-l-4 border-l-transparent hover:border-l-[#0B3D91] hover:bg-blue-50/40 transition-all duration-150">
                                        <td class="py-4 px-4">
                                            <span
                                                class="inline-flex items-center gap-1 font-mono text-xs font-bold text-[#0B3D91] bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full tracking-wide">
                                                <i class="fa-solid fa-list-check text-[10px] opacity-60"></i>
                                                #LOG-{{ $log->id }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if ($incidentReference)
                                                <span
                                                    class="bg-blue-50 text-tf-blue px-2 py-1 rounded font-bold text-xs border border-blue-100">
                                                    {{ $incidentReference }}
                                                </span>
                                            @else
                                                <span
                                                    class="bg-gray-50 text-gray-500 px-2 py-1 rounded font-bold text-xs border border-gray-200">
                                                    SYSTEM
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-gradient-to-br from-[#0B3D91] to-blue-400 text-white flex items-center justify-center text-[10px] font-bold shadow-sm shrink-0">
                                                    {{ $initials !== '' ? $initials : 'SY' }}</div>
                                                <span
                                                    class="font-medium text-gray-700">{{ $investigatorName ?: 'System / Public User' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid {{ $iconClass }} text-[10px]"></i>
                                                <span class="text-gray-600">{{ $log->action_performed }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-1.5 text-gray-500 text-xs">
                                                <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                                                {{ $log->created_at ? $log->created_at->format('F d, Y | h:i A') : 'N/A' }}
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
    <script>
        $(document).ready(function() {
            $('#logsTable').DataTable({
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
        });
    </script>
</body>

</html>
