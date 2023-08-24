<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * @throws BindingResolutionException
     */
    private function userService(): UserService
    {
        return app()->make(UserService::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_register_success(): void
    {
        //
        // Arrange
        //
        $password = fake()->password();
        $input = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'confirm_password' => $password,
        ];

        //
        // Act
        //
        $result = $this->userService()->register($input);

        //
        // Assert
        //
        $this->assertInstanceOf(User::class, $result);
        $this->assertTrue($result->exists);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_register_user_must_have_regular_role(): void
    {
        //
        // Arrange
        //
        $password = fake()->password();
        $input = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'confirm_password' => $password,
        ];

        //
        // Act
        //
        $result = $this->userService()->register($input);

        //
        // Assert
        //
        $this->assertTrue($result->hasRegularRole());
    }
}
