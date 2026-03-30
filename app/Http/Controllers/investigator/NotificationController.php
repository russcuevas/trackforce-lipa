<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use App\Models\InvestigatorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function NotificationPage(Request $request)
    {
        $investigatorId = Auth::guard('investigator')->id();
        $filter = $request->string('filter')->toString();

        $notificationsQuery = InvestigatorNotification::query()
            ->with(['incident', 'creator'])
            ->where('investigator_id', $investigatorId)
            ->latest();

        if ($filter === 'unread') {
            $notificationsQuery->where('is_read', false);
        }

        if ($filter === 'priority') {
            $notificationsQuery->whereIn('priority', ['high', 'urgent']);
        }

        $notifications = $notificationsQuery->paginate(10);
        $notifications->appends($request->query());

        $baseStatsQuery = InvestigatorNotification::query()->where('investigator_id', $investigatorId);

        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'unread' => (clone $baseStatsQuery)->where('is_read', false)->count(),
            'priority' => (clone $baseStatsQuery)->whereIn('priority', ['high', 'urgent'])->count(),
            'readToday' => (clone $baseStatsQuery)->whereDate('read_at', today())->count(),
        ];

        return view('investigator.notifications.index', [
            'notifications' => $notifications,
            'stats' => $stats,
            'activeFilter' => $filter,
        ]);
    }

    public function MarkAsReadRequest(Request $request, InvestigatorNotification $notification)
    {
        $this->authorizeNotification($notification);
        $notification->markAsRead();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Notification marked as read.',
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function RealtimeDataRequest(Request $request)
    {
        $investigatorId = Auth::guard('investigator')->id();

        $notifications = InvestigatorNotification::query()
            ->where('investigator_id', $investigatorId)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'unread_count' => InvestigatorNotification::query()
                ->where('investigator_id', $investigatorId)
                ->where('is_read', false)
                ->count(),
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'is_read' => (bool) $notification->is_read,
                    'action_url' => $notification->action_url ?: route('investigator.notification.page'),
                    'created_at_human' => optional($notification->created_at)->diffForHumans(),
                ];
            })->values(),
        ]);
    }

    public function MarkAllAsReadRequest(Request $request)
    {
        InvestigatorNotification::query()
            ->where('investigator_id', Auth::guard('investigator')->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'All notifications marked as read.',
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    private function authorizeNotification(InvestigatorNotification $notification): void
    {
        abort_unless($notification->investigator_id === Auth::guard('investigator')->id(), 403);
    }
}
