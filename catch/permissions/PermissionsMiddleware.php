<?php
namespace catchAdmin\permissions;

use app\Request;
use catchAdmin\permissions\model\Permissions;
use catcher\CatchCacheKeys;
use catcher\Code;
use catcher\exceptions\PermissionForbiddenException;
use think\facade\Cache;
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
        // Get 请求
        if ($request->isGet() && config('catch.permissions.is_allow_get')) {
          return $next($request);
        }

        $rule = $request->rule()->getName();

        if (!$rule) {
            return $next($request);
        }

        [$module, $controller, $action] = $this->parseRule($rule);

        if (in_array($module, $this->ignoreModule())) {
            return $next($request);
        }

        $user = $request->user();
        if (!$user) {
            throw new PermissionForbiddenException('Login is invalid', Code::LOST_LOGIN);
        }
        // 超级管理员
        if ($request->user()->id === config('catch.permissions.super_admin_id')) {
            return $next($request);
        }
        // toad
        $permission = $this->getPermission($module, $controller, $action);
        if (!$permission || !in_array($permission->id, Cache::get(CatchCacheKeys::USER_PERMISSIONS . $user->id))) {
          throw new PermissionForbiddenException();
        }

        // 操作日志
        event('operateLog', [
          'request' => $request,
          'permission' => $permission,
        ]);

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
    protected function getPermission($module, $controllerName, $action)
    {
        $permissionMark = sprintf('%s:%s', $controllerName, $action);

        $permission = Permissions::where('module', $module)->where('permission_mark', $permissionMark)->find();

        return $permission;
    }

    protected function ignoreModule()
    {
        return ['login'];
    }
}
