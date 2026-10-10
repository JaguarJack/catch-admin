<?php

declare(strict_types=1);

namespace Modules\System\Mcp\Servers;

use Laravel\Mcp\Server;
use Modules\System\Mcp\Tools\CurrentUser;
use Modules\System\Mcp\Tools\DictionaryList;

class AdminServer extends Server
{
    protected string $name = 'Admin MCP';

    protected string $version = '1.0.0';

    protected string $instructions = 'Use the available read-only tools as the authenticated admin user.';

    protected array $capabilities = [self::CAPABILITY_TOOLS => ['listChanged' => false]];

    protected array $tools = [CurrentUser::class, DictionaryList::class];
}
