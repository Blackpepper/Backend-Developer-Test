<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\Martian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TradeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_martian_flagged_should_not_trade()
    {
        $martian = Martian::create([
            "name" => "Juan Lansangan",
            "age" => 34,
            "gender" => "M",
            "can_trade" => false
        ]);

        $response = $this->post('/api/martians/trade',[
            "martian_id" => $martian->id,
            "items" => [
                1
            ],
            "right_trader" => [
                "martian_id" => 2,
                "items" => [
                    8,9,10
                ]
            ]
        ]);

        $response->assertStatus(400);
    }

    public function test_should_not_trade_to_itself()
    {
        $martian = Martian::create([
            "name" => "Juan Lansangan",
            "age" => 34,
            "gender" => "M",
            "can_trade" => true
        ]);

        $response = $this->post('/api/martians/trade',[
            "martian_id" => $martian->id,
            "items" => [
                ["item_id" => 1, "qty" => 1]
            ],
            "right_trader" => [
                "martian_id" => $martian->id,
                "items" => [
                    ["item_id" => 8, "qty" => 1],
                    ["item_id" => 9, "qty" => 1],
                    ["item_id" => 10, "qty" => 1]
                ]
            ]
        ]);

        $response->assertStatus(400);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Martian should not trade on itself')
            );
    }

    public function test_martian_traders_should_exist()
    {
        
        $response = $this->post('/api/martians/trade',[
            "martian_id" => 1,
            "items" => [
                ["item_id" => 1, "qty" => 1]
            ],
            "right_trader" => [
                "martian_id" => 2,
                "items" => [
                    ["item_id" => 8, "qty" => 1],
                    ["item_id" => 9, "qty" => 1],
                    ["item_id" => 10, "qty" => 1]
                ]
            ]
        ]);

        $response->assertStatus(404);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Martian ID not found')
            );


        $martian = Martian::create([
            "name" => "Juan Lansangan",
            "age" => 34,
            "gender" => "M",
            "can_trade" => true
        ]);

        $response = $this->post('/api/martians/trade',[
            "martian_id" => $martian->id,
            "items" => [
                ["item_id" => 1, "qty" => 1]
            ],
            "right_trader" => [
                "martian_id" => 2,
                "items" => [
                    ["item_id" => 8, "qty" => 1],
                    ["item_id" => 9, "qty" => 1],
                    ["item_id" => 10, "qty" => 1]
                ]
            ]
        ]);

        $response->assertStatus(404);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Martian right trader not exist')
            );
    }

    public function test_martian_traders_should_have_valid_items()
    {
        $martianLeft = Martian::create([
            "name" => "Juan",
            "age" => 34,
            "gender" => "M",
            "can_trade" => true
        ]);

        $martianRight = Martian::create([
            "name" => "Pedro",
            "age" => 34,
            "gender" => "M",
            "can_trade" => true
        ]);

        $response = $this->post('/api/martians/trade',[
            "martian_id" => $martianLeft->id,
            "items" => [
                ["item_id" => 1, "qty" => 1]
            ],
            "right_trader" => [
                "martian_id" => $martianRight->id,
                "items" => [
                    ["item_id" => 8, "qty" => 1],
                    ["item_id" => 9, "qty" => 1],
                    ["item_id" => 10, "qty" => 1]
                ]
            ]
        ]);

        $response->assertStatus(400);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Martian left trader has invalid item')
            );


        $inventory = Inventory::create([
            "martian_id" => $martianLeft->id,
            "name" => "Test Product #{$martianLeft->id}",
            "points" => 6,
            "qty" => 10
        ]);

        $response = $this->post('/api/martians/trade',[
            "martian_id" => $martianLeft->id,
            "items" => [
                ["item_id" => $inventory->id, "qty" => 1],
            ],
            "right_trader" => [
                "martian_id" => $martianRight->id,
                "items" => [
                    ["item_id" => 8, "qty" => 1],
                    ["item_id" => 9, "qty" => 1],
                    ["item_id" => 10, "qty" => 1]
                ]
            ]
        ]);

        $response->assertStatus(400);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Martian right trader has invalid item')
            );
    }

    public function test_martian_traders_should_have_items_with_equal_points()
    {
        Artisan::call('db:seed');

        $martians = Martian::all();
        $martianLeft = $martians->first();
        $martianRight = $martians->last();

        $response = $this->post('/api/martians/trade',[
            "martian_id" => $martianLeft->id,
            "items" => [
                ["item_id" => 1, "qty" => 1]
            ],
            "right_trader" => [
                "martian_id" => $martianRight->id,
                "items" => [
                    ["item_id" => 8, "qty" => 1],
                    ["item_id" => 9, "qty" => 1],
                ]
            ]
        ]);

        $response->assertStatus(400);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Martian trader points not equal')
                     ->etc()
            );
    }

    public function test_martian_traders_can_trade_successful()
    {
        Artisan::call('db:seed');

        $martians = Martian::all();
        $martianLeft = $martians->first();
        $martianRight = $martians->last();

        $response = $this->post('/api/martians/trade',[
            "martian_id" => $martianLeft->id,
            "items" => [
                1
            ],
            "right_trader" => [
                "martian_id" => $martianRight->id,
                "items" => [
                    8,9,10
                ]
            ]
        ]);

        $response->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', 'Martian traded successful')
            );
    }
    
}