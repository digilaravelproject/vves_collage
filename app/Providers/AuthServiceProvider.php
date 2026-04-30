<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Institution::class => \App\Policies\InstitutionPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Give all permissions to the Admin and Super Admin roles
        Gate::before(function ($user, $ability) {
            if ($user->hasRole(['Admin', 'admin', 'Super Admin'])) {
                return true;
            }
        });
    }
}
