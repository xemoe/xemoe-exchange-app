<?php

namespace Tests\Feature\App\Services;

use App\Models\TradingPair;
use App\Services\TradingPairService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TradingPairServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function tradingPairService(): TradingPairService
    {
        return $this->app->make(TradingPairService::class);
    }

    public function test_addCryptoToFiatPairsBySymbol_success(): void
    {
        //
        // Arrange
        //
        $expected = [
            'base_currency_symbol' => 'BUSD',
            'quote_currency_symbol' => 'USD',
            'pair_symbol' => 'BUSD/USD',
        ];

        // Truncate seed trading_pairs
        TradingPair::truncate();
        $this->assertDatabaseMissing('trading_pairs', $expected);

        //
        // Act
        //
        $actual = $this->tradingPairService()->addCryptoToFiatPairsBySymbol('BUSD', 'USD');

        //
        // Assert
        //
        $this->assertTrue($actual->exists);
        $this->assertDatabaseHas('trading_pairs', $expected);
    }

    public function test_addFiatToCryptoBySymbol_success(): void
    {
        //
        // Arrange
        //
        $expected = [
            'base_currency_symbol' => 'USD',
            'quote_currency_symbol' => 'BUSD',
            'pair_symbol' => 'USD/BUSD',
        ];

        // Truncate seed trading_pairs
        TradingPair::truncate();
        $this->assertDatabaseMissing('trading_pairs', $expected);

        //
        // Act
        //
        $actual = $this->tradingPairService()->addFiatToCryptoBySymbol('USD', 'BUSD');

        //
        // Assert
        //
        $this->assertTrue($actual->exists);
        $this->assertDatabaseHas('trading_pairs', $expected);
    }

    public function test_getFiatPairsList_success_while_empty(): void
    {
        // Truncate seed trading_pairs
        TradingPair::truncate();

        //
        // Arrange
        //
        $expected = [];

        //
        // Act
        //
        $actual = $this->tradingPairService()->getFiatPairsList();

        //
        // Assert
        //
        $this->assertEquals($expected, $actual->toArray());
    }

    public function test_getFiatPairsList_success(): void
    {
        //
        // Arrange
        //
        $expected = [
            [
                'base_currency_symbol' => 'BUSD',
                'quote_currency_symbol' => 'USD',
                'pair_symbol' => 'BUSD/USD',
            ],
            [
                'base_currency_symbol' => 'BUSD',
                'quote_currency_symbol' => 'THB',
                'pair_symbol' => 'BUSD/THB',
            ],
            [
                'base_currency_symbol' => 'USD',
                'quote_currency_symbol' => 'BUSD',
                'pair_symbol' => 'USD/BUSD',
            ],
            [
                'base_currency_symbol' => 'THB',
                'quote_currency_symbol' => 'BUSD',
                'pair_symbol' => 'THB/BUSD',
            ],
            [
                'base_currency_symbol' => 'ETH',
                'quote_currency_symbol' => 'USD',
                'pair_symbol' => 'ETH/USD',
            ],
            [
                'base_currency_symbol' => 'USD',
                'quote_currency_symbol' => 'ETH',
                'pair_symbol' => 'USD/ETH',
            ],
        ];

        // Create seed trading_pairs
        $this->tradingPairService()->addCryptoToFiatPairsBySymbol('BUSD', 'USD');
        $this->tradingPairService()->addCryptoToFiatPairsBySymbol('ETH', 'USD');
        $this->tradingPairService()->addCryptoToFiatPairsBySymbol('BUSD', 'THB');
        $this->tradingPairService()->addFiatToCryptoBySymbol('USD', 'BUSD');
        $this->tradingPairService()->addFiatToCryptoBySymbol('USD', 'ETH');
        $this->tradingPairService()->addFiatToCryptoBySymbol('THB', 'BUSD');

        //
        // Act
        //
        $actual = $this->tradingPairService()->getFiatPairsList(
            ['base_currency_symbol', 'quote_currency_symbol', 'pair_symbol'],
            ['BUSD']
        ); // prioritize BUSD

        //
        // Assert
        //
        $this->assertEquals(
            $expected,
            $actual->toArray()
        );
    }
}
