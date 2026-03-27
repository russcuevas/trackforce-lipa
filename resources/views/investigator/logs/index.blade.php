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
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
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
                                    <th class="py-4 px-4 text-left">Incident Ref</th>
                                    <th class="py-4 px-4 text-left">Investigator</th>
                                    <th class="py-4 px-4 text-left">Action Performed</th>
                                    <th class="py-4 px-4 text-left">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 text-gray-400 font-mono">#LOG-8821</td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="bg-blue-50 text-tf-blue px-2 py-1 rounded font-bold text-xs border border-blue-100">
                                            INC-2026-0045
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="h-6 w-6 rounded-full bg-tf-blue text-white flex items-center justify-center text-[10px] font-bold">
                                                IA</div>
                                            <span class="font-medium text-gray-700">Investigator Alpha</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-circle-check text-green-500 text-[10px]"></i>
                                            <span class="text-gray-600">Updated status to <strong
                                                    class="text-green-700 uppercase text-[11px]">Solved</strong></span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-gray-500 text-xs">
                                        March 26, 2026 | 10:45 AM
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 text-gray-400 font-mono">#LOG-8819</td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="bg-gray-50 text-gray-500 px-2 py-1 rounded font-bold text-xs border border-gray-200">
                                            INC-2026-0032
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="h-6 w-6 rounded-full bg-yellow-500 text-white flex items-center justify-center text-[10px] font-bold">
                                                RC</div>
                                            <span class="font-medium text-gray-700">Russel Cuevas</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-pen text-tf-red text-[10px]"></i>
                                            <span class="text-gray-600">Modified Narrative Description</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-gray-500 text-xs">
                                        March 25, 2026 | 02:15 PM
                                    </td>
                                </tr>
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
                    searchPlaceholder: "Search"
                }
            });
        });
    </script>
</body>

</html>
