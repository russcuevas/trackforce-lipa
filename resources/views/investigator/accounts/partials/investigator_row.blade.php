@php
    $initials = collect(explode(' ', trim($investigator->full_name)))
        ->filter()
        ->map(fn($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');

    $statusClasses = match ($investigator->status) {
        'inactive' => 'bg-slate-200 text-slate-700',
        'suspended' => 'bg-red-100 text-red-700',
        default => 'bg-green-100 text-green-700',
    };

    $profileImagePath = ltrim((string) ($investigator->profile_image ?? ''), '/');
    $profileImageUrl = null;
    if ($profileImagePath !== '') {
        if (\Illuminate\Support\Str::startsWith($profileImagePath, ['http://', 'https://'])) {
            $profileImageUrl = $profileImagePath;
        } elseif (file_exists(public_path($profileImagePath))) {
            $profileImageUrl = asset($profileImagePath);
        } else {
            $profileImageUrl = asset('storage/' . $profileImagePath);
        }
    }
@endphp

<tr id="investigator-row-{{ $investigator->id }}"
    class="group border-l-4 border-l-transparent hover:border-l-[#0B3D91] hover:bg-blue-50/40 transition-all duration-150">
    <td id="investigator-badge-{{ $investigator->id }}" class="py-4 px-4">
        <span
            class="inline-flex items-center gap-1 font-mono text-xs font-bold text-[#0B3D91] bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full tracking-wide">
            <i class="fa-solid fa-id-badge text-[10px] opacity-60"></i>
            #{{ $investigator->badge_number }}
        </span>
    </td>
    <td class="py-4 px-4">
        <div class="flex items-center gap-3">
            @if ($profileImageUrl)
                <div class="relative shrink-0">
                    <img id="investigator-image-{{ $investigator->id }}" src="{{ $profileImageUrl }}"
                        alt="{{ $investigator->full_name }}"
                        class="h-11 w-11 rounded-full object-cover ring-2 ring-[#0B3D91]/30 shadow-md group-hover:ring-[#0B3D91]/60 transition-all duration-200">
                    <span
                        class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-green-400 border-2 border-white"></span>
                </div>
            @else
                <div id="investigator-initials-{{ $investigator->id }}"
                    class="h-11 w-11 rounded-full bg-gradient-to-br from-[#0B3D91] to-blue-400 text-white flex items-center justify-center font-bold text-sm shadow-md shrink-0 group-hover:scale-105 transition-transform duration-200">
                    {{ $initials ?: 'NA' }}
                </div>
            @endif
            <div class="flex flex-col">
                <span id="investigator-name-{{ $investigator->id }}"
                    class="font-semibold text-gray-800 text-sm leading-tight">
                    {{ $investigator->full_name }}
                </span>
                <span class="text-xs text-gray-400 mt-0.5">Investigator</span>
            </div>
        </div>
    </td>
    <td class="py-4 px-4">
        @php
            $dotColor = match ($investigator->status) {
                'inactive' => 'bg-slate-400',
                'suspended' => 'bg-red-500',
                default => 'bg-green-500',
            };
        @endphp
        <span id="investigator-status-{{ $investigator->id }}"
            class="{{ $statusClasses }} inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider">
            <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }}"></span>
            {{ $investigator->status }}
        </span>
    </td>
    <td class="py-4 px-4">
        <div class="flex items-center gap-1.5 text-gray-500 text-sm">
            <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
            {{ $investigator->created_at->format('M d, Y') }}
        </div>
    </td>
    <td class="py-4 px-4 text-center">
        <div class="flex justify-center gap-2">
            @include('investigator.accounts.modals.edit_account_modal', ['investigator' => $investigator])
            @include('investigator.accounts.modals.delete_account_modal', [
                'investigator' => $investigator,
            ])
        </div>
    </td>
</tr>
