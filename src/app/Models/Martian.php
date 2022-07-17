<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Martian extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilterName(Builder $builder, string $search): Builder
    {
        return $builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('name', 'LIKE', '%' . $search . '%');
        });
    }

    public function supplies(): HasMany
    {
        return $this->hasMany(Supply::class, 'martian_id');
    }
}
