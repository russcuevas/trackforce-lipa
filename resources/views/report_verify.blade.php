<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Report OTP | TrackForce Lipa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8fafc;
        }

        .bg-tf-blue {
            background-color: #0B3D91;
        }

        .bg-tf-red {
            background-color: #CE1126;
        }

        .text-tf-blue {
            color: #0B3D91;
        }
    </style>
</head>

<body>
    <header class="bg-tf-blue py-10 px-6">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-white text-3xl md:text-4xl font-black">Verify Your Report</h1>
            <p class="text-blue-200 mt-2 text-sm md:text-base">Enter the OTP sent to your email to confirm your report.
            </p>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-6 -mt-8 pb-14">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-7 md:p-8">
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 text-green-700 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('report.verify.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="report_number" class="block text-xs font-bold text-gray-500 uppercase mb-2">Report
                        Number</label>
                    <input id="report_number" name="report_number" type="text"
                        value="{{ old('report_number', $reportNumber) }}" required readonly
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none">
                </div>

                <div>
                    <label for="reporter_email" class="block text-xs font-bold text-gray-500 uppercase mb-2">Reporter
                        Email</label>
                    <input id="reporter_email" name="reporter_email" type="email" value="{{ old('reporter_email') }}"
                        required readonly required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none"
                        placeholder="youremail@example.com">
                </div>

                <div>
                    <label for="otp" class="block text-xs font-bold text-gray-500 uppercase mb-2">One-Time Password
                        (OTP)</label>
                    <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" required
                        value="{{ old('otp') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm tracking-[0.3em] font-bold outline-none"
                        placeholder="123456">
                </div>

                <button type="submit"
                    class="w-full bg-tf-red text-white py-3 rounded-xl font-black hover:bg-red-700 transition-colors">
                    VERIFY OTP
                </button>
            </form>

            <p class="text-xs text-gray-500 mt-5">
                Did not receive OTP? Submit your report again or contact support.
            </p>

            <a href="{{ route('report.page') }}" class="inline-block text-sm font-bold text-tf-blue mt-4">Back to Report
                Form</a>
        </div>
    </main>
</body>

</html>
