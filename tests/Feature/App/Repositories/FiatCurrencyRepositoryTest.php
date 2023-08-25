<?php

namespace Tests\Feature\App\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FiatCurrencyRepositoryTest extends TestCase
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
            'name' => fake()->word(),
            'symbol' => fake()->currencyCode(),
        ];

        //
        // Act
        //
        $result = $this->fiatCurrencyRepository()->create(
            $input['name'],
            $input['symbol'],
        );

        //
        // Assert
        //
        $this->assertTrue($result->exists);
        $this->assertDatabaseHas('fiat_currencies', [
            'name' => $input['name'],
            'symbol' => $input['symbol'],
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_create_failure_duplicate_symbol(): void
    {
        //
        // Arrange
        //
        $input = [
            'name' => fake()->word(),
            'symbol' => fake()->currencyCode(),
        ];

        $this->fiatCurrencyRepository()->create(fake()->word(), $input['symbol']);
        $this->assertDatabaseHas('fiat_currencies', ['symbol' => $input['symbol']]);

        //
        // Exception
        //
        $this->expectException(UniqueConstraintViolationException::class);

        //
        // Act
        //
        $this->fiatCurrencyRepository()->create(
            $input['name'],
            $input['symbol'],
        );
    }
}
