<?php

namespace Tests\Feature\App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
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
        $input = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => fake()->password(),
        ];

        //
        // Act
        //
        $result = $this->userRepository()->create(
            $input['name'],
            $input['email'],
            $input['password']
        );

        //
        // Assert
        //
        $this->assertInstanceOf(User::class, $result);
        $this->assertTrue($result->exists);
        $this->assertDatabaseHas('users', [
            'name' => $input['name'],
            'email' => $input['email'],
        ]);
    }
}
