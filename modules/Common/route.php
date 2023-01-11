<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\Http\Controllers\OptionController;
use Modules\Common\Http\Controllers\UploadController;

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

Route::post('upload/file', [UploadController::class, 'file']);

Route::post('upload/image', [UploadController::class, 'image']);
