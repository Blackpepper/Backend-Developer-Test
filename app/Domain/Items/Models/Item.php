<?php

namespace App\Domain\Items\Models;

use App\Domain\Martians\Models\Martian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'points'
    ];

    public function items() : BelongsToMany
    {
        return $this->belongsToMany(Martian::class, 'martians_x_items');
    }
}
