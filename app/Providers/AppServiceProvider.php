<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

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
        view()->share('nav', include app_path('Helpers/navigation.php'));

        View::composer('*', function ($view) {

            $latestNotifications = collect();

            $unreadNotificationCount = 0;

            if (Auth::check()) {

                $latestNotifications = Notification::where(
                    'user_id',
                    Auth::id()
                )
                    ->latest()
                    ->take(10)
                    ->get();

                $unreadNotificationCount = $latestNotifications
                    ->where('is_read', false)
                    ->count();
            }

            $view->with([
                'unreadNotificationCount' => $unreadNotificationCount,
                'latestNotifications' => $latestNotifications,
            ]);
        });
    }
}
