<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Martian extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age', 'gender', 'allow_trade'];

    protected $with = ['inventories'];

    /**
     * A martian has many trade items(inventories)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inventories()
    {
        return $this->belongsToMany(
            TradeItem::class,
            'martian_inventory',
            'martian_id',
            'trade_item_id')
            ->withPivot('qty');

    }

    /**
     * check if martian own the trade item
     *
     * @param TradeItem $tradeItem
     * @return Collection|Model|null
     */
    public function hasTradeItem(TradeItem $tradeItem)
    {
        return $this->inventories->find($tradeItem->id);
    }

    /**
     * check if martian has enough qty trade item to exchange
     *
     * @param TradeItem $tradeItem
     * @param int $qty
     * @return bool
     */
    public function hasEnoughTradeItem(TradeItem $tradeItem, int $qty)
    {
        foreach ($this->inventories as $inventory) {
            if ($inventory->id == $tradeItem->id && $inventory->pivot->qty >= $qty) {
                return true;
            }
        }

        return false;
    }

    /**
     * get the qty of required trade item for exchange trade items
     *
     * @param Collection $sourceTradeItems source trade items
     * @param TradeItem $destTradeItem dest trade item
     * @return float|int
     * @throws \Exception
     */
    public function calculateRequiredQtyOfExchangeTradeItems(Collection $sourceTradeItems, TradeItem $destTradeItem)
    {
        $totalValue = $this->getTotalValueOfTradeItems($sourceTradeItems);

        $requiredQty = $destTradeItem->calculateQtyToTrade($totalValue);

        return $requiredQty;
    }

    /**
     * convert array date to collection for trade items
     *
     * @param array $tradeItems
     * @return Collection
     */
    public function transformArrayTradeItemsToCollection(array $tradeItems)
    {
        $tradeItemCollection = new Collection();
        foreach ($tradeItems as $data) {
            /** @var TradeItem $fromTradeItem */
            $tradeItem = TradeItem::where('name', $data['name'])->firstOrFail();
            $tradeItem->qty = $data['qty'];

            $tradeItemCollection->add($tradeItem);
        }

        return $tradeItemCollection;
    }

    /**
     * get total value of trade items
     *
     * @param Collection $tradeItems
     * @return float|int
     */
    public function getTotalValueOfTradeItems(Collection $tradeItems)
    {
        $value = 0;
        foreach ($tradeItems as $tradeItem) {
            $value += $tradeItem->points * $tradeItem->qty;
        }

        return $value;
    }

    /**
     * update multiple trade item inventory
     *
     * @param Collection $tradeItems
     */
    public function updateInventories(Collection $tradeItems)
    {
        foreach ($this->inventories as $i) {
            foreach ($tradeItems as $tradeItem) {
                if ($i->id == $tradeItem->id) {
                    $i->pivot->qty = $i->pivot->qty - $tradeItem->qty;
                }
            }
        }
    }

    /**
     * update single inventory
     *
     * @param TradeItem $tradeItem
     * @param $qty
     */
    public function updateSingleInventory(TradeItem $tradeItem, $qty)
    {
        foreach ($this->inventories as $i) {
            if ($i->id == $tradeItem->id) {
                $i->pivot->qty = $i->pivot->qty - $qty;
            }
        }

    }

}
