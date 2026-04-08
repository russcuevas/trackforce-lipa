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

<tr id="investigator-row-{{ $investigator->id }}" class="hover:bg-gray-50/50 transition-colors">
    <td id="investigator-badge-{{ $investigator->id }}" class="py-4 px-4 font-bold text-gray-400">
        #{{ $investigator->badge_number }}
    </td>
    <td class="py-4 px-4">
        <div class="flex items-center gap-3">
            @if ($profileImageUrl)
                <img id="investigator-image-{{ $investigator->id }}" src="{{ $profileImageUrl }}"
                    alt="{{ $investigator->full_name }}"
                    class="h-8 w-8 rounded-full object-cover border border-slate-200">
            @else
                <div id="investigator-initials-{{ $investigator->id }}"
                    class="h-8 w-8 rounded-full bg-blue-100 text-tf-blue flex items-center justify-center font-bold text-xs">
                    {{ $initials ?: 'NA' }}</div>
            @endif
            <span id="investigator-name-{{ $investigator->id }}" class="font-medium text-gray-700">
                {{ $investigator->full_name }}
            </span>
        </div>
    </td>
    <td class="py-4 px-4">
        <span id="investigator-status-{{ $investigator->id }}"
            class="{{ $statusClasses }} px-2 py-1 rounded text-[10px] font-black uppercase">{{ $investigator->status }}</span>
    </td>
    <td class="py-4 px-4 text-gray-500">{{ $investigator->created_at->format('M d, Y') }}</td>
    <td class="py-4 px-4 text-center">
        <div class="flex justify-center gap-2">
            @include('investigator.accounts.modals.edit_account_modal', ['investigator' => $investigator])
            @include('investigator.accounts.modals.delete_account_modal', [
                'investigator' => $investigator,
            ])
        </div>
    </td>
</tr>
