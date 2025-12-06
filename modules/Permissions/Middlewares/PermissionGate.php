<?php

namespace Modules\Permissions\Middlewares;

use Catch\Exceptions\FailedException;
use Catch\Facade\Admin;
use Illuminate\Http\Request;
use Modules\Permissions\Exceptions\PermissionForbidden;
use Modules\User\Models\User;

class PermissionGate
{
    public function handle(Request $request, \Closure $next)
    {
        if ($this->isAllowGetRequest($request)) {
            return $next($request);
        }

        if (config('app.env') == 'demo') {
            throw new FailedException('演示环境禁止操作');
        }

        /* @var User $user */
        $user = Admin::auth();

        if (! $user->can()) {
            throw new PermissionForbidden;
        }

        return $next($request);
    }

    protected function isAllowGetRequest(Request $request): bool
    {
        return config('catch.request_allowed') && $request->isMethod('get');
    }
}
