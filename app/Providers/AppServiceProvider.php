<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Transaction;

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
        // Share recent transactions to navbar as notifications
        View::composer('components.navbar', function ($view) {
            $recent = Transaction::orderByDesc('created_at')->limit(5)->get();
            $view->with('navbarNotifications', $recent);
        });
    }
}
