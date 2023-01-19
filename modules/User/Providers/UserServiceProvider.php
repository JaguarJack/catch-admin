<?php

namespace Modules\User\Providers;

use Catch\CatchAdmin;
use Catch\Providers\CatchModuleServiceProvider;
use Modules\User\Events\Login;
use Modules\User\Listeners\Login as LoginListener;
use Modules\User\Middlewares\OperatingMiddleware;

class UserServiceProvider extends CatchModuleServiceProvider
{
    protected array $events = [
        Login::class => LoginListener::class
    ];

    /**
     * route path
     *
     * @return string|array
     */
    public function moduleName(): string|array
    {
        // TODO: Implement path() method.
        return 'user';
    }

    /**
     * @return string[]
     */
    protected function middlewares(): array
    {
        return [OperatingMiddleware::class];
    }
}
