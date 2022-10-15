<?php

namespace App\Domain\Martians\Actions;

use App\Domain\Martians\Models\Martian;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UpsertMartianAction
{
    public function do(array $data, ?Martian $martian = null)
    {
        $requiredIfNullMartian = Rule::requiredIf(is_null($martian));

        $rules = [
            'name' => [$requiredIfNullMartian, 'string'],
            'gender' => [$requiredIfNullMartian, 'string', Rule::in(['Male', 'Female', 'Non-binary'])],
            'age' => [$requiredIfNullMartian, 'integer'],
            'can_trade' => [$requiredIfNullMartian, 'boolean'],
            'items' => 'array',
            'items.*.id' => 'required|integer|exists:items,id',
            'items.*.quantity' => 'required|integer'
        ];

        $data = validator($data, $rules)->validate();

        if ($martian) {
            $martian->fill($data)->save();
        } else {
            $martian = Martian::create($data);
        }

        $syncValues = collect(Arr::get($data, 'items'))
            ->mapWithKeys(function ($item) {
                return [$item['id'] => ['quantity' => $item['quantity']]];
            });

        if ($syncValues->isNotEmpty()) {
            if ($martian && !$martian->can_trade) {
                throw new ConflictHttpException('Flagged martians cannot update their inventory.');
            }

            $martian->items()->sync($syncValues->all(), false);
        }

        return $martian->load('items');
    }
}
