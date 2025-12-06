<?php

namespace Modules\Permissions\Providers;

use Catch\Providers\CatchModuleServiceProvider;
use Modules\Permissions\Middlewares\PermissionGate;
use Modules\Permissions\Events\EnableModuleMenusEvent;
use Modules\Permissions\Events\DisableModuleMenusEvent;
use Modules\Permissions\Listeners\EnableModuleMenusListener;
use Modules\Permissions\Listeners\DisableModuleMenusListener;
use Modules\Permissions\Events\DeleteModuleMenusEvent;
use Modules\Permissions\Listeners\DeleteModuleMenusListener;

class PermissionsServiceProvider extends CatchModuleServiceProvider
{
    protected array $events = [
        EnableModuleMenusEvent::class => EnableModuleMenusListener::class,
        DisableModuleMenusEvent::class => DisableModuleMenusListener::class,
        DeleteModuleMenusEvent::class => DeleteModuleMenusListener::class,
    ];

    /**
     * middlewares
     *
     * @return string[]
     */
    protected function middlewares(): array
    {
        return [PermissionGate::class];
    }

    /**
     * route path
     */
    public function moduleName(): string|array
    {
        // TODO: Implement path() method.
        return 'permissions';
    }
}
