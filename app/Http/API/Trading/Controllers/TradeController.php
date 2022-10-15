<?php

namespace App\Http\API\Trading\Controllers;

use App\Domain\Items\Actions\TradeAction;
use App\Http\BaseController;

/**
 * @group Trading
 *
 * API for trading
 */
class TradeController extends BaseController
{
    /**
     * Perform a trade between two martians.
     *
     * @bodyParam trader_1 object required The first trader.
     * @bodyParam trader_2 object required The second trader.
     * @bodyParam trader_1.id int required The ID of the first trader. Example: 1
     * @bodyParam trader_2.id int required The ID of the second trader. Example: 2
     * @bodyParam trader_1.items object[] required The trading items of the first trader.
     * @bodyParam trader_2.items object[] required The trading items of the second trader.
     * @bodyParam trader_1.items[].id int required ID of a trading item. Example: 1
     * @bodyParam trader_1.items[].quantity int required Quantity of a trading item. Example: 1
     * @bodyParam trader_2.items[].id int required ID of a trading item. Example: 3
     * @bodyParam trader_2.items[].quantity int required Quantity of a trading item. Example: 2
     */
    public function __invoke(TradeAction $action)
    {
        return responder()->success($action->do(request()->input()))->respond(200);
    }
}
