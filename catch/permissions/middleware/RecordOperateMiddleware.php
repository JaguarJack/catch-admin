<?php
namespace catchAdmin\permissions\middleware;

use app\Request;
use catchAdmin\permissions\model\Permissions;
use catcher\CatchCacheKeys;
use catcher\Code;
use catcher\exceptions\PermissionForbiddenException;
use think\facade\Cache;
use catcher\Utils;

class RecordOperateMiddleware
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

        // 模块忽略
        [$module, $controller, $action] = Utils::parseRule($rule);

        $permission = $this->getPermission($module, $controller, $action);

        $this->operateEvent($request->user()->id, $permission);

        // 将权限带入
        $request->permission  = $permission;
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
}
