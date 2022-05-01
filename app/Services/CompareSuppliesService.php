<?php

namespace App\Services;

use App\Models\Supply;

class CompareSuppliesService implements CompareSupplies
{
    /**
     * Compare points of supplies
     *
     * @param array $suppliesData
     * @param array $suppliesDataOfTrader
     * @return bool
     */
    public static function compare(array $suppliesData, array $suppliesDataOfTrader)
    {
        // get points of supplies
        $points = self::getPoints($suppliesData);

        // get points of supplies of trader
        $pointsOfTrader = self::getPoints($suppliesDataOfTrader);

        return $points === $pointsOfTrader;
    }

    /**
     * Get points of supplies
     *
     * @param array $suppliesData
     * @return int
     */
    public static function getPoints(array $suppliesData)
    {
        $points = 0;
        foreach ($suppliesData as $supplyData) {
            $supply = Supply::find($supplyData['id']);
            if ($supply) {
                $points += ($supply->point * $supplyData['quantity']);
            }
        }
        return $points;
    }
}
