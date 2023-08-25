<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\WalletCollection;
use App\Http\Resources\Api\V1\WalletResource;
use App\Services\WalletService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class WalletController extends BaseController
{
    public function index(Request $request): Response|JsonResponse
    {
        return $this->sendResponse(
            new WalletCollection($request->user()->wallets),
            __('Wallet retrieved successfully.')
        );
    }

    /**
     * @throws BindingResolutionException
     */
    public function store(Request $request): Response|JsonResponse
    {
        $wallet = app()->make(WalletService::class)->addBySymbol([
            'user_id' => $request->user()->id,
            'symbol' => $request->input('symbol'),
        ]);

        if (get_class($wallet) === MessageBag::class) {
            return $this->sendError(
                __('Validation error.'),
                $wallet->toArray(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->sendResponse(
            new WalletResource($wallet),
            __('Wallet created successfully.'),
            ResponseAlias::HTTP_CREATED
        );
    }

    /**
     * @throws BindingResolutionException
     */
    public function destroy(Request $request, string $id): Response|JsonResponse
    {
        $result = app()->make(WalletService::class)->removeById([
            'user_id' => $request->user()->id,
            'wallet_id' => $id,
        ]);

        if (is_object($result) && get_class($result) === MessageBag::class) {
            return $this->sendError(
                __('Validation error.'),
                $result->toArray(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->sendResponse(
            [],
            __('Wallet deleted successfully.')
        );
    }
}
