<?php

use Catch\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Common\Http\Controllers\AreaController;
use Modules\Common\Http\Controllers\DemoController;
use Modules\Common\Http\Controllers\LangController;
use Modules\Common\Http\Controllers\OptionController;
use Modules\Common\Http\Controllers\ServerController;
use Modules\Common\Http\Controllers\SettingController;
use Modules\Common\Http\Controllers\UploadController;
use Modules\Permissions\Middlewares\PermissionGate;
use Modules\User\Middlewares\OperatingMiddleware;

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

Route::withoutMiddleware([
    PermissionGate::class,
    OperatingMiddleware::class,
])->get('options/{name}', [OptionController::class, 'index']);

// 配置开启
Route::withoutMiddleware([
    AuthMiddleware::class,
    PermissionGate::class,
    OperatingMiddleware::class,
])->prefix('setting')->group(function () {
    Route::get('config', [SettingController::class, 'config']);
});

// 多语言
Route::withoutMiddleware([
    AuthMiddleware::class,
    PermissionGate::class,
    OperatingMiddleware::class,
])->prefix('lang')->group(function () {
    // 语言
    Route::get('{lang}', [LangController::class, 'translate']);
});

Route::prefix('')->group(function () {
    // 上传
    Route::controller(UploadController::class)->group(function () {
        Route::post('upload/file', 'file')->withoutMiddleware(PermissionGate::class);
        Route::post('upload/image', 'image')->withoutMiddleware(PermissionGate::class);
        // 分片上传
        Route::post('upload/chunk', 'chunk')->withoutMiddleware(PermissionGate::class);
        // 合并分片
        Route::post('upload/merge', 'merge')->withoutMiddleware(PermissionGate::class);
        // 检查上传进度
        Route::get('upload/check', 'checkProgress')->withoutMiddleware(PermissionGate::class);
        // get oss signature
        Route::get('upload/token', 'token');
    });
    // 地区
    Route::controller(AreaController::class)->group(function () {
        Route::get('areas', 'index');
    });
});

Route::get('server/info', [ServerController::class, 'info']);

// demo 路由，可删除
Route::prefix('demo')->group(function () {
    Route::get('exception', [DemoController::class, 'exception']);
    Route::get('dd', [DemoController::class, 'dd']);
});
