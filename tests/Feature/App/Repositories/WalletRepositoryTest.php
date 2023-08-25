<?php

namespace App\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\QueryException;
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
        $newCryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());

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

    /**
     * @throws BindingResolutionException
     */
    public function test_create_failure_missing_currency(): void
    {
        //
        // Arrange
        //
        $newUser = $this->userRepository()->create(fake()->name(), fake()->email(), fake()->password());

        // create new currency model without saving it to database
        $currencyInstance = $this->currencyRepository()->make(fake()->word(), fake()->currencyCode());

        //
        // Exception
        //
        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('Integrity constraint violation');

        //
        // Act
        //
        $this->walletRepository()->create($newUser, $currencyInstance);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_delete_should_be_soft_deleted(): void
    {
        //
        // Arrange
        //
        $newUser = $this->userRepository()->create(fake()->name(), fake()->email(), fake()->password());
        $newCryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $newWallet = $this->walletRepository()->create($newUser, $newCryptoCurrency);

        //
        // Act
        //
        $result = $this->walletRepository()->delete($newWallet);

        //
        // Assert
        //
        $this->assertTrue($result);
        $this->assertTrue($newWallet->trashed());
        $this->assertSoftDeleted('wallets', [
            'user_id' => $newUser->id,
            'currency_id' => $newCryptoCurrency->id,
        ]);

        // check if the wallet is still in the database
        $this->assertDatabaseHas('wallets', [
            'user_id' => $newUser->id,
            'currency_id' => $newCryptoCurrency->id,
        ]);

        // user wallet's relation should be empty
        $this->assertEmpty($newUser->wallets);
    }
}
