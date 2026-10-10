<?php

declare(strict_types=1);

namespace Modules\System\Mcp\Tools;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Modules\User\Models\User;

#[IsReadOnly]
class CurrentUser extends Tool
{
    protected string $name = 'current-user';

    protected string $description = 'Return the profile of the backend user associated with this MCP token.';

    public function handle(Request $request): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return Response::error('Authentication required.');
        }

        return Response::json(['id' => $user->id, 'username' => $user->username, 'avatar' => $user->avatar]);
    }
}
