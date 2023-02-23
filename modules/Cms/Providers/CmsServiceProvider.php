<?php

namespace Modules\Cms\Providers;

use Catch\CatchAdmin;
use Catch\Providers\CatchModuleServiceProvider;

class CmsServiceProvider extends CatchModuleServiceProvider
{
    /**
     * route path
     *
     * @return string
     */
    public function moduleName(): string
    {
        // TODO: Implement path() method.
        return 'Cms';
    }
}
