<?php

namespace Tests\Feature\App\Services;

use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * @throws BindingResolutionException
     */
    public function test_register_success(): void
    {
        //
        // Arrange
        //
        $password = fake()->password(16);
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
    public function test_register_failure_short_password(): void
    {
        //
        // Arrange
        //
        $password = fake()->password(4, 4);
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
        $this->assertInstanceOf(MessageBag::class, $result);
        $this->assertEquals('The password field must be at least 8 characters.', $result->first('password'));
        $this->assertDatabaseMissing('users', [
            'name' => $input['name'],
            'email' => $input['email'],
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_register_user_must_have_regular_role(): void
    {
        //
        // Arrange
        //
        $password = fake()->password(16);
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
