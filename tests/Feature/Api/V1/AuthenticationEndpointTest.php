<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_register_success(): void
    {
        //
        // Arrange
        //
        $password = fake()->password(16);
        $payload = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'confirm_password' => $password,
        ];

        //
        // Act
        //
        $response = $this->postJson(route('api.v1.auth.register'), $payload);

        //
        // Assert
        //
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['token', 'name',],
            'message'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_login_success(): void
    {
        //
        // Arrange
        //
        $password = fake()->password(16);
        $user = $this->registerNewUser($password);

        $payload = [
            'email' => $user->email,
            'password' => $password,
        ];

        //
        // Act
        //
        $response = $this->postJson(route('api.v1.auth.login'), $payload);

        //
        // Assert
        //
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['token', 'name',],
            'message'
        ]);
    }

    public function test_login_fail(): void
    {
        //
        // Arrange
        //
        $payload = [
            'email' => fake()->email(),
            'password' => fake()->password(),
        ];

        //
        // Act
        //
        $response = $this->postJson(route('api.v1.auth.login'), $payload);

        //
        // Assert
        //
        $response->assertStatus(401);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_get_user_success(): void
    {
        //
        // Arrange
        //
        $user = $this->registerNewUser();

        //
        // Act
        //
        $response = $this->actingAs($user)->getJson(
            route('api.v1.auth.user'),
            $this->getAuthorizationHeader($user)
        );

        //
        // Assert
        //
        $response->assertStatus(200);
    }
}
