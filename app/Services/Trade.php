<?php

namespace App\Services;

interface Trade
{
    /**
     * Trade supplies between martians
     *
     * @param array $suppliesData
     * @param array $suppliesDataOfTrader
     * @return bool
     */
    public static function trade(array $suppliesData, array $suppliesDataOfTrader);

    /**
     * Take off supplies from martian and add those to trader
     *
     * @param int $martianID
     * @param int $traderID
     * @param array $suppliesData
     * @return bool
     */
    public static function doTrade(int $martianID, int $traderID, array $suppliesData);
}
