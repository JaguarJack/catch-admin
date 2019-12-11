<?php
namespace catchAdmin\permissions;

use think\Middleware;

class PermissionsMiddleware
{
    public function handle($request, \Closure $next)
    {
       // toad

        return $next($request);
    }
}