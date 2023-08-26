<?php

namespace App\Repositories;

use App\Models\CryptoCurrency;
use App\Models\FiatCurrency;
use App\Models\TradingPair;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class TradingPairRepository
{
    public function createCryptoToCrypto(
        CryptoCurrency $baseCurrency,
        CryptoCurrency $quoteCurrency
    ): TradingPair
    {
        if ($baseCurrency->symbol === $quoteCurrency->symbol) {
            throw new RuntimeException('Base currency and quote currency cannot be the same.');
        }

        return TradingPair::create([
            'base_currency_symbol' => $baseCurrency->symbol,
            'base_currency_type' => CryptoCurrency::class,
            'base_currency_id' => $baseCurrency->id,
            'quote_currency_symbol' => $quoteCurrency->symbol,
            'quote_currency_type' => CryptoCurrency::class,
            'quote_currency_id' => $quoteCurrency->id,
            'pair_symbol' => $baseCurrency->symbol . '/' . $quoteCurrency->symbol,
        ]);
    }

    public function createCryptoToFiat(
        CryptoCurrency $cryptoCurrency,
        FiatCurrency   $fiatCurrency
    ): TradingPair
    {
        return TradingPair::create([
            'base_currency_symbol' => $cryptoCurrency->symbol,
            'base_currency_type' => CryptoCurrency::class,
            'base_currency_id' => $cryptoCurrency->id,
            'quote_currency_symbol' => $fiatCurrency->symbol,
            'quote_currency_type' => FiatCurrency::class,
            'quote_currency_id' => $fiatCurrency->id,
            'pair_symbol' => $cryptoCurrency->symbol . '/' . $fiatCurrency->symbol,
        ]);
    }

    public function createFiatToCrypto(
        FiatCurrency   $fiatCurrency,
        CryptoCurrency $cryptoCurrency
    ): TradingPair
    {
        return TradingPair::create([
            'base_currency_symbol' => $fiatCurrency->symbol,
            'base_currency_type' => FiatCurrency::class,
            'base_currency_id' => $fiatCurrency->id,
            'quote_currency_symbol' => $cryptoCurrency->symbol,
            'quote_currency_type' => CryptoCurrency::class,
            'quote_currency_id' => $cryptoCurrency->id,
            'pair_symbol' => $fiatCurrency->symbol . '/' . $cryptoCurrency->symbol,
        ]);
    }

    public function all(): Collection
    {
        return TradingPair::all();
    }

    public function whereAnyCurrencyType(string $currencyType): Builder
    {
        return TradingPair::query()
            ->where('base_currency_type', $currencyType)
            ->orWhere('quote_currency_type', $currencyType);
    }
}
