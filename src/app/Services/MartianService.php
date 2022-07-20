<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Martians;
use App\Models\PriceTable;


class MartianService
{
    public function allowedToTrade($martianid) {

        $martian = Martians::where([
            ['martianid','=',$martianid],
            ['allow','=',1],
        ])
        ->select('martianid')
        ->get();

        $allowed = ($martian->count() == 1) ? 1 : 0;

        return $allowed;
    }

    public function tradeMatchPoints($trader1points=[], $trader2points=[]) {

        $trade1totalpoints = 0;
        foreach($trader1points as $item) {
            $itemid = $item['itemid'];
            $PriceTable = PriceTable::find($itemid);

            $trade1totalpoints += $item['quantity'] * $PriceTable->points;
            
        }

        $trade2totalpoints = 0;
        foreach($trader2points as $item) {
            $itemid = $item['itemid'];
            $PriceTable = PriceTable::find($itemid);

            $trade2totalpoints += $item['quantity'] * $PriceTable->points;
            
        }

        $ret = ($trade1totalpoints == $trade2totalpoints) ? 1 : 0;

        return $ret;
    }
}

