<?php

namespace App\Providers;

use App\Models\InvestigatorNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'investigator.components.header',
            'investigator.components.left_sidebar',
        ], function ($view) {
            $unreadCount = 0;
            $recentNotifications = collect();

            if (Auth::guard('investigator')->check() && Schema::hasTable('investigator_notifications')) {
                $investigatorId = Auth::guard('investigator')->id();

                $unreadCount = InvestigatorNotification::query()
                    ->where('investigator_id', $investigatorId)
                    ->where('is_read', false)
                    ->count();

                $recentNotifications = InvestigatorNotification::query()
                    ->where('investigator_id', $investigatorId)
                    ->latest()
                    ->take(5)
                    ->get();
            }

            $view->with([
                'investigatorNotificationUnreadCount' => $unreadCount,
                'investigatorRecentNotifications' => $recentNotifications,
            ]);
        });
    }
}
