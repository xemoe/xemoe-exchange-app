<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FiatTradingPairsEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_get_fiat_trading_pairs_failure_no_authorized(): void
    {
        $response = $this->get(route('api.v1.fiat.trading_pairs.index'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
