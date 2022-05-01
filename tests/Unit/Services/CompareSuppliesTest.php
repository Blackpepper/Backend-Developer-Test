<?php

namespace Tests\Unit\Services;

use App\Services\CompareSuppliesService;
use PHPUnit\Framework\TestCase;

class CompareSuppliesTest extends TestCase
{
    /**
     * Test comparing supplies
     *
     * @return void
     */
    public function test_that_comparing_supplies_is_true()
    {
        $supplies = [
            ['id' => 1, 'quantity' => 2] // Oxygen - total 12 points
        ];
        $suppliesOfTrader = [
            ['id' => 2, 'quantity' => 1], // Water - 4 points
            ['id' => 3, 'quantity' => 2], // Food - total 6 points
            ['id' => 4, 'quantity' => 1] // Medication - 2 points
        ];
        $compareResult = CompareSuppliesService::compare($supplies, $suppliesOfTrader);
        $this->assertTrue($compareResult);
    }

    /**
     * Test comparing supplies that have different points
     *
     * @return void
     */
    public function test_that_comparing_supplies_that_have_different_points_is_false()
    {
        $supplies = [
            ['id' => 1, 'quantity' => 2] // Oxygen - total 12 points
        ];
        $suppliesOfTrader = [
            ['id' => 2, 'quantity' => 1], // Water - 4 points
            ['id' => 3, 'quantity' => 2], // Food - total 6 points
        ];
        $compareResult = CompareSuppliesService::compare($supplies, $suppliesOfTrader);
        $this->assertFalse($compareResult);
    }
}
