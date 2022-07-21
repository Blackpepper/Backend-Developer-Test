<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PriceTable;
use App\Http\Resources\PriceTableResources;

class PriceTableController extends Controller
{
    public function index() {
        return PriceTableResources::collection(PriceTable::all());
    }
}
