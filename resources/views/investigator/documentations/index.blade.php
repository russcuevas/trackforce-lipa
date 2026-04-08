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

        /* ── DataTable custom overrides ── */
        table.dataTable thead th,
        table.dataTable thead td {
            border-bottom: 2px solid #e5e7eb !important;
        }

        table.dataTable tbody tr {
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

        #docsTable thead th,
        #docsTable thead td {
            background-color: #0B3D91 !important;
            color: #ffffff !important;
        }

        #docsTable thead th.sorting,
        #docsTable thead th.sorting_asc,
        #docsTable thead th.sorting_desc {
            background-color: #0B3D91 !important;
            color: #ffffff !important;
        }

        #docsTable thead th:hover {
            background-color: #0a3480 !important;
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
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight">Documentations</h1>
                    <p class="text-sm text-gray-500">Access and view monthly generated reports.</p>
                </div>
            </div>

            <section class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <!-- Table header bar -->
                <div
                    class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#0B3D91]/5 to-white">
                    <div class="flex items-center gap-2">
                        <span class="h-5 w-1 rounded-full bg-[#0B3D91] inline-block"></span>
                        <span class="text-xs font-bold text-[#0B3D91] uppercase tracking-widest">Documentation
                            Records</span>
                    </div>
                    <span class="text-xs text-gray-400 italic">Monthly grouped incident reports</span>
                </div>
                <div class="p-6">
                    <div class="w-full overflow-x-auto">

                        <table id="docsTable" class="display w-full text-sm">
                            <thead>
                                <tr class="bg-[#0B3D91] text-white uppercase text-[11px] font-black">
                                    <th class="py-4 px-4 text-left rounded-tl-lg">Month/Year</th>
                                    <th class="py-4 px-4 text-left">No. of Reports</th>
                                    <th class="py-4 px-4 text-center rounded-tr-lg">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($monthlyIncidentReports as $group)
                                    <tr
                                        class="group border-l-4 border-l-transparent hover:border-l-[#0B3D91] hover:bg-blue-50/40 transition-all duration-150">
                                        <td class="py-4 px-4">
                                            <i
                                                class="fa-solid fa-folder text-tf-yellow text-2xl group-hover:scale-110 transition-transform duration-200"></i>
                                            &nbsp;
                                            <span class="font-bold text-gray-700">
                                                {{ \Illuminate\Support\Carbon::createFromDate((int) $group->year_value, (int) $group->month_value, 1)->format('F Y') }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span
                                                class="bg-blue-100 text-tf-blue px-3 py-1 rounded-full text-xs font-bold">
                                                {{ (int) $group->reports_count }}
                                                Report{{ (int) $group->reports_count === 1 ? '' : 's' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex justify-center">
                                                <a href="{{ route('investigator.documentation.reports.page', ['year' => (int) $group->year_value, 'month' => (int) $group->month_value]) }}"
                                                    class="bg-tf-blue hover:bg-blue-900 text-white px-5 py-2 rounded-xl text-[10px] font-black transition-all shadow-md hover:shadow-blue-900/20 flex items-center gap-2 w-fit active:scale-95">
                                                    <i class="fa-solid fa-eye"></i>
                                                    <span class="uppercase tracking-wider">View Reports</span>
                                                </a>
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
            $('#docsTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
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
