<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce Lipa | Notifications</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(11, 61, 145, 0.12), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #f4f7fb 100%);
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

        .hero-panel {
            background: linear-gradient(140deg, #081f4d 0%, #0b3d91 50%, #1e5fd0 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            inset: auto -10% -35% auto;
            width: 18rem;
            height: 18rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.08);
            filter: blur(2px);
        }

        .stat-card {
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">
    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">
        @include('investigator.components.left_sidebar')

        <main class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6">
            <section class="hero-panel rounded-[1.75rem] p-6 md:p-8 text-white shadow-xl shadow-blue-900/10">
                <div class="relative z-10 flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-[11px] uppercase tracking-[0.25em] text-blue-100">Investigator Message Center</p>
                        <h1 class="mt-3 text-3xl md:text-4xl font-black leading-tight">Case alerts, assignment updates,
                            and system reminders in one place.</h1>
                        <p class="mt-3 max-w-xl text-sm md:text-base text-blue-100/95">This notification board keeps
                            investigators focused on unread assignments, urgent incident activity, and follow-up actions
                            without checking multiple screens.</p>
                    </div>

                    @if (($stats['unread'] ?? 0) > 0)
                        <form method="POST" action="{{ route('investigator.notification.read.all') }}"
                            class="relative z-10">
                            @csrf
                            @method('PATCH')
                            <button style="color: black" type="submit"
                                class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-black uppercase tracking-wide text-tf-blue shadow-lg shadow-slate-900/20 transition-transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-check-double text-tf-red"></i>
                                Mark All as Read
                            </button>
                        </form>
                    @endif
                </div>
            </section>

            <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="stat-card rounded-2xl border border-white/70 bg-white/90 p-5 shadow-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">Total Notifications</p>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <p class="text-3xl font-black text-slate-900">{{ $stats['total'] }}</p>
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-tf-blue">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card rounded-2xl border border-blue-100 bg-blue-50/90 p-5 shadow-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-blue-700">Unread Alerts</p>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <p class="text-3xl font-black text-blue-950">{{ $stats['unread'] }}</p>
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-blue-700">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card rounded-2xl border border-red-100 bg-red-50/90 p-5 shadow-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-red-700">High Priority</p>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <p class="text-3xl font-black text-red-900">{{ $stats['priority'] }}</p>
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-tf-red">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card rounded-2xl border border-emerald-100 bg-emerald-50/90 p-5 shadow-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700">Read Today</p>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <p class="text-3xl font-black text-emerald-900">{{ $stats['readToday'] }}</p>
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-emerald-700">
                            <i class="fa-solid fa-badge-check"></i>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200/80 bg-white/95 shadow-sm">
                <div
                    class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">Notification Feed</h2>
                        <p class="mt-1 text-sm text-slate-500">Review activity, open the related incident, or clear
                            completed alerts.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('investigator.notification.page') }}"
                            class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-wide transition-colors {{ $activeFilter === '' ? 'bg-tf-blue text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            All
                        </a>
                        <a href="{{ route('investigator.notification.page', ['filter' => 'unread']) }}"
                            class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-wide transition-colors {{ $activeFilter === 'unread' ? 'bg-tf-red text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Unread
                        </a>
                        <a href="{{ route('investigator.notification.page', ['filter' => 'priority']) }}"
                            class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-wide transition-colors {{ $activeFilter === 'priority' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Priority
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-slate-200/80">
                    @forelse ($notifications as $notification)
                        @php
                            $iconByType = [
                                'assignment' => 'fa-user-shield',
                                'incident_update' => 'fa-file-circle-exclamation',
                                'report' => 'fa-folder-open',
                                'system' => 'fa-gear',
                            ];

                            $priorityClasses = [
                                'low' => 'bg-slate-100 text-slate-600',
                                'medium' => 'bg-blue-100 text-blue-700',
                                'high' => 'bg-amber-100 text-amber-700',
                                'urgent' => 'bg-red-100 text-red-700',
                            ];

                            $typeClasses = [
                                'assignment' => 'bg-blue-600 text-white',
                                'incident_update' => 'bg-amber-500 text-white',
                                'report' => 'bg-emerald-600 text-white',
                                'system' => 'bg-slate-600 text-white',
                            ];

                            $notificationIcon = $iconByType[$notification->type] ?? 'fa-bell';
                            $priorityBadgeClass =
                                $priorityClasses[$notification->priority] ?? $priorityClasses['medium'];
                            $typeBadgeClass = $typeClasses[$notification->type] ?? $typeClasses['system'];
                        @endphp

                        <article
                            class="px-5 py-5 transition-colors {{ $notification->is_read ? 'bg-white' : 'bg-blue-50/50' }} hover:bg-slate-50">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $typeBadgeClass }} shadow-sm">
                                        <i class="fa-solid {{ $notificationIcon }}"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-base font-black text-slate-900">{{ $notification->title }}
                                            </h3>

                                            @if (!$notification->is_read)
                                                <span
                                                    class="rounded-full bg-tf-red px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white">
                                                    New
                                                </span>
                                            @endif

                                            <span
                                                class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $priorityBadgeClass }}">
                                                {{ $notification->priority }}
                                            </span>
                                        </div>

                                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                            {{ $notification->message }}</p>

                                        <div
                                            class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-medium text-slate-400">
                                            <span><i
                                                    class="fa-regular fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</span>

                                            @if ($notification->incident_id)
                                                <span><i class="fa-solid fa-hashtag mr-1"></i>Incident ID
                                                    {{ $notification->incident_id }}</span>
                                            @endif

                                            @if ($notification->creator)
                                                <span><i
                                                        class="fa-solid fa-user-pen mr-1"></i>{{ $notification->creator->full_name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex shrink-0 flex-wrap items-center gap-2 lg:justify-end">
                                    @if ($notification->action_url)
                                        <a href="{{ $notification->action_url }}"
                                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-slate-700 transition-colors hover:border-tf-blue hover:text-tf-blue">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            Open
                                        </a>
                                    @endif

                                    @if (!$notification->is_read)
                                        <form method="POST"
                                            action="{{ route('investigator.notification.read', $notification) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl bg-tf-blue px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-sm transition-transform hover:-translate-y-0.5">
                                                <i class="fa-solid fa-check"></i>
                                                Mark as Read
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-2 text-xs font-bold uppercase tracking-wide text-emerald-700">
                                            <i class="fa-solid fa-circle-check"></i>
                                            Read {{ optional($notification->read_at)->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <div
                                class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-slate-100 text-slate-400">
                                <i class="fa-solid fa-envelope-open-text text-3xl"></i>
                            </div>
                            <h3 class="mt-5 text-xl font-black text-slate-800">Your notification feed is clear.</h3>
                            <p class="mt-2 text-sm text-slate-500">When reports are assigned or updated, new alerts will
                                appear here automatically.</p>
                        </div>
                    @endforelse
                </div>

                @if ($notifications->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
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
