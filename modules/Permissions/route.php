<?php

use Illuminate\Support\Facades\Route;
use Modules\Permissions\Http\Controllers\RolesController;

Route::prefix('permissions')->group(function () {
    
	Route::apiResource('roles', RolesController::class);
	//next
});

