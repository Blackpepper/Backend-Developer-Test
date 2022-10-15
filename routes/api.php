<?php

use App\Http\API\Items\Controllers\ItemController;
use App\Http\API\Martians\Controllers\MartianController;
use App\Http\API\Trading\Controllers\TradeController;
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

Route::prefix('/items')->group(function () {

    Route::get('/', [ItemController::class, 'all']);

    Route::post('/', [ItemController::class, 'store']);

    Route::put('/{item}', [ItemController::class, 'update']);

});

Route::prefix('/martians')->group(function () {

    Route::get('/', [MartianController::class, 'all']);

    Route::post('/', [MartianController::class, 'store']);

    Route::put('/{martian}', [MartianController::class, 'update']);

});

Route::post('/trade', [TradeController::class, '__invoke']);
