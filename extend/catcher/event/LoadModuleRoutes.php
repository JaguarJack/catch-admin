<?php
declare (strict_types = 1);

namespace catcher\event;

use catchAdmin\permissions\PermissionsMiddleware;
use catchAdmin\user\AuthTokenMiddleware;
use catcher\CatchAdmin;
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

        $routes = CatchAdmin::getRoutes();

        $routeMiddleware = config('catch.route_middleware');

        if ($domain) {
            $router->domain($domain, function () use ($router, $routes) {
                foreach ($routes as $route) {
                    include $route;
                }
            })->middleware($routeMiddleware);
        } else {
            $router->group(function () use ($router, $routes) {
                foreach ($routes as $route) {
                    include $route;
                }
            })->middleware($routeMiddleware);
        }

        // 单独加载登录
        include CatchAdmin::moduleDirectory('login') . 'route.php';
    }
}
