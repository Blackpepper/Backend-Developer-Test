<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Martians extends Model
{
    use HasFactory;

    protected $primaryKey = 'martianid';
    protected $fillable = [
        'name',
        'age',
        'gender',
        'allow',
    ];

    public function inventorysupplies() {
        return $this->hasMany(InventorySupplies::class, 'martianid');
    }
}
