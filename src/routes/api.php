<?php

use App\Http\Controllers\MartianController;
use App\Http\Controllers\SupplyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('martians', MartianController::class);
Route::apiResource('supplies', SupplyController::class);
