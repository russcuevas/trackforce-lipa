<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce Lipa</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8fafc;
            color: #1a1a1a;
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

        .profile-card {
            background: linear-gradient(120deg, #0b3d91 0%, #144aa8 100%);
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @php
        $profileImage = null;
        if ($investigator->profile_image) {
            $profilePath = ltrim((string) $investigator->profile_image, '/');
            if (\Illuminate\Support\Str::startsWith($profilePath, ['http://', 'https://'])) {
                $profileImage = $profilePath;
            } elseif (file_exists(public_path($profilePath))) {
                $profileImage = asset($profilePath);
            } else {
                $profileImage = asset('storage/' . $profilePath);
            }
        }
        $profileInitials = \Illuminate\Support\Str::of($investigator->full_name)
            ->explode(' ')
            ->filter()
            ->map(fn($part) => \Illuminate\Support\Str::substr($part, 0, 1))
            ->take(2)
            ->implode('');
    @endphp

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">
        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6">
            <section class="profile-card rounded-2xl p-6 text-white shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.2em] text-blue-100">My Profile</p>
                        <h1 class="text-2xl md:text-3xl font-black mt-1">{{ $investigator->full_name }}</h1>
                        <p class="text-sm text-blue-100 mt-1">Badge #{{ $investigator->badge_number }}</p>
                    </div>
                    @if ($profileImage)
                        <img src="{{ $profileImage }}" alt="{{ $investigator->full_name }}"
                            class="h-16 w-16 rounded-full object-cover border-2 border-white/70 shadow-md">
                    @else
                        <div
                            class="h-16 w-16 rounded-full bg-white text-tf-blue flex items-center justify-center text-2xl font-black">
                            {{ $profileInitials ?: 'IV' }}
                        </div>
                    @endif
                </div>
            </section>

            <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h2 class="text-base font-black text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-tf-blue"></i>
                        Update Profile Details
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 mb-5">Keep your account information current.</p>

                    <form method="POST" action="{{ route('investigator.profile.email.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Full
                                Name</label>
                            <input type="text" name="full_name"
                                value="{{ old('full_name', $investigator->full_name) }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 @error('full_name') border-red-400 @enderror"
                                required>
                            @error('full_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Email
                                Address</label>
                            <input type="email" name="email" value="{{ old('email', $investigator->email) }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 @error('email') border-red-400 @enderror"
                                required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="bg-tf-blue hover:opacity-90 transition-opacity text-white px-4 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wide">
                            Save Profile
                        </button>
                    </form>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                    <h2 class="text-base font-black text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-key text-tf-red"></i>
                        Change Password
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 mb-5">Use a strong password with at least 8 characters.</p>

                    <form method="POST" action="{{ route('investigator.profile.password.update') }}"
                        class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Current
                                Password</label>
                            <input type="password" name="current_password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 @error('current_password') border-red-400 @enderror"
                                required>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">New
                                Password</label>
                            <input type="password" name="new_password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 @error('new_password') border-red-400 @enderror"
                                required>
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Confirm
                                New Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100"
                                required>
                        </div>

                        <button type="submit"
                            class="bg-tf-red hover:opacity-90 transition-opacity text-white px-4 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wide">
                            Update Password
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 3500,
            position: {
                x: 'right',
                y: 'top'
            },
            dismissible: true
        });

        @if (session('success'))
            notyf.success(@json(session('success')));
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                notyf.error(@json($error));
            @endforeach
        @endif
    </script>
</body>

</html>
