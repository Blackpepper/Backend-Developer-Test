<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTable extends Model
{
    use HasFactory;

    protected $primaryKey = 'itemid';
    protected $table = 'price_table';
    protected $fillable = [
        'itemid',
        'name',
        'points',
    ];
}
