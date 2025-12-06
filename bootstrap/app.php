<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Catch\Support\HttpKernel;
use Illuminate\Foundation\Http\Kernel;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// 接管自带的 Http Kernel
// 这个接管类不会带来任何影响，只是忽略了 terminating 异常，可以从日志查看[terminating log]
if (class_exists(HttpKernel::class)) {
    $app->singleton(Kernel::class, HttpKernel::class);
}

return $app;
