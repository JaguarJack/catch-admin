<?php

namespace Modules\System\Providers;

use Catch\CatchAdmin;
use Catch\Providers\CatchModuleServiceProvider;

class SystemServiceProvider extends CatchModuleServiceProvider
{
    /**
     * route path
     *
     * @return string
     */
    public function moduleName(): string
    {
        // TODO: Implement path() method.
        return 'system';
    }
}
