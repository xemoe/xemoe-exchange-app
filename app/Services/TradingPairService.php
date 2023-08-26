<?php

namespace App\Services;

use App\Models\FiatCurrency;
use App\Models\TradingPair;
use App\Repositories\CurrencyRepository;
use App\Repositories\FiatCurrencyRepository;
use App\Repositories\TradingPairRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class TradingPairService
{
    public function __construct(
        private readonly TradingPairRepository  $tradingPairRepository,
        private readonly CurrencyRepository     $currencyRepository,
        private readonly FiatCurrencyRepository $fiatCurrencyRepository,
    )
    {
    }

    /**
     * Get all trading pairs list with fiat currency
     * @param array $select
     * @param array $sortSymbols
     * @return Collection
     */
    public function getFiatPairsList(array $select = [], array $sortSymbols = []): Collection
    {
        $q = $this->tradingPairRepository
            ->whereAnyCurrencyType(FiatCurrency::class);

        if (!empty($sortSymbols)) {
            $q->orderByRaw("FIELD(base_currency_symbol, '" . implode("','", $sortSymbols) . "') DESC");
            $q->orderByRaw("FIELD(quote_currency_symbol, '" . implode("','", $sortSymbols) . "') DESC");
        } else {
            $q->orderBy('pair_symbol', 'DESC');
        }

        if (!empty($select)) {
            $q->select($select);
        }

        return $q->get();
    }

    /**
     * Add crypto to fiat trading pair by symbol
     * @param string $baseCurrencySymbol
     * @param string $quoteCurrencySymbol
     * @return MessageBag|TradingPair
     */
    public function addCryptoToFiatPairsBySymbol(
        string $baseCurrencySymbol,
        string $quoteCurrencySymbol
    ): MessageBag|TradingPair
    {
        $validator = Validator::make([
            'base_currency_symbol' => $baseCurrencySymbol,
            'quote_currency_symbol' => $quoteCurrencySymbol,
        ], [
            'base_currency_symbol' => ['required', 'string', 'exists:crypto_currencies,symbol'],
            'quote_currency_symbol' => ['required', 'string', 'exists:fiat_currencies,symbol'],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $this->tradingPairRepository->createCryptoToFiat(
            $this->currencyRepository->findBySymbol($baseCurrencySymbol),
            $this->fiatCurrencyRepository->findBySymbol($quoteCurrencySymbol)
        );
    }

    /**
     * Add fiat to crypto trading pair by symbol
     * @param string $baseCurrencySymbol
     * @param string $quoteCurrencySymbol
     * @return MessageBag|TradingPair
     */
    public function addFiatToCryptoBySymbol(
        string $baseCurrencySymbol,
        string $quoteCurrencySymbol
    ): MessageBag|TradingPair
    {
        $validator = Validator::make([
            'base_currency_symbol' => $baseCurrencySymbol,
            'quote_currency_symbol' => $quoteCurrencySymbol,
        ], [
            'base_currency_symbol' => ['required', 'string', 'exists:fiat_currencies,symbol'],
            'quote_currency_symbol' => ['required', 'string', 'exists:crypto_currencies,symbol'],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $this->tradingPairRepository->createFiatToCrypto(
            $this->fiatCurrencyRepository->findBySymbol($baseCurrencySymbol),
            $this->currencyRepository->findBySymbol($quoteCurrencySymbol)
        );
    }
}
