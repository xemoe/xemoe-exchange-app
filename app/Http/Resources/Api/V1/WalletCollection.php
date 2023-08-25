<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sumBalance = [];
        foreach ($this->collection as $wallet) {
            $sumBalance[$wallet->currency->symbol] = $wallet->balance;
        }

        return [
            'wallets' => WalletResource::collection($this->collection),
            'balance' => $sumBalance,
        ];
    }
}
