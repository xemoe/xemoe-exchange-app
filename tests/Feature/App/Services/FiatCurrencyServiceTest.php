<?php

namespace Tests\Feature\App\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class FiatCurrencyServiceTest extends TestCase
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
            'symbol' => fake()->currencyCode(),
        ];

        //
        // Act
        //
        $actual = $this->fiatCurrencyService()->add($input);

        //
        // Assert
        //
        $this->assertTrue($actual->exists);
        $this->assertDatabaseHas('fiat_currencies', $input);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_add_failure_duplicated_symbol(): void
    {
        //
        // Arrange
        //
        $symbol = fake()->currencyCode();
        $this->fiatCurrencyService()->add([
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
        $actual = $this->fiatCurrencyService()->add($input);

        //
        // Assert
        //
        $this->assertInstanceOf(MessageBag::class, $actual);
        $this->assertDatabaseMissing('fiat_currencies', $input);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_symbols_success(): void
    {
        //
        // Arrange
        //
        $symbol = fake()->currencyCode();
        $this->fiatCurrencyService()->add([
            'name' => fake()->name(),
            'symbol' => $symbol,
        ]);

        //
        // Act
        //
        $actual = $this->fiatCurrencyService()->symbols();

        //
        // Assert
        //
        $this->assertContains($symbol, $actual);
    }
}
