<?php

namespace Tests\Unit;

use App\Models\Martian;
use App\Models\TradeItem;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MartianTest extends TestCase
{
    use WithFaker;

    /**
     * test required qty of exchanged trade item for specified collection of trade items
     *
     * @throws \Exception
     */
    public function testCalculateRequiredQtyOfExchangeTradeItems()
    {
        $faker = \Faker\Factory::create();

        $martianData = [
            'name' => $faker->firstname(),
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Oxygen',
                    'qty' => rand(5,10),
                ],
                [
                    'name' => 'Medication',
                    'qty' => rand(5,10),
                ]
            ]
        ];

        /** @var Martian $fromMartian */
        $fromMartian = $this->newMartian($martianData);

        $martianData = [
            'name' => $faker->firstname(),
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Water',
                    'qty' => rand(5,10),
                ],
                [
                    'name' => 'Clothing',
                    'qty' => rand(5,10),
                ]
            ]
        ];

        /** @var Martian $toMartian */
        $toMartian = $this->newMartian($martianData);

        $tradeItems = $fromMartian->transformArrayTradeItemsToCollection([
            [
                'name' => 'Oxygen',
                'qty' => '1',
            ],
            [
                'name' => 'Medication',
                'qty' => '1',
            ]
        ]);

        $toTradeItem = TradeItem::where('name', 'Water')->firstOrFail();

        // 1 Oxygen + 1 Medication = 2 Water
        $requiredQty = $fromMartian->calculateRequiredQtyOfExchangeTradeItems($tradeItems, $toTradeItem);

        $this->assertEquals(2, $requiredQty);
    }

    /**
     * prepare new margin data for testing
     *
     * @param array $inventory
     * @return mixed
     */
    public function newMartian(array $martianData)
    {
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

        return $martian;
    }
}
