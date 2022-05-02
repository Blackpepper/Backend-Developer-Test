<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Martian extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age', 'gender', 'allow_trade'];

    protected $with = ['inventories'];

    public function inventories()
    {
        return $this->belongsToMany(
            TradeItem::class,
            'martian_inventory',
            'martian_id',
            'trade_item_id')
            ->withPivot('qty');

    }
}
