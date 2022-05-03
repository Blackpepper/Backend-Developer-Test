<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeItem extends Model
{
    use HasFactory;

    public function calculateQtyToTrade(int $totalValue)
    {
        if ($totalValue % $this->points == 0) {
            return $totalValue / $this->points;
        }

        throw new \Exception('Unable to trade with this item');
    }
}
