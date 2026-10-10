<?php

declare(strict_types=1);

use Laravel\Mcp\Facades\Mcp;
use Modules\System\Mcp\Servers\AdminServer;
use Modules\System\Middlewares\McpMiddleware;

Mcp::web('/ai/mcp', AdminServer::class)
    ->name('mcp.admin')
    ->middleware(McpMiddleware::class);
