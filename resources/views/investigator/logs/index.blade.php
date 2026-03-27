<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce - Lipa</title>
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

    <header class="bg-tf-blue h-16 flex items-center justify-between px-6 shadow-lg z-20">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo.png') }}" alt="Trackforce Lipa Logo" class="h-10 w-auto object-contain">
            <h3 class="text-white font-bold tracking-wider">TRACKFORCE <br> LIPA - PNP</h3>
        </div>
        <div class="flex items-center gap-6">
            <div class="relative cursor-pointer">
                <i class="fa-solid fa-bell text-white text-xl"></i>
                <span
                    class="absolute -top-2 -right-2 bg-tf-red text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">3</span>
            </div>
            <div class="flex items-center gap-3 border-l border-blue-800 pl-6 text-white">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-medium">Investigator Alpha</p>
                    <p class="text-[10px] opacity-75 uppercase">Badge #0421</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-tf-blue font-bold">IA
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <aside class="bg-tf-blue w-20 lg:w-64 flex flex-col transition-all duration-300">
            <nav class="flex-1 px-3 py-6 space-y-2">
                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-chart-line group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Dashboard</span>
                </a>
                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-users group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block font-medium">Accounts</span>
                </a>
                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-file-signature group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Documentations</span>
                </a>

                <a href="#"
                    class="flex items-center gap-4 p-3 text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition-all group">
                    <i class="fa-solid fa-list-check group-hover:text-tf-yellow"></i>
                    <span class="hidden lg:block">Incident Reports</span>
                </a>
                <a href="#" class="flex items-center gap-4 p-3 rounded-lg nav-active group">
                    <i class="fa-solid fa-history text-tf-yellow group-hover:scale-110 transition-transform"></i>
                    <span class="hidden lg:block font-medium">Audit Trail Logs</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <button
                    class="w-full bg-tf-red text-white py-2 rounded font-bold text-sm uppercase flex items-center justify-center gap-2">
                    <i class="fa-solid fa-power-off"></i>
                    <span class="hidden lg:block">Logout</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight">Audit Trail Logs</h1>
                    <p class="text-sm text-gray-500">System-wide activity and incident modification history.</p>
                </div>
            </div>

            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
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
