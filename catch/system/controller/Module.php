<?php
namespace catchAdmin\system\controller;

use catchAdmin\permissions\model\Permissions;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\CatchAdmin;
use catcher\library\InstallCatchModule;
use catcher\library\InstallLocalModule;
use catcher\Utils;
use think\response\Json;

class Module extends CatchController
{
    /**
     *  模块列表
     *
     * @return Json
     */
    public function index(): Json
    {
        $modules = [];

        foreach(CatchAdmin::getModulesDirectory() as $d) {
            $modules[] = CatchAdmin::getModuleInfo($d);
        }

        $hasModules = array_unique(Permissions::whereIn('id', request()->user()->getPermissionsBy())->column('module'));

        $orders = array_column($modules, 'order');

        array_multisort($orders, SORT_DESC, $modules);

        if (!Utils::isSuperAdmin()) {
            foreach ($modules as $k => $module) {
                if (!in_array($module['alias'], $hasModules)) {
                    unset($modules[$k]);
                }
            }
        }

        return CatchResponse::success(array_values($modules));
    }

    /**
     * 禁用/启用模块
     *
     * @param string $module
     * @return Json
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function disOrEnable(string $module): Json
    {
        $moduleInfo = CatchAdmin::getModuleInfo(CatchAdmin::directory() . $module);

        $install = new InstallLocalModule($module);
        if (!$moduleInfo['enable']) {
            $install->findModuleInPermissions() ? $install->enableModule() : $install->done();
        } else {
            $install->disableModule();
        }

        return CatchResponse::success();
    }

    /**
     * 缓存
     *
     * @time 2020年09月21日
     * @return Json
     */
    public function cache(): Json
    {
        return CatchResponse::success(CatchAdmin::cacheServices());
    }

    /**
     * 清理缓存
     *
     * @time 2020年09月21日
     * @return Json
     */
    public function clear(): Json
    {
        return !file_exists(CatchAdmin::getCacheServicesFile()) ?
            CatchResponse::fail('模块没有缓存') :
            CatchResponse::success(unlink(CatchAdmin::getCacheServicesFile()));
    }
}