<?php

namespace App\Repositories;

use App\Models\CryptoCurrency;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRepository
{
    /**
     * @param string $name
     * @param string $symbol
     * @return CryptoCurrency
     */
    public static function create(
        string $name,
        string $symbol
    ): CryptoCurrency
    {
        return CryptoCurrency::create([
            'name' => $name,
            'symbol' => $symbol,
        ]);
    }

    /**
     * @return Collection
     */
    public static function all(): Collection
    {
        return CryptoCurrency::all();
    }

    /**
     * Create model instance.
     * @param string $word
     * @param string $currencyCode
     * @return CryptoCurrency
     */
    public static function make(string $word, string $currencyCode): CryptoCurrency
    {
        return CryptoCurrency::make([
            'name' => $word,
            'symbol' => $currencyCode,
        ]);
    }

    /**
     * @param string $symbol
     * @return CryptoCurrency|null
     */
    public static function findBySymbol(string $symbol): ?CryptoCurrency
    {
        return CryptoCurrency::where(['symbol' => $symbol])->first();
    }
}
