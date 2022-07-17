<?php

namespace App\Services;

use App\Models\Martian;
use App\Support\SupplySupport;
use Illuminate\Support\Collection;

class TradingService
{
    private array $tradingResult = [];

    public function trade(Martian $buyer, Martian $seller, array $data = []): array
    {
        $sellerSupplies = collect($data['seller']['supplies']);

        $supplies = $sellerSupplies->pluck('supply')->all();

        self::tradeSupply(
            self::countSellerPoints($sellerSupplies),
            SupplySupport::cleanSupplies($buyer, $supplies)
        );

        $data = collect($this->tradingResult)
            ->flatten()
            ->countBy()
            ->all();

        self::updateBuyerSupplies($buyer, $data, $sellerSupplies);

        self::updateSellerSupplies($seller, $data, $sellerSupplies);

        return $data;
    }

    private function tradeSupply(int $totalPoints, array $allowedSupply = null): void
    {
        $points = SupplySupport::allowedPoints($allowedSupply);

        for ($i = count($points) - 1; $i >= 0; $i--) {

            while ($totalPoints >= $points[$i]) {

                $totalPoints -= $points[$i];

                array_push($this->tradingResult, SupplySupport::getSupply($points[$i]));
            }
        }
    }

    private function updateSellerSupplies(Martian $martian, array $data, Collection $sellerSupplies)
    {
        foreach ($data as $key => $value) {

            if ($supply = $martian->supplies->firstWhere('name', '=', $value)) {
                $supply->update(['quantity' => ($supply->quantity - $key)]);
            }
        }

        foreach ($sellerSupplies as $data) {

            $supply = $martian->supplies->firstWhere('name', '=', $data['supply']);

            if (!$supply) continue;

            $supply->update(['quantity' => ($supply->quantity + $data['quantity'])]);
        }
    }

    private function updateBuyerSupplies(Martian $martian, array $data, Collection $sellerSupplies): void
    {
        foreach ($data as $key => $value) {

            if ($supply = $martian->supplies->firstWhere('name', '=', $value)) {
                $supply->update(['quantity' => ($supply->quantity + $key)]);
            }
        }

        foreach ($sellerSupplies as $data) {

            $supply = $martian->supplies->firstWhere('name', '=', $data['supply']);

            if (!$supply) continue;

            $supply->update(['quantity' => ($supply->quantity - $data['quantity'])]);
        }
    }

    private function countSellerPoints($sellerSupplies)
    {
        $total = 0;

        foreach ($sellerSupplies as $supply) {
            $total += (SupplySupport::allowedSupplies()[$supply['supply']] * $supply['quantity']);
        }

        return $total;
    }
}
