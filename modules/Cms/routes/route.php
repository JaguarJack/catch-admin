<?php

use Illuminate\Support\Facades\Route;
use Modules\Cms\Http\Controllers\CategoryController;

Route::prefix('cms')->group(function(){
    
	Route::apiResource('category', CategoryController::class);
	//next
});
