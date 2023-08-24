<?php

namespace Api\V1;

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_register_success(): void
    {
        //
        // Arrange
        //
        $password = fake()->password();
        $payload = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'confirm_password' => $password,
        ];

        //
        // Act
        //
        $response = $this->postJson('/api/v1/register', $payload);

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

    public function test_login_success(): void
    {
        //
        // Arrange
        //
        $password = fake()->password;
        $user = User::factory()->create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => bcrypt($password)
        ]);

        $payload = [
            'email' => $user->email,
            'password' => $password,
        ];

        //
        // Act
        //
        $response = $this->postJson('/api/v1/login', $payload);

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
        $response = $this->postJson('/api/v1/login', $payload);

        //
        // Assert
        //
        $response->assertStatus(401);
    }

    public function test_get_user_success(): void
    {
        //
        // Arrange
        //
        $user = User::factory()->create([
            'email' => fake()->email(),
            'password' => fake()->password(),
        ]);

        $bearerToken = AuthenticationService::getToken($user);

        //
        // Act
        //
        $response = $this->actingAs($user)->getJson('/api/v1/user', ['Authorization' => 'Bearer ' . $bearerToken]);

        //
        // Assert
        //
        $response->assertStatus(200);
    }
}
