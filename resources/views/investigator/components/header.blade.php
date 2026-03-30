<header class="bg-tf-blue h-16 flex items-center justify-between px-6 shadow-lg z-20">
    @php
        $investigator = \Illuminate\Support\Facades\Auth::guard('investigator')->user();
        $fullName = $investigator->full_name ?? 'Investigator';
        $badgeNumber = $investigator->badge_number ?? 'N/A';
        $profileImage =
            $investigator && $investigator->profile_image ? asset('storage/' . $investigator->profile_image) : null;
        $initials = \Illuminate\Support\Str::of($fullName)
            ->explode(' ')
            ->filter()
            ->map(fn($part) => \Illuminate\Support\Str::substr($part, 0, 1))
            ->take(2)
            ->implode('');
    @endphp

    <div class="flex items-center gap-4">
        <img src="{{ asset('images/logo.png') }}" alt="Trackforce Lipa Logo" class="h-10 w-auto object-contain">
        <h3 class="text-white font-bold tracking-wider">TRACKFORCE LIPA</h3>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative cursor-pointer">
            <i class="fa-solid fa-bell text-white text-xl"></i>
            <span
                class="absolute -top-2 -right-2 bg-tf-red text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">3</span>
        </div>
        <div id="profileMenuWrapper" class="relative border-l border-blue-800 pl-6">
            <button id="profileMenuButton" type="button"
                class="flex items-center gap-3 text-white hover:bg-white/10 rounded-xl px-2 py-1.5 transition-colors">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-medium">{{ $fullName }}</p>
                    <p class="text-[10px] opacity-75 uppercase">Badge #{{ $badgeNumber }}</p>
                </div>
                @if ($profileImage)
                    <img src="{{ $profileImage }}" alt="{{ $fullName }}"
                        class="h-10 w-10 rounded-full object-cover border border-white/40">
                @else
                    <div
                        class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-tf-blue font-bold">
                        {{ $initials ?: 'IV' }}
                    </div>
                @endif
                <i class="fa-solid fa-chevron-down text-xs text-blue-200"></i>
            </button>

            <div id="profileMenu"
                class="hidden absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-40">
                <a href="{{ route('investigator.profile.page') }}"
                    class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                    <i class="fa-solid fa-user text-tf-blue"></i>
                    <span>My Profile</span>
                </a>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    (function() {
        const wrapper = document.getElementById('profileMenuWrapper');
        const button = document.getElementById('profileMenuButton');
        const menu = document.getElementById('profileMenu');

        if (!wrapper || !button || !menu) {
            return;
        }

        button.addEventListener('click', function(event) {
            event.stopPropagation();
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            if (!wrapper.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    })();
</script>
