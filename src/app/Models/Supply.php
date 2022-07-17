<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilterSupply(Builder $builder, string $search): Builder
    {
        return $builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%');
        });
    }

    public function scopeAvailableSupplies(Builder $builder, int $sellerId, string $supply): Builder
    {
        return $builder->where('martian_id', '!=', $sellerId)
            ->where('name', '!=', $supply)
            ->where('quantity', '>', 0);
    }
}
