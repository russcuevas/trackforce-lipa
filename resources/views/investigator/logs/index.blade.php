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
        table.dataTable tbody tr {
            background-color: transparent !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            outline: none;
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

            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="w-full overflow-x-auto">

                        <table id="logsTable" class="display w-full text-sm">
                            <thead class="bg-gray-50 text-tf-blue uppercase text-[11px] font-black">
                                <tr>
                                    <th class="py-4 px-4 text-left">Log ID</th>
                                    <th class="py-4 px-4 text-left">Ref</th>
                                    <th class="py-4 px-4 text-left">Investigator</th>
                                    <th class="py-4 px-4 text-left">Action Performed</th>
                                    <th class="py-4 px-4 text-left">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($logs as $log)
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
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-4 text-gray-400 font-mono">#LOG-{{ $log->id }}</td>
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
                                                    class="h-6 w-6 rounded-full bg-tf-blue text-white flex items-center justify-center text-[10px] font-bold">
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
                                        <td class="py-4 px-4 text-gray-500 text-xs">
                                            {{ $log->created_at ? $log->created_at->format('F d, Y | h:i A') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 px-4 text-center text-sm text-gray-400">No audit
                                            logs recorded yet.</td>
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
