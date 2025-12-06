<?php

namespace Modules\User\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Modules\User\Models\LogOperate;
use Symfony\Component\HttpFoundation\Response;

class OperatingMiddleware
{
    public function handle($request, Closure $next): mixed
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        app(LogOperate::class)->log($request, $response);
    }
}
