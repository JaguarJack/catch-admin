<?php
namespace catchAdmin\permissions;

use app\Request;
use catchAdmin\permissions\model\Permissions;
use catcher\Code;
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
        $rule = $rule = $request->rule()->getName();

        if (!$rule) {
            return $next($request);
        }

        [$module, $controller, $action] = $this->parseRule($rule);

        if (in_array($module, $this->ignoreModule())) {
            return $next($request);
        }

        if (!$request->user()) {
            throw new PermissionForbiddenException('Login is invalid', Code::LOST_LOGIN);
        }

       // toad
        if (($permission = $this->getPermission($module, $controller, $action, $request))
            && !in_array($permission->id, $request->user()->getPermissionsBy())) {
              throw new PermissionForbiddenException();
        }

        return $next($request);
    }

    protected function parseRule($rule)
    {
        [$controller, $action] = explode(Str::contains($rule, '@') ? '@' : '/', $rule);

        $controller = explode('\\', $controller);

        $controllerName = strtolower(array_pop($controller));

        array_pop($controller);

        $module = array_pop($controller);

        return [$module, $controllerName, $action];
    }


    /**
     *
     * @time 2019年12月14日
     * @param $module
     * @param $controllerName
     * @param $action
     * @param $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|bool|\think\Model|null
     */
    protected function getPermission($module, $controllerName, $action, $request)
    {
        $permissionMark = sprintf('%s:%s', $controllerName, $action);
        $permission = Permissions::where('module', $module)->where('permission_mark', $permissionMark)->find();

        if (!$permission) {
            return  false;
        }

        event('operateLog', [
            'request' => $request,
            'permission' => $permission,
        ]);

        return $permission;
    }

    protected function ignoreModule()
    {
        return ['login'];
    }
}
