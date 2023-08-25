<?php

namespace Tests\Feature\Api\V1;

use App\Models\Wallet;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\HttpStatusCode;
use Tests\TestCase;

class UserWalletTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_get_wallet_failure_no_authorized(): void
    {
        $response = $this->get('/api/v1/wallet');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_get_wallet_success_when_empty_wallet(): void
    {
        //
        // Arrange
        //
        $user = $this->registerNewUser();

        //
        // Act
        //
        $response = $this->get(
            '/api/v1/wallet',
            $this->getAuthorizationHeader($user)
        );

        //
        // Assert
        //
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_get_wallet_success_when_not_empty_wallet(): void
    {
        //
        // Arrange
        //
        $user = $this->registerNewUser();
        $this->createWallet($user, 'Bitcoin', 'BTC');

        //
        // Act
        //
        $response = $this->get(
            '/api/v1/wallet',
            $this->getAuthorizationHeader($user)
        );

        //
        // Assert
        //
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'success',
            'data' => ['balance', 'wallets'],
            'message'
        ]);
    }

    public function test_create_wallet_failure_no_authorized(): void
    {
        $response = $this->post('/api/v1/wallet');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_create_wallet_failure_invalid_symbol(): void
    {
        //
        // Arrange
        //
        $user = $this->registerNewUser();
        $payload = ['symbol' => fake()->currencyCode()];

        //
        // Act
        //
        $response = $this->post(
            '/api/v1/wallet',
            $payload,
            $this->getAuthorizationHeader($user)
        );

        //
        // Assert
        //
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_create_wallet_success(): void
    {
        //
        // Arrange
        //
        $user = $this->registerNewUser();
        $newCurrency = $this->currencyService()->add(['name' => fake()->word(), 'symbol' => fake()->currencyCode()]);
        $payload = ['symbol' => $newCurrency->symbol];

        //
        // Act
        //
        $response = $this->post(
            '/api/v1/wallet',
            $payload,
            $this->getAuthorizationHeader($user)
        );

        //
        // Assert
        //
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'success',
            'data' => ['id', 'address', 'balance', 'symbol'],
            'message'
        ]);
    }

    public function test_destroy_wallet_failure_no_authorized(): void
    {
        $response = $this->delete('/api/v1/wallet/foo');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_destroy_wallet_failure_invalid_id(): void
    {
        //
        // Arrange
        //
        $user = $this->registerNewUser();
        $payload = [];

        //
        // Act
        //
        $response = $this->delete(
            '/api/v1/wallet/foo',
            $payload,
            $this->getAuthorizationHeader($user)
        );

        //
        // Assert
        //
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_destroy_success(): void
    {
        //
        // Arrange
        //
        $user = $this->registerNewUser();
        $symbol = fake()->currencyCode();
        $this->createWallet($user, fake()->word(), $symbol);

        /** @var Wallet $wallet */
        $wallet = $user->wallets()
            ->whereHas('currency', fn($query) => $query->where('symbol', $symbol))
            ->first();

        //
        // Act
        //
        $response = $this->delete(
            '/api/v1/wallet/' . $wallet->id,
            [],
            $this->getAuthorizationHeader($user)
        );

        //
        // Assert
        //
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'success',
            'data',
            'message'
        ]);
    }
}