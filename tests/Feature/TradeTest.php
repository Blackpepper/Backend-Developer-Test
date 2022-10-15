<?php

namespace Tests\Feature;

use App\Domain\Martians\Models\Martian;
use Tests\TestCase;

class TradeTest extends TestCase
{
    public function test_error_when_trade_invalid_input()
    {
        $data = [
            'trader_1' => [
                'id' => 'string',
                'items' => [
                    ['id' => 'string', 'quantity' => 'string']
                ]
            ],
            'trader_2' => [
                'id' => 'string'
            ]
        ];

        $this->json('POST', '/api/trade', $data)
            ->assertStatus(422);
    }

    public function test_error_when_trade_with_self()
    {
        $data = [
            'trader_1' => [
                'id' => 1,
                'items' => [
                    ['id' => 1, 'quantity' => 3]
                ]
            ],
            'trader_2' => [
                'id' => 1,
                'items' => [
                    ['id' => 1, 'quantity' => 3]
                ]
            ]
        ];

        $response = $this->json('POST', '/api/trade', $data)
            ->assertStatus(400);

        $this->assertStringContainsString(
            'Cannot trade with self.',
            $response->content()
        );
    }

    public function test_error_when_missing_martian()
    {
        $martian = Martian::first();
        $martian->delete();

        $data = [
            'trader_1' => [
                'id' => $martian->id,
                'items' => [
                    ['id' => 1, 'quantity' => 3]
                ]
            ],
            'trader_2' => [
                'id' => 2,
                'items' => [
                    ['id' => 1, 'quantity' => 3]
                ]
            ]
        ];

        $response = $this->json('POST', '/api/trade', $data)
            ->assertStatus(404);

        $this->assertStringContainsString(
            'Martian(s) not found.',
            $response->content()
        );
    }

    public function test_error_when_unequal_total_trading_points()
    {
        // create a martian and give 3 oxygen
        $trader1 = Martian::factory()->createOne(['can_trade' => true]);
        $trader1->items()->sync([1 => ['quantity' => 3]]);

        // create a martian and give 3 food
        $trader2 = Martian::factory()->createOne(['can_trade' => true]);
        $trader2->items()->sync([3 => ['quantity' => 3]]);

        $data = [
            'trader_1' => [
                'id' => $trader1->id,
                'items' => [
                    ['id' => 1, 'quantity' => 3]
                ]
            ],
            'trader_2' => [
                'id' => $trader2->id,
                'items' => [
                    ['id' => 3, 'quantity' => 3]
                ]
            ]
        ];

        $response = $this->json('POST', '/api/trade', $data)
            ->assertStatus(400);

        $this->assertStringContainsString(
            'Total trading item points are not equal.',
            $response->content()
        );
    }

    public function test_error_when_flagged_martians_trade()
    {
        // create a martian and give 1 oxygen
        $trader1 = Martian::factory()->createOne(['can_trade' => false]);
        $trader1->items()->sync([1 => ['quantity' => 1]]);

        // create a martian and give 2 food
        $trader2 = Martian::factory()->createOne(['can_trade' => true]);
        $trader2->items()->sync([3 => ['quantity' => 2]]);

        $data = [
            'trader_1' => [
                'id' => $trader1->id,
                'items' => [
                    ['id' => 1, 'quantity' => 1]
                ]
            ],
            'trader_2' => [
                'id' => $trader2->id,
                'items' => [
                    ['id' => 3, 'quantity' => 2]
                ]
            ]
        ];

        $response = $this->json('POST', '/api/trade', $data)
            ->assertStatus(400);

        $this->assertStringContainsString(
            'Flagged martian(s) cannot trade.',
            $response->content()
        );
    }

    public function test_error_when_martians_trade_they_dont_own()
    {
        // create a martian and give 1 oxygen
        $trader1 = Martian::factory()->createOne(['can_trade' => true]);
        $trader1->items()->sync([1 => ['quantity' => 1]]);

        // create a martian and give 2 food
        $trader2 = Martian::factory()->createOne(['can_trade' => true]);
        $trader2->items()->sync([3 => ['quantity' => 2]]);

        $data = [
            'trader_1' => [
                'id' => $trader1->id,
                'items' => [
                    ['id' => 2, 'quantity' => 1]
                ]
            ],
            'trader_2' => [
                'id' => $trader2->id,
                'items' => [
                    ['id' => 3, 'quantity' => 2]
                ]
            ]
        ];

        $response = $this->json('POST', '/api/trade', $data)
            ->assertStatus(400);

        $this->assertStringContainsString(
            'Martians cannot trade items they do not own.',
            $response->content()
        );
    }

    public function test_error_when_martians_trade_larger_than_actually_owned()
    {
        // create a martian and give 1 oxygen
        $trader1 = Martian::factory()->createOne(['can_trade' => true]);
        $trader1->items()->sync([1 => ['quantity' => 1]]);

        // create a martian and give 2 food
        $trader2 = Martian::factory()->createOne(['can_trade' => true]);
        $trader2->items()->sync([3 => ['quantity' => 2]]);

        $data = [
            'trader_1' => [
                'id' => $trader1->id,
                'items' => [
                    ['id' => 1, 'quantity' => 3]
                ]
            ],
            'trader_2' => [
                'id' => $trader2->id,
                'items' => [
                    ['id' => 3, 'quantity' => 2]
                ]
            ]
        ];

        $response = $this->json('POST', '/api/trade', $data)
            ->assertStatus(400);

        $this->assertStringContainsString(
            'Cannot trade items larger in amount actually owned.',
            $response->content()
        );
    }

    public function test_trade_successful()
    {
        // create a martian and give 1 oxygen
        $trader1 = Martian::factory()->createOne(['can_trade' => true]);
        $trader1->items()->sync([1 => ['quantity' => 1]]);

        // create a martian and give 2 food
        $trader2 = Martian::factory()->createOne(['can_trade' => true]);
        $trader2->items()->sync([3 => ['quantity' => 2]]);

        $data = [
            'trader_1' => [
                'id' => $trader1->id,
                'items' => [
                    ['id' => 1, 'quantity' => 1]
                ]
            ],
            'trader_2' => [
                'id' => $trader2->id,
                'items' => [
                    ['id' => 3, 'quantity' => 2]
                ]
            ]
        ];

        $this->json('POST', '/api/trade', $data)
            ->assertStatus(200);
    }
}
