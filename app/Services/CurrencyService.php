<?php

namespace App\Services;

use App\Models\CryptoCurrency;
use App\Repositories\CurrencyRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class CurrencyService
{
    public function __construct(
        private readonly CurrencyRepository $currencyRepository
    )
    {
    }

    /**
     * Validate and add a new currency.
     * @param array $input
     * @return MessageBag|CryptoCurrency
     */
    public function add(array $input): MessageBag|CryptoCurrency
    {
        $validator = Validator::make($input, [
            'name' => 'required|min:3|max:255',
            'symbol' => 'required|min:3|max:255|unique:crypto_currencies',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $this->currencyRepository->create($input['name'], $input['symbol']);
    }

    /**
     * Get all currency symbols.
     * @return array
     */
    public function symbols(): array
    {
        return $this->currencyRepository->all()->pluck('symbol')->toArray();
    }
}
