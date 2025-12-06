<?php

namespace Modules\User\Providers;

use Catch\Providers\CatchModuleServiceProvider;
use Modules\User\Console\PasswordCommand;
use Modules\User\Events\Login;
use Modules\User\Listeners\Login as LoginListener;
use Modules\User\Middlewares\OperatingMiddleware;

class UserServiceProvider extends CatchModuleServiceProvider
{
    protected array $events = [
        Login::class => LoginListener::class,
    ];

    protected array $commands = [
        PasswordCommand::class,
    ];

    /**
     * route path
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
