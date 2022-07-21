<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventorySupplies;
use App\Http\Resources\InventorySuppliesResource;

class InventorySuppliesController extends Controller
{
    public function index() {

        return InventorySuppliesResource::collection(InventorySupplies::all());
    }

    public function addsupplies($items, $martianid) {
        foreach($items as $key => $val) { 
            if(!empty($val['itemid']) && !empty($val['quantity']) && !empty($martianid)) {
                $inventory_supplies = new InventorySupplies;
                $inventory_supplies->itemid = $val['itemid'];
                $inventory_supplies->quantity = $val['quantity'];
                $inventory_supplies->martianid = $martianid;
                $inventory_supplies->save();
            }
        }
        
        return true;

    }

    public function updateSupplies($trader, $martianid1, $martianid2) {
        foreach($trader as $item) {
            $itemid = $item['itemid'];
            $quantity = $item['quantity'];
            $trader1itemcurrentqty = 0;
            $trader1itemaddqty = 0;

            if($quantity != 0) { 
                // TRADER 1 Inventory
                $trader1_exist_sup = InventorySupplies::where([
                    ['itemid','=',$itemid],
                    ['martianid','=',$martianid1],
                ])
                ->select('itemid', 'quantity')
                ->first();

                $trader1itemidexist = $trader1_exist_sup->itemid ?? '';
                $trader1itemcurrentqty = $trader1_exist_sup->quantity ?? 0;

                $trader1itemaddqty = $trader1itemcurrentqty + $quantity;

                if(!empty($trader1itemidexist)) {
                    $trader1_inventory = InventorySupplies::where([
                            ['itemid','=',$itemid],
                            ['martianid','=',$martianid1],
                        ])
                    ->update(array('quantity' => $trader1itemaddqty));
                } else {
                    $trader1_inventory = new InventorySupplies;
                    $trader1_inventory->itemid = $itemid;
                    $trader1_inventory->quantity = $quantity;
                    $trader1_inventory->martianid = $martianid1;
                    $trader1_inventory->save();
                }

                // TRADER 2 Inventory
                $trader2itemsubqty = 0;
                $trader2currentqty = 0;
                $trader2_inventory = InventorySupplies::where([
                    ['itemid','=',$itemid],
                    ['martianid','=',$martianid2],
                ])
                ->select('itemid', 'quantity')
                ->first();

                $trader2currentqty = $trader2_inventory->quantity;
                $trader2itemsubqty = $trader2currentqty - $quantity;

                $trader2_update_inventory = InventorySupplies::where([
                            ['itemid','=',$itemid],
                            ['martianid','=',$martianid2],
                        ])
                ->update(array('quantity' => $trader2itemsubqty));

            }
        }
    }

    public function tradeInStock($traderArr) {

        $trade1list = [];
        foreach($traderArr['buyFrom']['trader1'] as $items) {
            $martianid = $traderArr['buyFrom']['martianid'];
            $itemid = $items['itemid'];
            $quantity = $items['quantity'];

            $trader1_sup = InventorySupplies::where([
                ['itemid','=',$itemid],
                ['martianid','=',$martianid],
            ])
            ->select('quantity')
            ->first();

            $trader1currentqty = $trader1_sup->quantity;

            $stockItemStatus = ($trader1currentqty >= $quantity) ? 1 : 0;
            $trade1list[] = $stockItemStatus;
        }
        $trade1StockStatus = (in_array(0, $trade1list)) ? 0 : 1;

        $trade2list = [];
        foreach($traderArr['sellTo']['trader2'] as $items) {
            $martianid = $traderArr['sellTo']['martianid'];
            $itemid = $items['itemid'];
            $quantity = $items['quantity'];

            $trader2_sup = InventorySupplies::where([
                ['itemid','=',$itemid],
                ['martianid','=',$martianid],
            ])
            ->select('quantity')
            ->first();

            $trader2currentqty = $trader2_sup->quantity;

            $stockItemStatus = ($trader2currentqty >= $quantity) ? 1 : 0;
            $trade2list[] = $stockItemStatus;
        }
        $trade2StockStatus = (in_array(0, $trade2list)) ? 0 : 1;

        $res = (($trade1StockStatus && $trade2StockStatus) == 1) ? 1 : 0;

        return $res;
    }
}
