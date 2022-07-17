<?php

namespace App\Services;

use App\Models\Martian;

class TradingService
{
    private array $tradingResult = [];

    public function trade(Martian $buyer, Martian $seller, array $data = [])
    {
        $sellerSupplies = collect($data['seller']['supplies']);
    }
}
