<?php

namespace Modules\Permissions\Providers;

use Catch\CatchAdmin;
use Catch\Providers\CatchModuleServiceProvider;
use Modules\Permissions\Middlewares\PermissionGate;

class PermissionsServiceProvider extends CatchModuleServiceProvider
{
    /**
     * register permission gate
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */

    protected function registering()
    {
        $route = $this->app['config']->get('catch.route');

        $route['middlewares'][] = PermissionGate::class;

        $this->app['config']->set('catch.route', $route);
    }

    /**
     * route path
     *
     * @return string|array
     */
    public function routePath(): string|array
    {
        // TODO: Implement path() method.
        return CatchAdmin::getModuleRoutePath('Permissions');
    }
}
