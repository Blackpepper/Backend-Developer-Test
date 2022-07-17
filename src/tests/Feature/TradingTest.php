<?php

namespace Tests\Feature;

use App\Exceptions\NotEnoughSupplyException;
use App\Models\Martian;
use App\Models\Supply;
use App\Support\SupplySupport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TradingTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/trades';

    public function setup(): void
    {
        parent::setUp();
    }

    public function test_martian_has_successful_trading()
    {
        $buyer = Martian::factory()->create();
        collect(SupplySupport::allowedSupplies())
            ->keys()
            ->each(
                fn ($supply) => Supply::factory()
                    ->state(
                        [
                            'martian_id' => $buyer->id,
                            'name' => $supply,
                            'quantity' => 100
                        ]
                    )
                    ->create()
            );

        $seller = Martian::factory()->create();
        Supply::factory()->state(['martian_id' => $seller->id, 'name' => 'Oxygen', 'quantity' => 1])->create();
        Supply::factory()->state(['martian_id' => $seller->id, 'name' => 'Food', 'quantity' => 2])->create();

        $response = $this->json(
            'POST',
            $this->url,
            [
                'seller' => [
                    'id' => $seller->id,
                    'supplies' => [
                        [
                            'supply' => 'Food',
                            'quantity' => 2
                        ],
                        [
                            'supply' => 'Oxygen',
                            'quantity' => 1
                        ]
                    ]
                ],
                'buyer' => [
                    'id' => $buyer->id
                ]
            ]
        );

        $response->assertStatus(200)->assertJsonStructure(['data' => []]);
    }
}
