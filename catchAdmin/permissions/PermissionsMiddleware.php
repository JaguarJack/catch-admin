<?php
namespace catchAdmin\permissions;

use app\Request;
use catchAdmin\permissions\model\Permissions;
use catcher\exceptions\PermissionForbiddenException;
use think\helper\Str;

class PermissionsMiddleware
{
    /**
     *
     * @time 2019年12月12日
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws PermissionForbiddenException
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!$request->user()) {
            throw new PermissionForbiddenException('Login is invalid', 10006);
        }
       // toad
        if (($permission = $this->getPermission($request->rule()->getName())) && in_array($permission->id, $request->user()->getPermissionsBy())) {
              throw new PermissionForbiddenException();
        }

        return $next($request);
    }

    /**
     *
     * @time 2019年12月12日
     * @param $rule
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|bool|\think\Model|null
     */
    protected function getPermission($rule)
    {
        if (!$rule) {
            return false;
        }

        [$controller, $action] = explode(Str::contains($rule, '@') ? '@' : '/', $rule);

        $controller = explode('\\', $controller);

        $controllerName = strtolower(array_pop($controller));

        array_pop($controller);

        $module = array_pop($controller);

        $ignore = config('catch.ignore');

        if (in_array($module, $ignore['module'])) {
            return false;
        }

        $permissionMark = sprintf('%s:%s:%s', $module, $controllerName, $action);

        if (in_array($permissionMark, $ignore['route'])) {
            return false;
        }

        return Permissions::where('permission_mark', $permissionMark)->find();
    }
}