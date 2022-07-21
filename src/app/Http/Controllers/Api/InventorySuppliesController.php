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
}
