<?php

namespace App\Providers;

use App\Repositories\CurrencyRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserRolesRepository;
use App\Repositories\WalletRepository;
use App\Services\AuthenticationService;
use App\Services\CurrencyService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // UserRepository
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        // UserRolesRepository
        $this->app->singleton(UserRolesRepository::class, function ($app) {
            return new UserRolesRepository();
        });

        // CryptoCurrencyRepository
        $this->app->singleton(CurrencyRepository::class, function ($app) {
            return new CurrencyRepository();
        });

        // WalletRepository
        $this->app->singleton(WalletRepository::class, function ($app) {
            return new WalletRepository();
        });

        // UserService
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(UserRepository::class),
                $app->make(UserRolesRepository::class),
            );
        });

        // AuthenticationService
        $this->app->singleton(AuthenticationService::class, function ($app) {
            return new AuthenticationService();
        });

        // CurrencyService
        $this->app->singleton(CurrencyService::class, function ($app) {
            return new CurrencyService(
                $app->make(CurrencyRepository::class),
            );
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
