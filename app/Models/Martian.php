<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Martian extends Model
{
    use HasFactory;

    public $table = 'martians';
    protected $guarded = ['id'];
    protected $fillable = ['name', 'age', 'gender', 'trade'];

    public static $relation = ['inventories', 'inventories.supply'];

    /**
     * inventories relation
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'martian_id', 'id');
    }
}
