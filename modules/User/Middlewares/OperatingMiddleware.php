<?php

namespace Modules\User\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\User\Models\LogOperate;

class OperatingMiddleware
{
    /**
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        return $next($request);
    }


    /**
     *
     * @param Request $request
     * @param Response $response
     */
    public function terminate(Request $request, Response $response): void
    {
        app(LogOperate::class)->log($request, $response);
    }
}
