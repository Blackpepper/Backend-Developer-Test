<?php

namespace App\Support;

use App\Models\Martian;

class MartianSupport
{
    public static function cannotTrade(Martian $martian): bool
    {
        return $martian->can_trade === 0;
    }
}
