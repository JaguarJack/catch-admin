<?php

namespace Modules\Permissions\Providers;

use Catch\CatchAdmin;
use Catch\Providers\CatchModuleServiceProvider;

class PermissionsServiceProvider extends CatchModuleServiceProvider
{
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
