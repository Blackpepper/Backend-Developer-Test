<?php

namespace App\Services;

interface CompareSupplies
{
    /**
     * Compare points of supplies
     *
     * @param array $suppliesComparedFrom
     * @param array $suppliesComparedTo
     * @return bool
     */
    public static function compare(array $suppliesComparedFrom, array $suppliesComparedTo);

    /**
     * Get points of supplies
     *
     * @param array $suppliesData
     * @return int
     */
    public static function getPoints(array $suppliesData);
}
