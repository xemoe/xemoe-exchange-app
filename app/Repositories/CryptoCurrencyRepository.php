<?php

namespace App\Repositories;

use App\Models\CryptoCurrency;

class CryptoCurrencyRepository
{
    public function create(
        string $name,
        string $symbol
    ): CryptoCurrency
    {
        return CryptoCurrency::create([
            'name' => $name,
            'symbol' => $symbol,
        ]);
    }
}
