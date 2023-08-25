<?php

namespace Database\Seeders;

use App\Models\CryptoCurrency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->newLine();

        if (CryptoCurrency::count() > 0) {
            $this->command->info('  <error> WARNING </error>');
            $this->command->info('  CryptoCurrency table is not empty, skipping...');
            $this->command->newLine();
            return;
        }

        $seedCurrency = [
            ['name' => 'Bitcoin', 'symbol' => 'BTC'],
            ['name' => 'Ethereum', 'symbol' => 'ETH'],
            ['name' => 'Ripple', 'symbol' => 'XRP'],
            ['name' => 'Doge coin', 'symbol' => 'DOGE'],
            ['name' => 'Binance - BNB', 'symbol' => 'BNB'],
            ['name' => 'Binance - BUSD', 'symbol' => 'BUSD'],
        ];

        $this->command->info('  Creating default CryptoCurrency...');

        foreach ($seedCurrency as $currency) {
            CryptoCurrency::create($currency);
            $this->command->warn('  Name: ' . $currency['name'] . ' Symbol: ' . $currency['symbol']);
        }

        $this->command->newLine();
    }
}
