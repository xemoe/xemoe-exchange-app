<?php

namespace Tests\Feature\App\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * @throws BindingResolutionException
     */
    public function test_add_success(): void
    {
        //
        // Arrange
        //
        $newUser = $this->registerNewUser();
        $newCurrency = $this->currencyRepository()::create(fake()->word(), fake()->currencyCode());

        $input = [
            'user_id' => $newUser->id,
            'symbol' => $newCurrency->symbol,
        ];

        //
        // Act
        //
        $actual = $this->walletService()->addBySymbol($input);

        //
        // Assert
        //
        $this->assertTrue($actual->exists);
        $this->assertDatabaseHas('wallets', [
            'user_id' => $newUser->id,
            'currency_id' => $newCurrency->id,
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_add_failure_invalid_symbol(): void
    {
        //
        // Arrange
        //
        $newUser = $this->registerNewUser();

        // Input with symbol that does not exist in the database
        $invalidInput = [
            'user_id' => $newUser->id,
            'symbol' => fake()->currencyCode(),
        ];

        //
        // Act
        //
        $actual = $this->walletService()->addBySymbol($invalidInput);

        //
        // Assert
        //
        $this->assertInstanceOf(MessageBag::class, $actual);
        $this->assertEquals('The selected symbol is invalid.', $actual->first('symbol'));
        $this->assertDatabaseMissing('wallets', [
            'user_id' => $newUser->id,
            'currency_id' => $invalidInput['symbol'],
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_remove_success(): void
    {
        //
        // Arrange
        //
        $newUser = $this->registerNewUser();
        $newCurrency = $this->currencyRepository()::create(fake()->word(), fake()->currencyCode());
        $newWallet = $this->walletRepository()::create($newUser, $newCurrency);

        $input = [
            'user_id' => $newUser->id,
            'wallet_id' => $newWallet->id,
        ];

        //
        // Act
        //
        $actual = $this->walletService()->removeById($input);

        //
        // Assert
        //
        $this->assertTrue($actual);
        $this->assertSoftDeleted('wallets', [
            'user_id' => $newUser->id,
            'currency_id' => $newCurrency->id,
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_remove_failure_not_owner(): void
    {
        //
        // Arrange
        //
        $newUser = $this->registerNewUser();
        $newCurrency = $this->currencyRepository()::create(fake()->word(), fake()->currencyCode());
        $newWallet = $this->walletRepository()::create($newUser, $newCurrency);

        $input = [
            'user_id' => $this->registerNewUser()->id,
            'wallet_id' => $newWallet->id,
        ];

        //
        // Act
        //
        $actual = $this->walletService()->removeById($input);

        //
        // Assert
        //
        $this->assertInstanceOf(MessageBag::class, $actual);
        $this->assertEquals('Wallet does not belong to user.', $actual->first('wallet_id'));

        $this->assertNotSoftDeleted('wallets', [
            'user_id' => $newUser->id,
            'currency_id' => $newCurrency->id,
        ]);
    }
}
