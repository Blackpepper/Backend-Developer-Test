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

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => []]);
    }

    public function test_it_will_throw_an_exception()
    {
        $buyer = Martian::factory()->create();
        $seller = Martian::factory()->create();

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

        $response->assertStatus(401)
            ->assertSee('Oopps! Insufficient Supply.');

        $this->assertInstanceOf(NotEnoughSupplyException::class, $response->exception);
    }

    public function test_martian_did_not_success_to_his_trading()
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
                            'quantity' => 1
                        ]
                    )
                    ->create()
            );

        $seller = Martian::factory()->create();
        Supply::factory()->create(['martian_id' => $seller->id, 'name' => 'Oxygen', 'quantity' => 1]);
        Supply::factory()->create(['martian_id' => $seller->id, 'name' => 'Clothing', 'quantity' => 2]);

        $response = $this->json(
            'POST',
            $this->url,
            [
                'seller' => [
                    'id' => $seller->id,
                    'supplies' => [
                        [
                            'supply' => 'Clothing',
                            'quantity' => 1
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

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => []]);
    }
}
