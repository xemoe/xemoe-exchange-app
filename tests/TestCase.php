<?php

namespace Tests;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\CurrencyRepository;
use App\Repositories\FiatCurrencyRepository;
use App\Repositories\TradingPairRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\AuthenticationService;
use App\Services\CurrencyService;
use App\Services\FiatCurrencyService;
use App\Services\UserService;
use App\Services\WalletService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @throws BindingResolutionException
     */
    protected function currencyRepository(): CurrencyRepository
    {
        return app()->make(CurrencyRepository::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function fiatCurrencyRepository(): FiatCurrencyRepository
    {
        return app()->make(FiatCurrencyRepository::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function userRepository(): UserRepository
    {
        return app()->make(UserRepository::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function walletRepository(): WalletRepository
    {
        return app()->make(WalletRepository::class);
    }

    protected function tradingPairRepository(): TradingPairRepository
    {
        return $this->app->make(TradingPairRepository::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function userService(): UserService
    {
        return app()->make(UserService::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function currencyService(): CurrencyService
    {
        return app()->make(CurrencyService::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function fiatCurrencyService(): FiatCurrencyService
    {
        return app()->make(FiatCurrencyService::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function walletService(): WalletService
    {
        return app()->make(WalletService::class);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function registerNewUser(string $password = null): User
    {
        $password = $password ?: fake()->password(16);
        $input = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'confirm_password' => $password,
        ];

        return $this->userService()->register($input);
    }

    /**
     * @param User $user
     * @return string[]
     */
    protected function getAuthorizationHeader(User $user): array
    {
        $token = AuthenticationService::getToken($user);
        return ['Authorization' => "Bearer $token"];
    }

    /**
     * @throws BindingResolutionException
     */
    protected function createWallet(User $user, string $description, string $symbol): Wallet
    {
        $currency = $this->currencyRepository()->create($description, $symbol);
        return $this->walletRepository()->create($user, $currency);
    }
}
