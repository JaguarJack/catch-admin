<?php

namespace Modules\Common\Providers;

use Catch\CatchAdmin;
use Catch\Providers\CatchModuleServiceProvider;
use Modules\User\Events\Login;
use Modules\User\Listeners\Login as LoginListener;
use Modules\User\Middlewares\OperatingMiddleware;

class CommonServiceProvider extends CatchModuleServiceProvider
{
    /**
     * route path
     *
     * @return string|array
     */
    public function moduleName(): string|array
    {
        // TODO: Implement path() method.
        return 'common';
    }
}
