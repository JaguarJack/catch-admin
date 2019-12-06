<?php
declare (strict_types = 1);

namespace catcher\event;

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

        $router->group(function () use ($router) {
            include CatchAdmin::getRoutes();
        });
    }
}
