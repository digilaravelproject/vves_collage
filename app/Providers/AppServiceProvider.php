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
        View::composer(['layouts.admin.partials.sidebar', 'partials.header'], function ($view) {
            if (Schema::hasTable('pending_actions')) {
                $count = \App\Models\PendingAction::where('status', '=', 'pending', 'and')->count('*');
                $view->with('pendingWorkflowCount', $count);
            }

            // Share active institutions for the dynamic menu
            if (Schema::hasTable('institutions')) {
                $institutions = \Illuminate\Support\Facades\Cache::remember('global_institutions', 3600, function() {
                    return \App\Models\Institution::where('status', '=', true, 'and')->orderBy('name', 'asc')->get();
                });
                $view->with('global_institutions', $institutions);
            }
        });

        // Dynamically set Mail configuration from DB
        try {
            if (Schema::hasTable('smtp_settings')) {
                $mailSetting = \App\Models\SmtpSetting::query()->first();
                if ($mailSetting) {
                    config([
                        'mail.mailers.smtp.host' => $mailSetting->host,
                        'mail.mailers.smtp.port' => $mailSetting->port,
                        'mail.mailers.smtp.encryption' => $mailSetting->encryption === 'none' ? null : $mailSetting->encryption,
                        'mail.mailers.smtp.username' => $mailSetting->username,
                        'mail.mailers.smtp.password' => $mailSetting->password,
                        'mail.from.address' => $mailSetting->from_address,
                        'mail.from.name' => $mailSetting->from_name ?? config('app.name'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if DB is not ready
        }
    }
}
