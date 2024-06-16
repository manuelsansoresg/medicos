<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('menu-admin', function (User $user) {
            return $user->hasRole('administrador') === true;
        });
        
        Gate::define('menu-users', function (User $user) {
            return $user->hasRole('medico') || $user->hasRole('auxiliar') === true;
        });
        
        
    }
}
