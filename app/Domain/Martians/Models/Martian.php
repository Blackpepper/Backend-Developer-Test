<?php

namespace App\Domain\Martians\Models;

use App\Domain\Items\Models\Item;
use Database\Factories\MartianFactory;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\Access\Authorizable as AuthorizableTrait;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Martian extends Model implements Authenticatable, Authorizable
{
    use AuthenticatableTrait,
        AuthorizableTrait,
        HasFactory;

    protected $casts = [
        'can_trade' => 'boolean'
    ];

    protected $fillable = [
        'name',
        'age',
        'gender',
        'can_trade'
    ];

    protected static function newFactory()
    {
        return MartianFactory::new();
    }

    public function items() : BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'martians_x_items')->withPivot('quantity');
    }
}
