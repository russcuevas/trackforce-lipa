<header class="bg-tf-blue h-16 flex items-center justify-between px-6 shadow-lg z-20">
    @php
        $investigator = \Illuminate\Support\Facades\Auth::guard('investigator')->user();
        $fullName = $investigator->full_name ?? 'Investigator';
        $badgeNumber = $investigator->badge_number ?? 'N/A';
        $unreadCount = $investigatorNotificationUnreadCount ?? 0;
        $recentNotifications = $investigatorRecentNotifications ?? collect();
        $profileImage = null;
        if ($investigator && $investigator->profile_image) {
            $profilePath = ltrim((string) $investigator->profile_image, '/');
            if (\Illuminate\Support\Str::startsWith($profilePath, ['http://', 'https://'])) {
                $profileImage = $profilePath;
            } elseif (file_exists(public_path($profilePath))) {
                $profileImage = asset($profilePath);
            } else {
                $profileImage = asset('storage/' . $profilePath);
            }
        }
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
        <div id="notificationMenuWrapper" class="relative z-[999999]">
            <button type="button" id="notificationMenuButton"
                class="relative flex h-10 w-10 items-center justify-center rounded-full text-white transition-colors hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/30">
                <i class="fa-solid fa-bell text-xl"></i>
                @if ($unreadCount > 0)
                    <span id="notificationUnreadBadge"
                        class="absolute -top-1 -right-1 min-w-[1.25rem] h-5 bg-tf-red text-white text-[10px] font-bold px-1.5 rounded-full flex items-center justify-center">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <div id="notificationMenuPanel"
                class="fixed left-3 right-3 top-20 hidden md:absolute md:left-auto md:right-0 md:top-auto md:mt-3 md:w-[22rem] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20 z-[1000000]">
                <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-blue-700 px-4 py-4 text-white">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.2em] text-blue-100">Notifications</p>
                            <h4 class="mt-1 text-sm font-bold">Investigator Updates</h4>
                        </div>
                        <span id="notificationUnreadText"
                            class="rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-bold">
                            {{ $unreadCount }} unread
                        </span>
                    </div>
                </div>

                <div id="notificationList" class="max-h-[70vh] md:max-h-96 overflow-y-auto bg-slate-50">
                    @forelse ($recentNotifications as $notification)
                        @php
                            $typeStyles = [
                                'assignment' => 'bg-blue-100 text-blue-700',
                                'incident_update' => 'bg-amber-100 text-amber-700',
                                'report' => 'bg-emerald-100 text-emerald-700',
                                'system' => 'bg-slate-200 text-slate-700',
                            ];
                            $badgeClass = $typeStyles[$notification->type] ?? $typeStyles['system'];
                        @endphp

                        <a href="{{ $notification->action_url ?: route('investigator.notification.page') }}"
                            class="block border-b border-slate-200/80 px-4 py-3 transition-colors hover:bg-white {{ $notification->is_read ? '' : 'bg-blue-50/60' }}">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 h-2.5 w-2.5 rounded-full {{ $notification->is_read ? 'bg-slate-300' : 'bg-tf-red' }}">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="truncate text-sm font-bold text-slate-800">{{ $notification->title }}
                                        </p>
                                        <span
                                            class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', $notification->type) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs leading-5 text-slate-600">
                                        {{ \Illuminate\Support\Str::limit($notification->message, 88) }}
                                    </p>
                                    <p class="mt-2 text-[11px] font-medium text-slate-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                                <i class="fa-solid fa-bell-slash text-lg"></i>
                            </div>
                            <p class="mt-4 text-sm font-bold text-slate-700">No notifications yet</p>
                            <p class="mt-1 text-xs text-slate-500">Incident updates and assignment alerts will appear
                                here.</p>
                        </div>
                    @endforelse
                </div>

                <div class="flex items-center justify-between border-t border-slate-200 bg-white px-4 py-3">
                    <a href="{{ route('investigator.notification.page') }}"
                        class="text-sm font-bold text-tf-blue hover:underline">
                        View all notifications
                    </a>

                    @if ($unreadCount > 0)
                        <form method="POST" action="{{ route('investigator.notification.read.all') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="text-xs font-bold uppercase tracking-wide text-tf-red hover:opacity-80">
                                Mark all read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div id="profileMenuWrapper" class="relative border-l border-blue-800 pl-6">
            <a href="{{ route('investigator.profile.page') }}"
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

            </a>
        </div>
    </div>
</header>

<script>
    (() => {
        const button = document.getElementById('notificationMenuButton');
        const panel = document.getElementById('notificationMenuPanel');
        const wrapper = document.getElementById('notificationMenuWrapper');
        const unreadText = document.getElementById('notificationUnreadText');
        const notificationList = document.getElementById('notificationList');
        const realtimeEndpoint = @json(route('investigator.notification.realtime'));
        const fallbackPageUrl = @json(route('investigator.notification.page'));
        const typeStyles = {
            assignment: 'bg-blue-100 text-blue-700',
            incident_update: 'bg-amber-100 text-amber-700',
            report: 'bg-emerald-100 text-emerald-700',
            system: 'bg-slate-200 text-slate-700',
        };

        if (!button || !panel || !wrapper) {
            return;
        }

        const escapeHtml = value => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        const updateUnreadBadge = count => {
            const existingBadge = document.getElementById('notificationUnreadBadge');

            if (count <= 0) {
                if (existingBadge) {
                    existingBadge.remove();
                }
                return;
            }

            if (existingBadge) {
                existingBadge.textContent = count > 99 ? '99+' : String(count);
                return;
            }

            const badge = document.createElement('span');
            badge.id = 'notificationUnreadBadge';
            badge.className =
                'absolute -top-1 -right-1 min-w-[1.25rem] h-5 bg-tf-red text-white text-[10px] font-bold px-1.5 rounded-full flex items-center justify-center';
            badge.textContent = count > 99 ? '99+' : String(count);
            button.appendChild(badge);
        };

        const renderEmptyState = () => {
            if (!notificationList) {
                return;
            }

            notificationList.innerHTML = `
                <div class="px-5 py-10 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                        <i class="fa-solid fa-bell-slash text-lg"></i>
                    </div>
                    <p class="mt-4 text-sm font-bold text-slate-700">No notifications yet</p>
                    <p class="mt-1 text-xs text-slate-500">Incident updates and assignment alerts will appear here.</p>
                </div>
            `;
        };

        const renderNotificationList = notifications => {
            if (!notificationList) {
                return;
            }

            if (!Array.isArray(notifications) || notifications.length === 0) {
                renderEmptyState();
                return;
            }

            notificationList.innerHTML = notifications.map(notification => {
                const isRead = Boolean(notification.is_read);
                const badgeClass = typeStyles[notification.type] || typeStyles.system;
                const rowStateClass = isRead ? '' : 'bg-blue-50/60';
                const dotClass = isRead ? 'bg-slate-300' : 'bg-tf-red';
                const typeLabel = String(notification.type || 'system').replace('_', ' ');

                return `
                    <a href="${escapeHtml(notification.action_url || fallbackPageUrl)}"
                        class="block border-b border-slate-200/80 px-4 py-3 transition-colors hover:bg-white ${rowStateClass}">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 h-2.5 w-2.5 rounded-full ${dotClass}"></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="truncate text-sm font-bold text-slate-800">${escapeHtml(notification.title)}</p>
                                    <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase ${badgeClass}">${escapeHtml(typeLabel)}</span>
                                </div>
                                <p class="mt-1 text-xs leading-5 text-slate-600">${escapeHtml(notification.message || '').slice(0, 88)}</p>
                                <p class="mt-2 text-[11px] font-medium text-slate-400">${escapeHtml(notification.created_at_human || 'just now')}</p>
                            </div>
                        </div>
                    </a>
                `;
            }).join('');
        };

        const fetchRealtimeNotifications = async () => {
            try {
                const response = await fetch(realtimeEndpoint, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();
                const unreadCount = Number(data.unread_count || 0);

                if (unreadText) {
                    unreadText.textContent = `${unreadCount} unread`;
                }

                updateUnreadBadge(unreadCount);
                renderNotificationList(data.notifications || []);
            } catch (error) {
                // Keep UI stable if polling temporarily fails.
            }
        };

        button.addEventListener('click', () => {
            panel.classList.toggle('hidden');
        });

        document.addEventListener('click', event => {
            if (!wrapper.contains(event.target)) {
                panel.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                panel.classList.add('hidden');
            }
        });

        fetchRealtimeNotifications();
        setInterval(fetchRealtimeNotifications, 8000);
    })();
</script>
