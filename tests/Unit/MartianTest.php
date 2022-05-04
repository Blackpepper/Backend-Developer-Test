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
            'name' => $faker->name(),
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
            'name' => $faker->name(),
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
     * test if martian has enough qty for given trade item
     */
    public function testHasEnoughTradeItem()
    {
        $faker = \Faker\Factory::create();

        $martianData = [
            'name' => $faker->name(),
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

        /** @var Martian $martian */
        $martian = $this->newMartian($martianData);

        $tradeItem = TradeItem::where('name', 'Oxygen')->firstOrFail();

        $this->assertTrue($martian->hasEnoughTradeItem($tradeItem, 5));

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

    /**
     * test if update inventories successfully
     */
    public function testUpdateInventoriesSuccessfully()
    {
        $faker = \Faker\Factory::create();

        $martianData = [
            'name' => $faker->name(),
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Oxygen',
                    'qty' => 5,
                ],
                [
                    'name' => 'Medication',
                    'qty' => 5,
                ]
            ]
        ];

        /** @var Martian $martian */
        $martian = $this->newMartian($martianData);

        $tradeItems = $martian->transformArrayTradeItemsToCollection([
            [
                'name' => 'Oxygen',
                'qty'  => 3,
            ],
            [
                'name' => 'Medication',
                'qty'  => 1,
            ],
        ]);

        $martian->updateInventories($tradeItems);

        $exptecedQty = 2;
        $actualQty = null;
        foreach($martian->inventories as $i) {
            if ($i->name == 'Oxygen') {
                $actualQty = $i->pivot->qty;
                break;
            }
        }

        $this->assertEquals($exptecedQty, $actualQty);

    }

    /**
     * test if update single inventory successfully
     */
    public function testUpdateSingleInventorySuccessfully()
    {
        $faker = \Faker\Factory::create();

        $martianData = [
            'name' => $faker->name(),
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Oxygen',
                    'qty' => 5,
                ],
                [
                    'name' => 'Medication',
                    'qty' => 5,
                ]
            ]
        ];

        /** @var Martian $martian */
        $martian = $this->newMartian($martianData);

        $tradeItem = TradeItem::where('name','Oxygen')->firstOrFail();

        $martian->updateSingleInventory($tradeItem, 3);

        $exptecedQty = 2;
        $actualQty = null;
        foreach($martian->inventories as $i) {
            if ($i->name == 'Oxygen') {
                $actualQty = $i->pivot->qty;
                break;
            }
        }

        $this->assertEquals($exptecedQty, $actualQty);

    }
}
