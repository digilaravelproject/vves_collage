<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
    public function boot()
    {
        // Globally share pending workflow count for the sidebar badge
        View::composer('layouts.admin.partials.sidebar', function ($view) {
            if (Schema::hasTable('pending_actions')) {
                $count = \App\Models\PendingAction::where('status', 'pending')->count();
                $view->with('pendingWorkflowCount', $count);
            }
        });
    }
}
