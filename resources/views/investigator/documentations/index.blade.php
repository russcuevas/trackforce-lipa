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
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight">Documentations</h1>
                    <p class="text-sm text-gray-500">Access and view monthly generated reports.</p>
                </div>
            </div>

            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="w-full overflow-x-auto">

                        <table id="docsTable" class="display w-full text-sm">
                            <thead class="bg-gray-50 text-tf-blue uppercase text-[11px] font-black">
                                <tr>
                                    <th class="py-4 px-4 text-left">Month/Year</th>
                                    <th class="py-4 px-4 text-left">No. of Reports</th>
                                    <th class="py-4 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($monthlyIncidentReports as $group)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-4">
                                            <i class="fa-solid fa-folder text-tf-yellow text-2xl"></i>
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
