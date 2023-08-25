<?php

namespace App\Services;

use App\Models\Wallet;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class WalletService
{
    public function __construct(
        private readonly WalletRepository   $walletRepository,
        private readonly UserRepository     $userRepository,
        private readonly CurrencyRepository $currencyRepository
    )
    {
    }

    /**
     * @param array $input
     * @return MessageBag|Wallet
     */
    public function addBySymbol(array $input): MessageBag|Wallet
    {
        $validator = Validator::make($input, [
            'user_id' => 'required|exists:users,id',
            'symbol' => 'required|min:3|max:255|exists:crypto_currencies,symbol',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $this->walletRepository::create(
            $this->userRepository->find($input['user_id']),
            $this->currencyRepository->findBySymbol($input['symbol'])
        );
    }

    /**
     * @param array $input
     * @return bool|MessageBag
     */
    public function removeById(array $input): bool|MessageBag
    {
        $validator = Validator::make($input, [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'wallet_id' => [
                'required', 'exists:wallets,id,deleted_at,NULL',
                function ($attribute, $value, $fail) use ($input) {
                    $wallet = $this->walletRepository->find($value);
                    if ($wallet && $wallet->user_id !== $input['user_id']) {
                        $fail('Wallet does not belong to user.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $this->walletRepository::delete(
            $this->walletRepository->find($input['wallet_id'])
        );
    }
}
