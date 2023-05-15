<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\DictionaryController;
use Modules\System\Http\Controllers\DictionaryValuesController;

Route::prefix('system')->group(function(){

	Route::apiResource('dictionary', DictionaryController::class);
    Route::put('dictionary/enable/{id}', [DictionaryController::class, 'enable']);

	Route::apiResource('dic/values', DictionaryValuesController::class);
    Route::put('dic/values/enable/{id}', [DictionaryValuesController::class, 'enable']);

    //next
});

