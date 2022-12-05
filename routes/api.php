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

Route::get('/user/info', function (Request $request) {
    return [
        'id' => 1,
        'nickname' => 'catchadmin',
        'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhZG1pbl9pZCI6MSwiYXVkIjoiIiwiZXhwIjoxNjg0ODA2MzIxLCJpYXQiOjE2NTg4ODYzMjEsImlzcyI6IiIsImp0aSI6ImI4ZjZlMzM4ZjM1MDg2OWJiZjIwNjE4OTA4OTk0ODMzIiwibmJmIjoxNjU4ODg2MzIxLCJzdWIiOiIifQ.ZJinC0JvY6OhJjr1GJgMaYk2qie8U_2W55L_I2AIBHk',

    ];
});

