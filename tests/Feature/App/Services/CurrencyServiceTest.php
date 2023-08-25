<?php

namespace Tests\Feature\App\Services;

use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
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
        $input = [
            'name' => fake()->name(),
            'symbol' => fake()->unique()->currencyCode(),
        ];

        //
        // Act
        //
        $actual = $this->currencyService()->add($input);

        //
        // Assert
        //
        $this->assertTrue($actual->exists);
        $this->assertDatabaseHas('crypto_currencies', $input);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_add_failure_duplicated_symbol(): void
    {
        //
        // Arrange
        //
        $symbol = fake()->unique()->currencyCode();
        $this->currencyService()->add([
            'name' => fake()->name(),
            'symbol' => $symbol,
        ]);

        $input = [
            'name' => fake()->name(),
            'symbol' => $symbol,
        ];

        //
        // Act
        //
        $actual = $this->currencyService()->add($input);

        //
        // Assert
        //
        $this->assertInstanceOf(MessageBag::class, $actual);
        $this->assertDatabaseMissing('crypto_currencies', $input);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_symbols_success(): void
    {
        //
        // Arrange
        //
        $symbol = fake()->unique()->currencyCode();
        $this->currencyService()->add([
            'name' => fake()->name(),
            'symbol' => $symbol,
        ]);

        //
        // Act
        //
        $actual = $this->currencyService()->symbols();

        //
        // Assert
        //
        $this->assertContains($symbol, $actual);
    }
}
