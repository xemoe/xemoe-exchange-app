<?php

namespace App\Providers;

use App\Repositories\CurrencyRepository;
use App\Repositories\FiatCurrencyRepository;
use App\Repositories\TradingPairRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserRolesRepository;
use App\Repositories\WalletRepository;
use App\Services\AuthenticationService;
use App\Services\CurrencyService;
use App\Services\FiatCurrencyService;
use App\Services\TradingPairService;
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

        // FiatCurrencyRepository
        $this->app->singleton(FiatCurrencyRepository::class, function ($app) {
            return new FiatCurrencyRepository();
        });

        // WalletRepository
        $this->app->singleton(WalletRepository::class, function ($app) {
            return new WalletRepository();
        });

        // TradingPairRepository
        $this->app->singleton(TradingPairRepository::class, function ($app) {
            return new TradingPairRepository();
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

        // FiatCurrencyService
        $this->app->singleton(FiatCurrencyService::class, function ($app) {
            return new FiatCurrencyService(
                $app->make(FiatCurrencyRepository::class),
            );
        });

        // TradingPairService
        $this->app->singleton(TradingPairService::class, function ($app) {
            return new TradingPairService(
                $app->make(TradingPairRepository::class),
                $app->make(CurrencyRepository::class),
                $app->make(FiatCurrencyRepository::class),
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
