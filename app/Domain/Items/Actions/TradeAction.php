<?php

namespace App\Domain\Items\Actions;

use App\Domain\Items\Models\Item;
use App\Domain\Martians\Models\Martian;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TradeAction
{
    public function do(array $data)
    {
        $rules = [
            'trader_1' => ['required', 'array'],
            'trader_2' => ['required', 'array'],
            'trader_1.id' => ['required', 'integer'],
            'trader_2.id' => ['required', 'integer'],
            'trader_1.items' => ['required', 'array'],
            'trader_1.items.*.id' => ['required', 'integer'],
            'trader_1.items.*.quantity' => ['required', 'integer'],
            'trader_2.items' => ['required', 'array'],
            'trader_2.items.*.id' => ['required', 'integer'],
            'trader_2.items.*.quantity' => ['required', 'integer'],
        ];

        $data = validator($data, $rules)->validated();

        if ($data['trader_1']['id'] === $data['trader_2']['id']) {
            throw new BadRequestHttpException('Cannot trade with self.');
        }

        try {
            $trader1 = Martian::findOrFail($data['trader_1']['id']);
            $trader2 = Martian::findOrFail($data['trader_2']['id']);
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException(
                'Martian(s) not found.',
                $exception->getCode(),
                $exception
            );
        }

        $trader1Items = $this->loadAndValidateItems($trader1, $data['trader_1']['items']);
        $trader2Items = $this->loadAndValidateItems($trader2, $data['trader_2']['items']);

        if ($trader1Items->sum('total_points') !== $trader2Items->sum('total_points')) {
            throw new BadRequestHttpException('Total trading item points are not equal.');
        }

        try {
            DB::beginTransaction();
            $this->syncNewItems($trader1, $data['trader_1']['items'], $data['trader_2']['items']);
            $this->syncNewItems($trader2, $data['trader_2']['items'], $data['trader_1']['items']);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return [$trader1->fresh('items'), $trader2->fresh('items')];
    }

    private function loadAndValidateItems(Martian $martian, $data) {
        if (!$martian->can_trade) throw new BadRequestHttpException('Flagged martian(s) cannot trade.');

        $items = $martian->items
            ->whereIn('id', Arr::pluck($data, 'id'))
            ->map(function (Item $item) use ($data) {
                $data = Arr::first(Arr::where($data, fn ($v) => $v['id'] === $item->id));

                $item['total_points'] = $item['points'];

                if (empty($data)) {
                    throw new BadRequestHttpException('Martians cannot trade items they do not own.');
                } else {
                    if ($item->pivot->quantity < $data['quantity']) {
                        throw new BadRequestHttpException('Cannot trade items larger in amount actually owned.');
                    }

                    $item['total_points'] = $item['points'] * $data['quantity'];
                }

                return $item;
            });

        if ($items->isEmpty()) {
            throw new BadRequestHttpException('Martians cannot trade items they do not own.');
        }

        return $items;
    }

    private function syncNewItems(Martian $martian, array $tradeItems, array $forItems) {
        // subtract first the trading items
        $values = $martian->items
            ->whereIn('id', Arr::pluck($tradeItems, 'id'))
            ->mapWithKeys(function (Item $item) {
                return ["$item->id" => ['quantity' => $item->pivot->quantity]];
            })
            ->map(function ($v, $k) use ($tradeItems) {
                $tradeItem = Arr::first(Arr::where($tradeItems, fn ($item) => $item['id'] === $k));
                $v['quantity'] -= $tradeItem['quantity'];
                return $v;
            })
            ->all();

        // add the items to be received
        foreach ($forItems as $forItem) {
            $value = Arr::get($values, strval($forItem['id']));
            if ($value) {
                $value['quantity'] += $forItem['quantity'];
            } else {
                $values[strval($forItem['id'])] = ['quantity' => $forItem['quantity']];
            }
        }

        $syncValues = collect($values)->filter(fn ($v) => $v['quantity'] > 0)->all();
        $detachValues = collect($values)->filter(fn ($v) => $v['quantity'] === 0)->keys()->all();

        $martian->items()->sync($syncValues, false);
        // detach items with zero quantity
        $martian->items()->detach($detachValues);
    }
}
