<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TradingPairCollection;
use App\Models\TradingPair;
use App\Services\TradingPairService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FiatTradingPairController extends BaseController
{
    public function __construct(
        private readonly TradingPairService $tradingPairService
    )
    {
    }

    public function index(Request $request): Response|JsonResponse
    {
        return $this->sendResponse(
            new TradingPairCollection($this->tradingPairService->getFiatPairsList([
                'id',
                'base_currency_symbol',
                'quote_currency_symbol',
                'pair_symbol',
            ])),
            __('Trading pair retrieved successfully.')
        );
    }
}
