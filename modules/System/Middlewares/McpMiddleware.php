<?php

declare(strict_types=1);

namespace Modules\System\Middlewares;

use Catch\Enums\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\System\Models\McpToken;
use Symfony\Component\HttpFoundation\Response;

class McpMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearer = $request->bearerToken();
        abort_if(!$bearer, 401, 'Unauthenticated.');

        $token = McpToken::query()
            ->where('token_hash', hash('sha256', $bearer))
            ->where('status', Status::Enable->value)
            ->where('revoked_at', 0)
            ->where('expires_at', '>', time())
            ->first();
        $user = $token?->user;
        abort_if(!$user || (int) $user->status !== Status::Enable->value, 401, 'Unauthenticated.');

        $token->update(['last_used_at' => time(), 'last_used_ip' => $request->ip() ?? '']);
        Auth::resolveUsersUsing(fn () => $user);

        return $next($request);
    }
}
