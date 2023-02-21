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

Route::controller(UploadController::class)->group(function (){
    Route::post('upload/file',  'file');
    Route::post('upload/image', 'image');
    // get oss signature
    Route::get('upload/oss', 'oss');
});



