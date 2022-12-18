<?php

namespace Modules\Permissions\Middlewares;

use Illuminate\Http\Request;
use Modules\Permissions\Exceptions\PermissionForbidden;
use Modules\Permissions\Models\LogOperate;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;

class PermissionGate
{
    public function handle(Request $request, \Closure $next)
    {
        if ($request->isMethod('get')) {
            // return $next($request);
        }

        /* @var User $user */
        $user = $request->user(getGuardName());

        if (! $user->can()) {
            throw new PermissionForbidden();
        }

        return $next($request);
    }


    /**
     * terminate
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        app(LogOperate::class)->log($request, $response);
    }
}
