<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('martians-create', [App\Http\Controllers\Api\MartiansController::class, 'addmartian']);

Route::get('pricetable', [App\Http\Controllers\Api\PriceTableController::class, 'index'])->name('pricetable');

Route::get('martians', [App\Http\Controllers\Api\MartiansController::class, 'index'])->name('martians');

Route::get('martians/{martianid}', [App\Http\Controllers\Api\MartiansController::class, 'show']);

Route::post('martians-trade', [App\Http\Controllers\Api\MartiansController::class, 'trade']);
