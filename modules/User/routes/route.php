<?php

use Catch\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Permissions\Middlewares\PermissionGate;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;

// login route
Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(config('catch.route.middlewares'));
Route::post('logout', [AuthController::class, 'logout'])->withoutMiddleware([PermissionGate::class]);
Route::get('sms/code', [AuthController::class, 'loginSmsCode'])->withoutMiddleware(config('catch.route.middlewares'));
Route::get('auth/wechat', [AuthController::class, 'wechat'])->withoutMiddleware(config('catch.route.middlewares'));
// Route::get('auth/wechat/callback', [AuthController::class, 'wechatCallback'])->withoutMiddleware(config('catch.route.middlewares'));
// 验证码路由
Route::get('auth/captcha', [AuthController::class, 'captcha'])->withoutMiddleware([
    PermissionGate::class,
    AuthMiddleware::class,
]);
// users route
Route::apiResource('users', UserController::class);
Route::put('users/enable/{id}', [UserController::class, 'enable']);
Route::match(['post', 'get'], 'user/online', [UserController::class, 'online']);
Route::get('user/login/log', [UserController::class, 'loginLog']);
Route::get('user/operate/log', [UserController::class, 'operateLog']);
Route::get('user/export', [UserController::class, 'export']);
// 用户导出
Route::post('user/import', [UserController::class, 'import']);
// 回收站恢复
Route::put('users/restore/{id}', [UserController::class, 'restore']);
