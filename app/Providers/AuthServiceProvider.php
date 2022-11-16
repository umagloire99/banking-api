<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

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
        Passport::loadKeysFrom(base_path(config('passport.key_path')));
        Passport::tokensCan([
            'user-side' => 'User can access user resource',
            'admin-side' => 'user can access admin resource'
        ]);
        Passport::personalAccessTokensExpireIn(now()->addDay());

    }
}
