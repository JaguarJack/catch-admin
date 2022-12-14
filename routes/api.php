<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Options\Http\OptionController;

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

Route::get('options/{name}', [OptionController::class, 'index']);

