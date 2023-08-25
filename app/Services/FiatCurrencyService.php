<?php

namespace App\Services;

use App\Models\FiatCurrency;
use App\Repositories\FiatCurrencyRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class FiatCurrencyService
{
    public function __construct(
        private readonly FiatCurrencyRepository $fiatCurrencyRepository
    )
    {
    }

    /**
     * Validate and add a new currency.
     * @param array $input
     * @return MessageBag|FiatCurrency
     */
    public function add(array $input): MessageBag|FiatCurrency
    {
        $validator = Validator::make($input, [
            'name' => 'required|min:3|max:255',
            'symbol' => 'required|min:3|max:255|unique:fiat_currencies',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $this->fiatCurrencyRepository->create($input['name'], $input['symbol']);
    }

    /**
     * Get all fiat currency symbols.
     * @return array
     */
    public function symbols(): array
    {
        return $this->fiatCurrencyRepository->all()->pluck('symbol')->toArray();
    }
}
