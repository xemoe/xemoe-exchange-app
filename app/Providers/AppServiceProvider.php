<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // UserRepository
        //
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        //
        // UserService
        //
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(UserRepository::class)
            );
        });

        //
        // AuthenticationService
        //
        $this->app->singleton(AuthenticationService::class, function ($app) {
            return new AuthenticationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
