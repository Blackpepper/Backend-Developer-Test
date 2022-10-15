<?php

namespace App\Domain\Items\Actions;

use App\Domain\Items\Models\Item;
use Illuminate\Validation\Rule;

class UpsertItemAction
{
    protected $rules = [
        'name' => 'required|string|unique:items,name',
        'points' => 'required|integer'
    ];

    public function do(array $data, ?Item $item = null)
    {
        $requiredIfNullItem = Rule::requiredIf(is_null($item));

        $rules = [
            'name' => [$requiredIfNullItem, 'string', Rule::unique('items', 'name')],
            'points' => ['required', 'integer']
        ];

        $data = validator($data, $rules)->validate();

        if ($item) {
            $item->fill($data)->save();
        } else {
            $item = Item::create($data);
        }

        return $item;
    }
}
