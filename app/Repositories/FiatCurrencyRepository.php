<?php

namespace App\Repositories;

use App\Models\FiatCurrency;
use Illuminate\Database\Eloquent\Collection;

class FiatCurrencyRepository
{
    /**
     * @param string $name
     * @param string $symbol
     * @return FiatCurrency
     */
    public static function create(
        string $name,
        string $symbol
    ): FiatCurrency
    {
        return FiatCurrency::create([
            'name' => $name,
            'symbol' => $symbol,
        ]);
    }

    /**
     * @return Collection
     */
    public static function all(): Collection
    {
        return FiatCurrency::all();
    }

    /**
     * Create model instance.
     * @param string $word
     * @param string $currencyCode
     * @return FiatCurrency
     */
    public static function make(string $word, string $currencyCode): FiatCurrency
    {
        return FiatCurrency::make([
            'name' => $word,
            'symbol' => $currencyCode,
        ]);
    }

    /**
     * @param string $symbol
     * @return FiatCurrency|null
     */
    public static function findBySymbol(string $symbol): ?FiatCurrency
    {
        return FiatCurrency::where(['symbol' => $symbol])->first();
    }
}
