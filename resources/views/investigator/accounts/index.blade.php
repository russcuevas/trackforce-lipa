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

        /* Your specific red */
        .text-tf-yellow {
            color: #FFD700;
        }

        /* Active State Class */
        .nav-active {
            background-color: #CE1126 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(11, 61, 145, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
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
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight">Account Management</h1>
                    <p class="text-sm text-gray-500">Manage investigator access.</p>
                </div>
                <button
                    class="bg-tf-blue hover:bg-blue-900 text-white px-6 py-2.5 rounded-lg font-bold flex items-center justify-center gap-2 transition-all shadow-md">
                    <i class="fa-solid fa-user-plus text-tf-yellow"></i>
                    ADD ACCOUNT
                </button>
            </div>

            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="w-full overflow-x-auto">
                        <table id="accountsTable" class="display w-full text-sm">
                            <thead class="bg-gray-50 text-tf-blue uppercase text-[11px] font-black">
                                <tr>
                                    <th class="py-4 px-4 text-left">Badge No.</th>
                                    <th class="py-4 px-4 text-left">Full Name</th>
                                    <th class="py-4 px-4 text-left">Status</th>
                                    <th class="py-4 px-4 text-left">Date Created</th>
                                    <th class="py-4 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 font-bold text-gray-400">#0421</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-8 w-8 rounded-full bg-blue-100 text-tf-blue flex items-center justify-center font-bold text-xs">
                                                VA</div>
                                            <span class="font-medium text-gray-700">Vincent Alpha</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-black uppercase">Active</span>
                                    </td>
                                    <td class="py-4 px-4 text-gray-500">Oct 12, 2025</td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button
                                                class="p-2 hover:bg-blue-50 text-blue-600 rounded-md transition-colors"><i
                                                    class="fa-solid fa-pen-to-square"></i></button>
                                            <button
                                                class="p-2 hover:bg-red-50 text-tf-red rounded-md transition-colors"><i
                                                    class="fa-solid fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-4 font-bold text-gray-400">#0552</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-8 w-8 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold text-xs">
                                                RC</div>
                                            <span class="font-medium text-gray-700">Russel Cuevas</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-black uppercase">Active</span>
                                    </td>
                                    <td class="py-4 px-4 text-gray-500">Jan 05, 2026</td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button
                                                class="p-2 hover:bg-blue-50 text-blue-600 rounded-md transition-colors"><i
                                                    class="fa-solid fa-pen-to-square"></i></button>
                                            <button
                                                class="p-2 hover:bg-red-50 text-tf-red rounded-md transition-colors"><i
                                                    class="fa-solid fa-trash-can"></i></button>
                                        </div>
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
            $('#accountsTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });
        });
    </script>
</body>

</html>
