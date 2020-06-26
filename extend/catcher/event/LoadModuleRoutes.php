<?php
declare (strict_types = 1);

namespace catcher\event;

use think\App;
use think\Route;

class LoadModuleRoutes
{
    /**
     * 处理
     *
     * @time 2019年11月29日
     * @return void
     */
    public function handle(): void
    {
        $router = app(Route::class);
        $domain = config('catch.domain');
        $paths = app(App::class)->make('routePath')->get();
        // $routeMiddleware = config('catch.route_middleware');
        if ($domain) {
            $router->domain($domain, function () use ($router, $paths) {
                foreach ($paths as $path) {
                    include $path;
                }
            });
        } else {
            $router->group(function () use ($router, $paths) {
                foreach ($paths as $path) {
                    include $path;
                }
            });
        }
    }
}
