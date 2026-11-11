<?php

namespace App\Providers;

use App\Models\Promotion;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $activePromotion = null;
            $unreadNotificationCount = 0;

            if (Schema::hasTable('promotions')) {
                $activePromotion = Promotion::currentlyActive()->orderByDesc('discount_percent')->first();
            }
            if (Auth::check() && !Auth::user()->is_admin && Schema::hasTable('user_notifications') && Schema::hasColumn('user_notifications', 'is_read')) {
                $unreadNotificationCount = UserNotification::where('user_id', Auth::id())->where('is_read', false)->count();
            }

            $view->with(compact('activePromotion', 'unreadNotificationCount'));
        });
    }
}
