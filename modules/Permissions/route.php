<?php

use Illuminate\Support\Facades\Route;
use Modules\Permissions\Http\Controllers\RolesController;
use Modules\Permissions\Http\Controllers\JobsController;

Route::prefix('permissions')->group(function () {
    Route::apiResource('roles', RolesController::class);
    
	Route::apiResource('jobs', JobsController::class);
	//next
});

