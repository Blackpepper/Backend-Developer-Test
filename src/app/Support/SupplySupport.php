<?php

namespace App\Support;

use App\Models\Martian;

class SupplySupport
{
    private const ITEM_OXYGEN = 'Oxygen';
    private const ITEM_WATER = 'Water';
    private const ITEM_FOOD = 'Food';
    private const ITEM_MEDICATION = 'Medication';
    private const ITEM_CLOTHING = 'Clothing';

    private const ALLOWED_SUPPLIES = [
        self::ITEM_CLOTHING => 1,
        self::ITEM_MEDICATION => 2,
        self::ITEM_FOOD => 3,
        self::ITEM_WATER => 4,
        self::ITEM_OXYGEN => 6,
    ];

    public static function allowedSupplies(): array
    {
        return self::ALLOWED_SUPPLIES;
    }

    public static function isValidSupply(string $supply): bool
    {
        return collect(self::allowedSupplies())
            ->keys()
            ->contains($supply);
    }

    public static function allowedPoints(array $available = []): array
    {
        return collect(self::ALLOWED_SUPPLIES)
            ->only($available)
            ->sort()
            ->values()
            ->all();
    }

    public static function cleanSupplies(Martian $martian, array $supplies): array
    {
        return $martian->supplies
            ->reject(fn ($dbSupply) => collect($supplies)->contains($dbSupply->name))
            ->reject(fn ($dbSupply) => $dbSupply->quantity <= 0)
            ->pluck('name')
            ->unique()
            ->all();
    }

    public static function getSupply(int $points): string
    {
        return collect(self::allowedSupplies())
            ->flip()
            ->all()[$points];
    }
}
