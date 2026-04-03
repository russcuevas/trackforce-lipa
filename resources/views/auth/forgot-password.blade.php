<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce - Forgot Password</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e293b, #0f172a, #020617);
            overflow: hidden;
        }

        .glow-border {
            position: relative;
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
        }

        .input-dark {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .input-dark:focus {
            border-color: #FFD700;
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.1);
        }

        .ambient-light {
            position: absolute;
            width: 300px;
            height: 300px;
            background: #0B3D91;
            filter: blur(100px);
            opacity: 0.15;
            z-index: 0;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <div class="ambient-light top-0 left-0"></div>
    <div class="ambient-light bottom-0 right-0"></div>

    <main class="relative z-10 w-full max-w-[420px]">
        <div class="glow-border rounded-2xl p-6 md:p-8 shadow-2xl">
            <div class="flex items-center justify-center mb-8">
                <div class="flex items-center gap-3 text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="PNP Logo"
                        class="h-10 w-auto drop-shadow-[0_0_5px_rgba(255,255,255,0.2)]">

                    <div>
                        <h1 class="text-white text-lg font-extrabold tracking-tighter uppercase leading-none">
                            TrackForce
                        </h1>
                        <p class="text-[#FFD700] text-[8px] font-bold tracking-[0.2em] uppercase mt-1 opacity-80">
                            Lipa
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-white text-xl font-semibold">Forgot Password</h2>
                <p class="text-slate-400 text-xs mt-1 font-light">
                    Enter the investigator email address and we will send a password reset link.
                </p>
            </div>

            <form id="forgotPasswordForm" action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-widest ml-1">Email</label>
                    <div class="relative group">
                        <i
                            class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs group-focus-within:text-[#FFD700] transition-colors"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com"
                            class="w-full pl-10 pr-4 py-3 rounded-xl outline-none input-dark text-xs @error('email') border-red-500 @enderror"
                            required>
                    </div>
                    @error('email')
                        <p class="text-red-400 text-[10px] ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <button id="submitBtn" type="submit"
                    class="w-full bg-[#0B3D91] hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all duration-300 shadow-lg flex items-center justify-center gap-2 active:scale-[0.98] text-xs mt-2 uppercase tracking-widest disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="btnText">Send Reset Link</span>
                    <i id="btnIcon" class="fa-solid fa-paper-plane text-[#FFD700] text-[10px]"></i>
                    <svg id="btnSpinner" class="hidden animate-spin h-3.5 w-3.5 text-[#FFD700]"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                </button>
            </form>

            <div class="mt-5 flex justify-center">
                <a href="{{ route('auth.login.page') }}"
                    class="text-[10px] font-bold text-[#FFD700] hover:text-white transition-colors uppercase tracking-widest">
                    Back to Login
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top'
            },
            dismissible: true,
            types: [{
                    type: 'success',
                    background: '#198754',
                    icon: {
                        className: 'fa-solid fa-circle-check',
                        tagName: 'i',
                        color: 'white'
                    }
                },
                {
                    type: 'error',
                    background: '#dc3545',
                    icon: {
                        className: 'fa-solid fa-triangle-exclamation',
                        tagName: 'i',
                        color: 'white'
                    }
                }
            ]
        });

        @if (session('success'))
            notyf.open({
                type: 'success',
                message: @json(session('success'))
            });
        @endif

        @if (session('error'))
            notyf.open({
                type: 'error',
                message: @json(session('error'))
            });
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                notyf.open({
                    type: 'error',
                    message: @json($error)
                });
            @endforeach
        @endif
    </script>

    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const btnSpinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            btnText.textContent = 'Sending...';
            btnIcon.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
        });
    </script>
</body>

</html>
