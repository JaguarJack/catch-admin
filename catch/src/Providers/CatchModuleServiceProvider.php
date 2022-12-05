<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 ~ now https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace Catch\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class CatchModuleServiceProvider extends ServiceProvider
{
    protected array $events = [];

    /**
     * register
     *
     * @return void
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function boot(): void
    {
        $this->registerModuleRoute();

        foreach ($this->events as $event => $listener) {
            Event::listen($event, $listener);
        }
    }

    /**
     * load module router
     *
     * @return void
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function registerModuleRoute(): void
    {
        $route = $this->app['config']->get('catch.route');

        Route::prefix($route['prefix'])
            ->middleware($route['middlewares'])
            ->group($this->routePath());
    }

    /**
     * route path
     *
     * @return string|array
     */
    abstract protected function routePath(): string | array;
}
