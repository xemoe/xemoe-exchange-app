<?php

namespace App\Repositories;

use App\Models\CryptoCurrency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Str;

class WalletRepository
{
    private const ADDRESS_HASH_ALGORITHM = 'sha256';
    private const DEFAULT_BALANCE = 0;

    /**
     * @param User $user
     * @param CryptoCurrency $cryptoCurrency
     * @return Wallet
     */
    public static function create(
        User           $user,
        CryptoCurrency $cryptoCurrency
    ): Wallet
    {
        return Wallet::create([
            'user_id' => $user->id,
            'currency_id' => $cryptoCurrency->id,
            'address' => hash(self::ADDRESS_HASH_ALGORITHM, Str::uuid()),
            'balance' => self::DEFAULT_BALANCE,
        ]);
    }

    public static function find(string $id): ?Wallet
    {
        return Wallet::find($id);
    }

    /**
     * Soft delete model instance.
     * @param Wallet $newWallet
     * @return bool
     */
    public static function delete(Wallet $newWallet): bool
    {
        return $newWallet->delete();
    }
}
