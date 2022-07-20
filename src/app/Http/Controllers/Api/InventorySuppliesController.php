<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventorySupplies;

class InventorySuppliesController extends Controller
{
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
}
