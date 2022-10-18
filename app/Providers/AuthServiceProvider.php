<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Opcodes\LogViewer\LogFile;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Log viewer
        Gate::define('viewLogViewer', function (?User $user) {
            return $user->hasRole('Super Admin') ? true : null;
        });
        Gate::define('downloadLogFile', function (?User $user, LogFile $file) {
            return $user->hasRole('Super Admin') ? true : null;
        });
        Gate::define('deleteLogFile', function (?User $user, LogFile $file) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
