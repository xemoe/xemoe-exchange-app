<?php

namespace App\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * @throws BindingResolutionException
     */
    public function test_create_success(): void
    {
        //
        // Arrange
        //
        $newUser = $this->userRepository()->create(fake()->name(), fake()->email(), fake()->password());
        $newCryptoCurrency = $this->cryptoCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        //
        // Act
        //
        $result = $this->walletRepository()->create($newUser, $newCryptoCurrency);

        //
        // Assert
        //
        $this->assertTrue($result->exists);
        $this->assertDatabaseHas('wallets', [
            'user_id' => $newUser->id,
            'currency_id' => $newCryptoCurrency->id,
        ]);
    }
}
