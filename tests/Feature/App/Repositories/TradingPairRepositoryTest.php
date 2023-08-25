<?php

namespace Tests\Feature\App\Repositories;

use App\Models\TradingPair;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TypeError;

class TradingPairRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * @throws BindingResolutionException
     */
    public function test_createCryptoToCrypto_success(): void
    {
        //
        // Arrange
        //
        $firstCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $secondCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());

        //
        // Act
        //
        $result = $this->tradingPairRepository()->createCryptoToCrypto($firstCurrency, $secondCurrency);

        //
        // Assert
        //
        $this->assertTrue($result->exists);
        $this->assertDatabaseHas('trading_pairs', [
            'base_currency_type' => get_class($firstCurrency),
            'base_currency_id' => $firstCurrency->id,
            'base_currency_symbol' => $firstCurrency->symbol,
            'quote_currency_type' => get_class($secondCurrency),
            'quote_currency_id' => $secondCurrency->id,
            'quote_currency_symbol' => $secondCurrency->symbol,
        ]);

        // Check relationships
        $this->assertEquals($firstCurrency->id, $result->baseCurrency->id);
        $this->assertEquals($secondCurrency->id, $result->quoteCurrency->id);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createCryptoToCrypto_failure_duplicate_pair(): void
    {
        //
        // Arrange
        //
        $firstCurrency = $this->currencyRepository()->create(fake()->word(), fake()->unique()->currencyCode());
        $secondCurrency = $this->currencyRepository()->create(fake()->word(), fake()->unique()->currencyCode());

        // Create the first trading pair
        $this->tradingPairRepository()->createCryptoToCrypto($firstCurrency, $secondCurrency);

        //
        // Exception
        //
        $this->expectException(UniqueConstraintViolationException::class);

        //
        // Act
        //
        $this->tradingPairRepository()->createCryptoToCrypto($firstCurrency, $secondCurrency);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createCryptoToCrypto_failure_wrong_type(): void
    {
        //
        // Arrange
        //
        $firstCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        //
        // Exception
        //
        $this->expectException(TypeError::class);

        //
        // Act
        //
        $this->tradingPairRepository()->createCryptoToCrypto($firstCurrency, $fiatCurrency);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createCryptoToFiat_success(): void
    {
        //
        // Arrange
        //
        $cryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        //
        // Act
        //
        $result = $this->tradingPairRepository()->createCryptoToFiat($cryptoCurrency, $fiatCurrency);

        //
        // Assert
        //
        $this->assertTrue($result->exists);
        $this->assertDatabaseHas('trading_pairs', [
            'base_currency_type' => get_class($cryptoCurrency),
            'base_currency_id' => $cryptoCurrency->id,
            'base_currency_symbol' => $cryptoCurrency->symbol,
            'quote_currency_type' => get_class($fiatCurrency),
            'quote_currency_id' => $fiatCurrency->id,
            'quote_currency_symbol' => $fiatCurrency->symbol,
        ]);

        // Check relationships
        $this->assertEquals($cryptoCurrency->id, $result->baseCurrency->id);
        $this->assertEquals($fiatCurrency->id, $result->quoteCurrency->id);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createCryptoToFiat_failure_duplicate_pair(): void
    {
        //
        // Arrange
        //
        $cryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        // Create the first trading pair
        $this->tradingPairRepository()->createCryptoToFiat($cryptoCurrency, $fiatCurrency);

        //
        // Exception
        //
        $this->expectException(UniqueConstraintViolationException::class);

        //
        // Act
        //
        $this->tradingPairRepository()->createCryptoToFiat($cryptoCurrency, $fiatCurrency);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createCryptoToFiat_failure_wrong_type(): void
    {
        //
        // Arrange
        //
        $cryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        //
        // Exception
        //
        $this->expectException(TypeError::class);

        //
        // Act
        //
        $this->tradingPairRepository()->createCryptoToFiat($fiatCurrency, $cryptoCurrency);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createFiatToCrypto_success(): void
    {
        //
        // Arrange
        //
        $cryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        //
        // Act
        //
        $result = $this->tradingPairRepository()->createFiatToCrypto($fiatCurrency, $cryptoCurrency);

        //
        // Assert
        //
        $this->assertTrue($result->exists);
        $this->assertDatabaseHas('trading_pairs', [
            'base_currency_type' => get_class($fiatCurrency),
            'base_currency_id' => $fiatCurrency->id,
            'base_currency_symbol' => $fiatCurrency->symbol,
            'quote_currency_type' => get_class($cryptoCurrency),
            'quote_currency_id' => $cryptoCurrency->id,
            'quote_currency_symbol' => $cryptoCurrency->symbol,
        ]);

        // Check relationships
        $this->assertEquals($fiatCurrency->id, $result->baseCurrency->id);
        $this->assertEquals($cryptoCurrency->id, $result->quoteCurrency->id);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createFiatToCrypto_failure_duplicate_pair(): void
    {
        //
        // Arrange
        //
        $cryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        // Create the first trading pair
        $this->tradingPairRepository()->createFiatToCrypto($fiatCurrency, $cryptoCurrency);

        //
        // Exception
        //
        $this->expectException(UniqueConstraintViolationException::class);

        //
        // Act
        //
        $this->tradingPairRepository()->createFiatToCrypto($fiatCurrency, $cryptoCurrency);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_createFiatToCrypto_failure_wrong_type(): void
    {
        //
        // Arrange
        //
        $cryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        //
        // Exception
        //
        $this->expectException(TypeError::class);

        //
        // Act
        //
        $this->tradingPairRepository()->createFiatToCrypto($cryptoCurrency, $fiatCurrency);
    }

    /**
     * @throws BindingResolutionException
     */
    public function test_all_success(): void
    {
        //
        // Truncate trading pairs table
        // prevent records from seeding
        //
        TradingPair::truncate();

        //
        // Arrange
        //
        $cryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $secondCryptoCurrency = $this->currencyRepository()->create(fake()->word(), fake()->currencyCode());
        $fiatCurrency = $this->fiatCurrencyRepository()->create(fake()->word(), fake()->currencyCode());

        $this->tradingPairRepository()->createCryptoToCrypto($cryptoCurrency, $secondCryptoCurrency);
        $this->tradingPairRepository()->createCryptoToFiat($cryptoCurrency, $fiatCurrency);
        $this->tradingPairRepository()->createFiatToCrypto($fiatCurrency, $cryptoCurrency);

        //
        // Act
        //
        $result = $this->tradingPairRepository()->all();

        //
        // Assert
        //
        $this->assertCount(3, $result);
    }
}
