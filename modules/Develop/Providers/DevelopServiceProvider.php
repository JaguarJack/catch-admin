<?php

namespace Modules\Develop\Providers;

use Catch\CatchAdmin;
use Catch\Events\Module\Created;
use Catch\Events\Module\Deleted;
use Catch\Providers\CatchModuleServiceProvider;
use Modules\Develop\Listeners\CreatedListener;
use Modules\Develop\Listeners\DeletedListener;

class DevelopServiceProvider extends CatchModuleServiceProvider
{
    protected array $events = [
        Created::class => CreatedListener::class,

        Deleted::class => DeletedListener::class
    ];

    /**
     * route path
     *
     * @return string|array
     */
    public function moduleName(): string|array
    {
        // TODO: Implement path() method.
        return 'develop';
    }
}
