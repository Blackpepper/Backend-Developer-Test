<?php

namespace Tests\Unit\Services;

use App\Services\TradeService;
use PHPUnit\Framework\TestCase;

class TradeTest extends TestCase
{
    /**
     * Test trading
     *
     * @return void
     */
    public function test_that_trading_supplies_is_true()
    {
        $suppliesData = [
            'martian_id' => 1,
            'supplies' => [
                ['id' => 1, 'quantity' => 2] // Oxygen - total 12 points
            ]
        ];
        $suppliesDataOfTrader = [
            'martian_id' => 2,
            'supplies' => [
                ['id' => 2, 'quantity' => 1], // Water - 4 points
                ['id' => 3, 'quantity' => 2], // Food - total 6 points
                ['id' => 4, 'quantity' => 1] // Medication - 2 points
            ]
        ];
        $tradeResult = TradeService::trade($suppliesData, $suppliesDataOfTrader);
        $this->assertTrue($tradeResult);
    }
}
