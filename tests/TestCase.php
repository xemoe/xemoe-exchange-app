<?php

namespace Tests;

use App\Repositories\CryptoCurrencyRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\UserService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @throws BindingResolutionException
     */
    protected function cryptoCurrencyRepository(): CryptoCurrencyRepository
    {
        return app()->make(CryptoCurrencyRepository::class);
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

    /**
     * @throws BindingResolutionException
     */
    protected function userService(): UserService
    {
        return app()->make(UserService::class);
    }
}
