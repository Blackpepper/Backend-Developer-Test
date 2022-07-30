<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MartianController;
use App\Http\Controllers\TradeController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('test', function() {
    return 'test route';
});

// martian routes
Route::get('martians', [MartianController::class, 'index']);
Route::get('martians/{id}', [MartianController::class, 'show']);
Route::put('martians/{id}', [MartianController::class, 'update']);
Route::post('martians', [MartianController::class, 'store']);


// inventory routes
Route::get('inventories', [InventoryController::class, 'index']);
Route::get('inventories/{id}', [InventoryController::class, 'show']);
Route::put('inventories/{id}', [InventoryController::class, 'update']);
Route::patch('inventories/{id}', [InventoryController::class, 'updateInventoryStocks']);
Route::post('inventories', [InventoryController::class, 'store']);

// trading routes
Route::post('trade', [TradeController::class, 'store']);
