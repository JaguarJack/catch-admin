<?php
declare(strict_types=1);

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\library;


use catchAdmin\permissions\model\Permissions;
use catcher\CatchAdmin;
use think\facade\Console;

class InstallLocalModule
{
    protected $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * 查找
     *
     * @time 2020年09月10日
     * @return bool
     */
    public function done()
    {
        if ($this->findModuleInPermissions()) {
            return false;
        } else {
            $this->installModuleTables();
            $this->installModuleSeeds();
            $this->enableModule();
            return true;
        }
    }

    /**
     * 本地模块是否存在
     *
     * @time 2020年09月10日
     * @return bool
     */
    public function localModuleExist()
    {
        return in_array($this->module, array_column(CatchAdmin::getModulesInfo(true), 'value'));
    }


    /**
     * 模块是否开启
     *
     * @time 2020年09月10日
     * @return false|mixed
     */
    public function isModuleEnabled()
    {
        return in_array($this->module, array_column($this->getLocalModulesInfo(false), 'name'));
    }

    /**
     * 获取本地模块信息
     *
     * @param bool $enabled
     * @time 2020年09月10日
     * @return array
     */
    public function getLocalModulesInfo($enabled = true)
    {
        $modules = CatchAdmin::getModulesInfo(true);

        $info = [];
        foreach ($modules as $module) {
            $moduleInfo = CatchAdmin::getModuleInfo(CatchAdmin::directory() . $module['value']);
            // 获取全部
            if ($enabled) {
                $info[] = [
                    'name' => $module['value'],
                    'title' => $module['title'],
                    'enable' => $moduleInfo['enable'],
                ];
            } else {
                // 获取未开启的
                if (!$moduleInfo['enable']) {
                    $info[] = [
                        'name' => $module['value'],
                        'title' => $module['title'],
                        'enable' => $moduleInfo['enable'],
                    ];
                }
            }
        }

        return $info;
    }

    /**
     * 查找模块
     *
     * @time 2020年09月10日
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return bool
     */
    public function findModuleInPermissions()
    {
        return Permissions::withTrashed()->where('module', $this->module)->find() ? true : false;
    }

    /**
     * 启用模块
     *
     * @time 2020年09月10日
     * @return void
     */
    public function enableModule()
    {
        CatchAdmin::updateModuleInfo($this->module, ['enable' => true]);

        app(Permissions::class)->restore(['module' => trim($this->module)]);
    }

    /**
     * 禁用模块
     *
     * @time 2020年09月10日
     * @return void
     */
    public function disableModule()
    {
        CatchAdmin::updateModuleInfo($this->module, ['enable' => false]);

        Permissions::destroy(function ($query) {
            $query->where('module', trim($this->module));
        });
    }

    /**
     * 创建模块表
     *
     * @time 2020年09月10日
     * @return void
     */
    public function installModuleTables()
    {
        Console::call('catch-migrate:run', [$this->module]);
    }

    /**
     * 初始化模块数据
     *
     * @time 2020年09月10日
     * @return void
     */
    public function installModuleSeeds()
    {
        Console::call('catch-seed:run', [$this->module]);
    }

    /**
     * 回滚模块表
     *
     * @time 2020年09月10日
     * @return void
     */
    public function rollbackModuleTable()
    {
        Console::call('catch-migrate:rollback', [$this->module, '-f']);
    }
}