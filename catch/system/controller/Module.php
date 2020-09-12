<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\CatchAdmin;
use catcher\library\InstallCatchModule;
use catcher\library\InstallLocalModule;

class Module extends CatchController
{
    /**
     *  模块列表
     *
     * @return void
     */
    public function index()
    {
        # code...
        $modules = [];

        foreach(CatchAdmin::getModulesDirectory() as $d) {
            $modules[] = json_decode(file_get_contents($d . 'module.json'), true);
        }

        array_multisort(array_column($modules, 'order'), SORT_DESC, $modules);

        return CatchResponse::success($modules);
    }


    /**
     * 禁用/启用模块
     *
     * @param string $module
     * @return void
     */
    public function disOrEnable($module)
    {
        # code...
        $moduleInfo = CatchAdmin::getModuleInfo(CatchAdmin::directory() . $module);

        $install = new InstallLocalModule($module);
        if (!$moduleInfo['enable']) {
            $install->findModuleInPermissions() ? $install->enableModule() : $install->done();
        } else {
            $install->disableModule();
        }

        return CatchResponse::success();
    }
}