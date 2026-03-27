<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Case #2026-0045</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: portrait; margin: 10mm; }
            .no-print { display: none !important; }
            body { background-color: white !important; font-size: 11px; }
            .print-container { width: 100% !important; border: none !important; padding: 0 !important; }
        }
        body { font-family: 'Courier New', Courier, monospace; } /* Professional/Formal feel */
        .border-heavy { border: 2px solid #000; }
        .bg-gray-doc { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; }
    </style>
</head>
<body class="bg-gray-100 p-4">

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
                <p class="text-lg font-black italic">#2026-0045</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4 text-[11px]">
            <div class="space-y-1">
                <p><strong>INCIDENT TYPE:</strong> ROAD ACCIDENT</p>
                <p><strong>DATE/TIME:</strong> MAR 21, 2026 / 02:30 PM</p>
                <p><strong>LOCATION:</strong> SABANG-LIPA ROAD</p>
            </div>
            <div class="space-y-1 text-right">
                <p><strong>WEATHER:</strong> RAINING</p>
                <p><strong>ROAD COND:</strong> WET/SLIPPERY</p>
                <p><strong>INVESTIGATOR:</strong> CPL. RUSSEL CUEVAS</p>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="bg-gray-doc px-2 py-1 text-[10px] font-black border-t border-b border-black mb-1 uppercase">Narrative Report</h3>
            <p class="text-[11px] leading-tight text-justify italic">
                A two-vehicle collision involving a motorcycle and a private SUV. 
                Road was wet due to sudden rain. No major injuries reported on site, 
                significant property damage.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-6">
            <div>
                <h3 class="text-[10px] font-black mb-1 uppercase">I. Involved Parties</h3>
                <table class="w-full text-[10px] border-collapse border border-black text-left">
                    <tr class="bg-gray-doc border-b border-black font-bold">
                        <th class="p-1 border-r border-black uppercase">Name</th>
                        <th class="p-1 border-r border-black uppercase">Role</th>
                        <th class="p-1 border-r border-black uppercase">Status</th>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="p-1 border-r border-black">JUAN DELA CRUZ (M/28)</td>
                        <td class="p-1 border-r border-black">DRIVER</td>
                        <td class="p-1">UNHARMED</td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="p-1 border-r border-black">MARIA CLARA (F/24)</td>
                        <td class="p-1 border-r border-black">PASSENGER</td>
                        <td class="p-1">MINOR INJURY</td>
                    </tr>
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
                    <tr class="border-b border-black">
                        <td class="p-1 border-r border-black">NCO-1234</td>
                        <td class="p-1 border-r border-black">TOYOTA FORTUNER (SUV)</td>
                        <td class="p-1 uppercase">GRAY</td>
                    </tr>
                    <tr class="border-b border-black">
                        <td class="p-1 border-r border-black">123-ABC</td>
                        <td class="p-1 border-r border-black">HONDA CLICK (MC)</td>
                        <td class="p-1 uppercase">RED/BLACK</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-10">
            <div class="text-center">
                <p class="font-bold underline uppercase text-[11px]">CPL. RUSSEL CUEVAS</p>
                <p class="text-[9px] font-bold uppercase text-gray-500 tracking-tighter">Reporting Officer</p>
            </div>
            <div class="text-center">
                <div class="border-b border-black w-3/4 mx-auto mb-1"></div>
                <p class="text-[9px] font-bold uppercase text-gray-500 tracking-tighter">Duty Officer Signature</p>
            </div>
        </div>

        <div class="mt-8 pt-2 border-t border-gray-200 flex justify-between text-[8px] font-bold text-gray-400 italic">
            <p>ID: TF-LIPA-0045-8821</p>
            <p>Printed: {{ now()->format('M d, Y h:i A') }}</p>
            <p>Official Record - Confidential</p>
        </div>
    </div>

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