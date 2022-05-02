<?php

namespace Tests\Feature;

use App\Models\Martian;
use App\Models\TradeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MartianTest extends TestCase
{
    use WithFaker;

    /**
     * test if create martian successfully
     */
    public function testMartianCreatedSuccessfully()
    {
        $martianData = [
            'name' => $this->faker->name,
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Water',
                    'qty' => 5
                ]
            ]
        ];

        $this->json('POST', 'api/martians', $martianData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'age',
                    'gender',
                    'inventory',
                    'allow_trade',
                ]
            ]);
    }

    /**
     * test if update martian successfully
     */
    public function testMartianUpdatedSuccessfully()
    {
        $martianData = [
            'name' => $this->faker->name,
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Water',
                    'qty' => 5
                ]
            ]
        ];

        $martian = Martian::create($martianData);

        // add inventory to martian
        foreach ($martianData['inventory'] as $i) {
            $tradeItem = TradeItem::where('name', $i['name'])->first();

            // check if inventory exist
            $existInventory = DB::table('martian_inventory')->where([
                'martian_id' => $martian->id,
                'trade_item_id' => $tradeItem->id
            ])->first();

            if (!$existInventory) {
                $martian->inventories()->attach($tradeItem->id, [
                    'qty' => $i['qty']
                ]);
            }
        }

        // martian payload
        $payload = [
            'name' => $this->faker->name,
            'age' => 10,
            'gender' => 'f',
            'allow_trade' => true
        ];

        // inventory result data
        $inventoryAssertData = [];
        foreach($martian->inventories as $i) {
            $inventoryAssertData[] = [
                'id' => $i->id,
                'name' => $i->name,
                'points' => $i->points,
                'qty' => $i->pivot->qty
            ];
        }

        $this->json('PUT', 'api/martians/'. $martian->id, $payload, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => $martian->id,
                    'name' => $payload['name'],
                    'age' => $payload['age'],
                    'gender' => $payload['gender'],
                    'allow_trade' => $payload['allow_trade'],
                    'inventory' => $inventoryAssertData
                ]
            ]);
    }


    /**
     * test if only update  martian allow trade successfully
     */
    public function testOnlyUpdateMartianAllowTradeSuccessfully()
    {
        $martianData = [
            'name' => $this->faker->name,
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Water',
                    'qty' => 5
                ]
            ]
        ];

        $martian = Martian::create($martianData);

        // add inventory to martian
        foreach ($martianData['inventory'] as $i) {
            $tradeItem = TradeItem::where('name', $i['name'])->first();

            // check if inventory exist
            $existInventory = DB::table('martian_inventory')->where([
                'martian_id' => $martian->id,
                'trade_item_id' => $tradeItem->id
            ])->first();

            if (!$existInventory) {
                $martian->inventories()->attach($tradeItem->id, [
                    'qty' => $i['qty']
                ]);
            }
        }

        // martian payload
        $payload = [
            'allow_trade' => true
        ];

        // inventory result data
        $inventoryAssertData = [];
        foreach($martian->inventories as $i) {
            $inventoryAssertData[] = [
                'id' => $i->id,
                'name' => $i->name,
                'points' => $i->points,
                'qty' => $i->pivot->qty
            ];
        }

        $this->json('PATCH', 'api/martians/'. $martian->id, $payload, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => $martian->id,
                    'name' => $martianData['name'],
                    'age' => $martianData['age'],
                    'gender' => $martianData['gender'],
                    'allow_trade' => $payload['allow_trade'],
                    'inventory' => $inventoryAssertData
                ]
            ]);
    }


}
