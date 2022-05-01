<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public $table = 'inventories';
    protected $guarded = ['id'];
    protected $fillable = ['martian_id', 'supply_id', 'supply_quantity'];

    /**
     * Supply relation
     */
    public function supply()
    {
        return $this->hasOne(Supply::class, 'id', 'supply_id');
    }
}
