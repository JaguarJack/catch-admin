<?php
namespace catchAdmin\permissions;

use app\Request;
use catchAdmin\permissions\model\Permissions;
use catcher\CatchAdmin;
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
        if (($permission = $this->getPermission($request))
            && !in_array($permission->id, $request->user()->getPermissionsBy())) {
              throw new PermissionForbiddenException();
        }

        return $next($request);
    }

    /**
     *
     * @time 2019年12月12日
     * @param $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|bool|\think\Model|null
     */
    protected function getPermission(Request $request)
    {
        $rule = $request->rule()->getName();

        [$controller, $action] = explode(Str::contains($rule, '@') ? '@' : '/', $rule);

        $controller = explode('\\', $controller);

        $controllerName = strtolower(array_pop($controller));

        array_pop($controller);

        $module = array_pop($controller);

        $permissionMark = sprintf('%s:%s', $controllerName, $action);
        $permission = Permissions::where('module', $module)->where('permission_mark', $permissionMark)->find();

        if (!$permission) {
            return  false;
        }

        event('operateLog', [
            'request' => $request,
            'permission' => $permission,
        ]);

        return  $permission;
    }
}