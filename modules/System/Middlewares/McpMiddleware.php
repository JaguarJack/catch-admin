<?php

declare(strict_types=1);

namespace Modules\System\Middlewares;

use Catch\Enums\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\System\Models\McpToken;
use Modules\System\Models\McpTokenLog;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class McpMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearer = $request->bearerToken();
        abort_if(! $bearer, 401, 'Unauthenticated.');

        $token = McpToken::query()
            ->where('token_hash', hash('sha256', $bearer))
            ->where('status', Status::Enable->value)
            ->where('revoked_at', 0)
            ->where('expires_at', '>', time())
            ->first();
        $user = $token?->user;
        abort_if(! $user || (int) $user->status !== Status::Enable->value, 401, 'Unauthenticated.');

        $token->update(['last_used_at' => time(), 'last_used_ip' => $request->ip() ?? '']);
        Auth::resolveUsersUsing(fn () => $user);

        $method = $request->json('method');
        if (is_string($method) && $method === 'tools/call') {
            $toolName = $request->json('params.name');
            $request->attributes->set('mcp_token_usage', [
                'token_id' => $token->id,
                'tool_name' => is_string($toolName) ? mb_substr($toolName, 0, 100) : '',
            ]);
        }

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $usage = $request->attributes->get('mcp_token_usage');
        if (! is_array($usage)) {
            return;
        }

        try {
            // ponytail: 当前工具同步返回 JSON，新增流式工具时采集最终消息。
            $content = $response->getContent();
            $payload = is_string($content) ? json_decode($content, true) : null;
            $success = $response->isSuccessful() && is_array($payload)
                && ! isset($payload['error']) && is_array($payload['result'] ?? null)
                && ($payload['result']['isError'] ?? true) === false;

            McpTokenLog::query()->create($usage + [
                'ip' => mb_substr($request->ip() ?? '', 0, 45),
                'result' => $success ? 1 : 2,
                'created_at' => time(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
