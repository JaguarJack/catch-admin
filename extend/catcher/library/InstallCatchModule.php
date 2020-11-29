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

use catcher\CatchAdmin;
use catcher\command\MigrateCreateCommand;
use catcher\command\MigrateRollbackCommand;
use catcher\command\SeedRunCommand;
use catcher\facade\FileSystem;
use think\facade\Console;

class InstallCatchModule
{
    protected $installPath;

    protected $module;

    protected $compress;

    public function __construct()
    {
        $this->compress = new Compress();

        $this->registerCommands();
    }

    public function run()
    {
        if ($this->isFirstInstall()) {
            if ($this->download()) {
                $this->install();
            }
        }
    }


    /**
     * 是否是首页安装
     *
     * @time 2020年07月26日
     * @return bool
     */
    public function isFirstInstall()
    {
       return  !FileSystem::exists($this->getInstallPath() . $this->module);
    }

    /**
     * 搜索模块
     *
     * @time 2020年07月26日
     * @return string
     */
    public function searchModule()
    {
        return 'http://api.catchadmin.com/hello.zip';
    }

    /**
     * 下载
     *
     * @time 2020年07月26日
     * @return string
     */
    public function download()
    {
        return $this->compress->savePath($this->getModuleZip())->download($this->searchModule());
    }

    /**
     * 安装
     *
     * @time 2020年07月26日
     * @throws \Exception
     * @return void
     */
    public function install()
    {
        $this->extractTo();

        $this->installDatabaseTables();

        $this->installTableData();
    }

    /**
     * 解压
     *
     * @time 2020年07月26日
     * @throws \Exception
     * @return bool
     */
    public function extractTo()
    {
        $zip = new Zip();

        $zip->make($this->getModuleZip())->extractTo($this->getInstallPath())->close();

        return true;
    }

    /**
     * 获取模块
     *
     * @time 2020年07月26日
     * @return string
     */
    protected function getModuleZip()
    {
        return $this->downloadPath() . $this->module . '_'. date('YmdHis') . '.zip';
    }

    /**
     * 安装表
     *
     * @time 2020年07月26日
     * @return void
     */
    public function installDatabaseTables()
    {
        Console::call('catch-migrate:run', [$this->module]);
    }

    /**
     * 初始化表数据
     *
     * @time 2020年07月26日
     * @return void
     */
    public function installTableData()
    {
        Console::call('catch-seed:run', [$this->module]);
    }

    /**
     * 注册命令
     *
     * @time 2020年07月26日
     * @return void
     */
    protected function registerCommands()
    {
        return app()->console->addCommands([
            MigrateCreateCommand::class,
            SeedRunCommand::class,
            MigrateRollbackCommand::class,
        ]);
    }

    /**
     * 下载路径
     *
     * @time 2020年07月26日
     * @return string
     */
    protected function downloadPath()
    {
        $path = runtime_path('catch' . DIRECTORY_SEPARATOR . 'download');

        if (!FileSystem::exists($path)) {
            FileSystem::makeDirectory($path, 0777, true);
        }

        return $path;
    }

    /**
     * 获取安装路径
     *
     * @time 2020年07月26日
     * @return string
     */
    protected function getInstallPath()
    {
        if ($this->installPath) {
            return root_path($this->installPath);
        }

        return CatchAdmin::directory();
    }

    /**
     * 设置模块
     *
     * @time 2020年07月26日
     * @param $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * 设置安装目录
     *
     * @time 2020年07月26日
     * @param $path
     * @return $this
     */
    public function setInstallPath($path)
    {
        $this->installPath = $path;

        return $this;
    }
}