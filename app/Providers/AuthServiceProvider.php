<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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
    public function boot(): void
    {
        // هنا يتم وضع تعريفات الـ Gates:
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('is-doctor', function (User $user) {
            return $user->role === 'doctor';
        });

        Gate::define('is-patient', function (User $user) {
            return $user->role === 'patient';
        });

        // يمكنك أيضاً تعريف بوابة للتحقق من أدوار متعددة إذا كنت تريد استخدامها في مكان واحد
        Gate::define('is-admin-or-doctor', function (User $user) {
            return $user->role === 'admin' || $user->role === 'doctor';
        });
    }
}




