<?php

use App\Http\Controllers\MartianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('martians', MartianController::class);
