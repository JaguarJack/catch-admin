<?php
namespace catchAdmin\permissions\middleware;

use app\Request;
use catchAdmin\permissions\model\Permissions;
use catcher\CatchCacheKeys;
use catcher\Code;
use catcher\exceptions\PermissionForbiddenException;
use think\facade\Cache;
use catcher\Utils;

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
        $rule = $request->rule()->getName();

        if (!$rule) {
            return $next($request);
        }

        // 模块忽略
        [$module, $controller, $action] = Utils::parseRule($rule);

        // toad
        if (in_array($module, $this->ignoreModule())) {
            return $next($request);
        }
        // 用户未登录
        $user = $request->user();
        if (!$user) {
            throw new PermissionForbiddenException('Login is invalid', Code::LOST_LOGIN);
        }
        // 超级管理员
        if (Utils::isSuperAdmin()) {
            return $next($request);
        }
        // Get 请求
        if ($this->allowGet($request)) {
            return $next($request);
        }
        // 判断权限
        $permission = property_exists($request, 'permission') ? $request->permission :
                        $this->getPermission($module, $controller, $action);

        if (!$permission || !in_array($permission->id, Cache::get(CatchCacheKeys::USER_PERMISSIONS . $user->id))) {
          throw new PermissionForbiddenException();
        }

        return $next($request);
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
        $permissionMark = sprintf('%s@%s', $controllerName, $action);

        return Permissions::where('module', $module)->where('permission_mark', $permissionMark)->find();
    }

    /**
     * 忽略模块
     *
     * @time 2020年04月16日
     * @return array
     */
    protected function ignoreModule()
    {
        return ['login'];
    }

    /**
     * 操作日志
     *
     * @time 2020年04月16日
     * @param $creatorId
     * @param $permission
     * @return void
     */
    protected function operateEvent($creatorId, $permission)
    {
        // 操作日志
        $permission && event('operateLog', [
            'creator_id' => $creatorId,
            'permission' => $permission,
        ]);
    }

    /**
     * get allow
     *
     * @time 2020年10月12日
     * @param $request
     * @return bool
     * @throws \ReflectionException
     */
    protected function allowGet($request)
    {
        if (Utils::isMethodNeedAuth($request->rule()->getName())) {
            return false;
        }

        return $request->isGet() && config('catch.permissions.is_allow_get');
    }
}
