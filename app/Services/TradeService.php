<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Martian;
use Illuminate\Support\Facades\Log;

class TradeService implements Trade
{
    /**
     * Trade supplies between martians
     *
     * @param array $suppliesData
     * @param array $suppliesDataOfTrader
     * @return bool
     */
    public static function trade(array $suppliesData, array $suppliesDataOfTrader)
    {
        try {
            // from martian to trader
            self::doTrade($suppliesData['martian_id'], $suppliesDataOfTrader['martian_id'], $suppliesData['supplies']);

            // from trader to martian
            self::doTrade($suppliesDataOfTrader['martian_id'], $suppliesData['martian_id'], $suppliesDataOfTrader['supplies']);

            return true;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            dd($ex->getTraceAsString());
            return false;
        }
    }

    /**
     * Take off supplies from martian and add those to trader
     *
     * @param int $martianID
     * @param int $traderID
     * @param array $supplies
     * @return bool
     */
    public static function doTrade(int $martianID, int $traderID, array $suppliesData)
    {
        foreach ($suppliesData as $supplyData) {
            $quantity = (int)$supplyData['quantity'];
            // take off supply from martian
            $inventory = Inventory::where([['martian_id', $martianID], ['supply_id', $supplyData['id']]])->first();
            if ($inventory->supply_quantity === $quantity) {
                // remove inventory if the quantity of the supply is same with it for trade
                $inventory->delete();
            } else {
                $inventory->update(['supply_quantity' => ($inventory->supply_quantity - $quantity)]);
            }

            // add supply to trader
            $inventoryOfTrader = Inventory::where([['martian_id', $traderID], ['supply_id', $supplyData['id']]])->first();
            if ($inventoryOfTrader) {
                // update quantity
                $inventoryOfTrader->update(['supply_quantity' => ($inventoryOfTrader->supply_quantity + $quantity)]);
            } else {
                // add
                Inventory::create(['martian_id' => $traderID, 'supply_id' => $supplyData['id'], 'supply_quantity' => $quantity]);
            }
        }
    }
}
