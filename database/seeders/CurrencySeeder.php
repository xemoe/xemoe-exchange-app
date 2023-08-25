<?php

namespace Database\Seeders;

use App\Models\CryptoCurrency;
use App\Models\FiatCurrency;
use App\Repositories\CurrencyRepository;
use App\Repositories\FiatCurrencyRepository;
use App\Repositories\TradingPairRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        $this->command->newLine();

        if (CryptoCurrency::count() > 0) {
            $this->command->info('  <error> WARNING </error>');
            $this->command->info('  CryptoCurrency table is not empty, skipping...');
            $this->command->newLine();
            return;
        }

        $this->command->info('  Creating default CryptoCurrency...');
        $this->createDefaultCryptoCurrencies();
        $this->command->newLine();

        $this->command->info('  Creating default FiatCurrency...');
        $this->createDefaultFiatCurrencies();
        $this->command->newLine();

        $this->command->info('  Creating default TradingPairs...');
        $this->createDefaultTradingPairs();
        $this->command->newLine();
    }

    private function createDefaultCryptoCurrencies(): void
    {
        $seedCurrencies = [
            ['name' => 'Bitcoin', 'symbol' => 'BTC'],
            ['name' => 'Ethereum', 'symbol' => 'ETH'],
            ['name' => 'Ripple', 'symbol' => 'XRP'],
            ['name' => 'Doge coin', 'symbol' => 'DOGE'],
            ['name' => 'Binance - BNB', 'symbol' => 'BNB'],
            ['name' => 'Binance - BUSD', 'symbol' => 'BUSD'],
        ];

        foreach ($seedCurrencies as $currency) {
            CryptoCurrency::create($currency);
            $this->command->warn('  Name: ' . $currency['name'] . ' Symbol: ' . $currency['symbol']);
        }
    }

    private function createDefaultFiatCurrencies(): void
    {
        $fiatCurrencies = [
            ['name' => 'US Dollar', 'symbol' => 'USD'],
            ['name' => 'Euro', 'symbol' => 'EUR'],
            ['name' => 'Thai Baht', 'symbol' => 'THB'],
        ];

        foreach ($fiatCurrencies as $currency) {
            FiatCurrency::create($currency);
            $this->command->warn('  Name: ' . $currency['name'] . ' Symbol: ' . $currency['symbol']);
        }
    }

    /**
     * @throws BindingResolutionException
     */
    private function createDefaultTradingPairs(): void
    {
        /** @var TradingPairRepository $repository */
        $repository = app()->make(TradingPairRepository::class);

        /** @var CurrencyRepository $currencyRepository */
        $currencyRepository = app()->make(CurrencyRepository::class);

        /** @var FiatCurrencyRepository $fiatCurrencyRepository */
        $fiatCurrencyRepository = app()->make(FiatCurrencyRepository::class);

        // CryptoToCrypto
        $cryptoToCryptoPairs = [
            ['BTC', 'ETH'],
            ['BTC', 'BUSD'],
            ['ETH', 'BTC'],
            ['ETH', 'BUSD'],
            ['BUSD', 'BTC'],
            ['BUSD', 'ETH'],
        ];

        foreach ($cryptoToCryptoPairs as $pair) {
            $tradingPair = $repository->createCryptoToCrypto(
                $currencyRepository->findBySymbol($pair[0]),
                $currencyRepository->findBySymbol($pair[1])
            );

            $this->command->warn('  C2C Pairs: ' . $tradingPair->pair_symbol);
        }

        // FiatToCrypto
        $fiatToCryptoPairs = [
            ['USD', 'BTC'],
            ['USD', 'ETH'],
            ['USD', 'BUSD'],
            ['EUR', 'BTC'],
            ['EUR', 'ETH'],
            ['EUR', 'BUSD'],
            ['THB', 'BTC'],
            ['THB', 'ETH'],
            ['THB', 'BUSD'],
        ];

        foreach ($fiatToCryptoPairs as $pair) {
            $tradingPair = $repository->createFiatToCrypto(
                $fiatCurrencyRepository->findBySymbol($pair[0]),
                $currencyRepository->findBySymbol($pair[1])
            );

            $this->command->warn('  F2C Pairs: ' . $tradingPair->pair_symbol);
        }

        // CryptoToFiat
        $cryptoToFiatPairs = [
            ['BTC', 'USD'],
            ['BTC', 'EUR'],
            ['BTC', 'THB'],
            ['ETH', 'USD'],
            ['ETH', 'EUR'],
            ['ETH', 'THB'],
            ['BUSD', 'USD'],
            ['BUSD', 'EUR'],
            ['BUSD', 'THB'],
        ];

        foreach ($cryptoToFiatPairs as $pair) {
            $tradingPair = $repository->createCryptoToFiat(
                $currencyRepository->findBySymbol($pair[0]),
                $fiatCurrencyRepository->findBySymbol($pair[1])
            );

            $this->command->warn('  C2F Pairs: ' . $tradingPair->pair_symbol);
        }
    }
}
