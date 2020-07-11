<?php
/**
 * @filename  InstallCatchModuleCommand.php
 * @createdAt 2020/2/24
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\command\install;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use catcher\facade\Http;
use catcher\library\Compress;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Console;

class InstallCatchModuleCommand extends Command
{
    protected $module;

    protected $moduleZipPath;

    protected function configure()
    {
      $this->setName('install:module')
           ->addArgument('module', Argument::REQUIRED, 'module name')
           ->setDescription('install catch module');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->module = $input->getArgument('module');

        $this->moduleZipPath = $this->installRootPath() . $this->module .'.zip';

        try {
            if ($this->download()) {
                if ($this->install()) {
                    $this->installComposerPackage();
                    $this->createTable();
                    $this->importData();
                }
            }
        } catch (\Throwable $exception) {
            $this->rollback();
            exit($output->error($exception->getMessage()));
        }

        $output->info("install module [$this->module] successfully");
    }

    protected function searchModule()
    {
        return 'http://api.catchadmin.com/hello.zip';
    }

    /**
     * 下载扩展包
     *
     * @return bool
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function download()
    {
       if (!(new Compress())->savePath($this->moduleZipPath)->download($this->module, $this->searchModule())) {
           throw new FailedException('download module '.$this->module. ' failed');
       }

       return true;
    }

    /**
     * 安装模块
     *
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function install()
    {
        if ($this->isFirstInstall()) {
            $zip = new \ZipArchive();
            $res = $zip->open($this->moduleZipPath);
            if ($res === true) {
                $zip->extractTo($this->installRootPath());
                $zip->close();
                return true;
            }
        } else {
            if (!(new Compress())->update($this->module)) {
                throw new FailedException('install module ' . $this->module . ' failed');
            }
        }

        return true;
    }


    protected function installComposerPackage()
    {
        try {
           $moduleInfo = \json_decode(file_get_contents($this->installPath() . $this->module . DIRECTORY_SEPARATOR . 'module.json'), true);
           $requires = $moduleInfo['requires'];
           foreach ($requires as $require) {
               exec(sprintf('composer require "%s"', $require));
           }
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }

        return true;
    }

    /**
     * 创建表
     *
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function createTable()
    {
        Console::call('catch-migrate:run', [$this->module]);
    }

    /**
     * 导入数据
     *
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function importData()
    {
        Console::call('catch-seed:run', [$this->module]);
    }

    /**
     * 回滚
     *
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function rollback()
    {
        (new Compress())->rmDir($this->installPath() . $this->module);

        Console::call('catch-migrate:rollback', [$this->module, '-f']);
    }

    protected function update()
    {

    }

    /**
     * 那安装根目录
     *
     * @return string
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function installRootPath()
    {
        return CatchAdmin::directory();
    }

    /**
     * 安装的模块目录
     *
     * @return string
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function installPath()
    {
       return CatchAdmin::moduleDirectory($this->module);
    }

    /**
     * 是否第一次安装
     *
     * @return bool
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function isFirstInstall()
    {
        return !is_dir($this->installRootPath() . $this->module);
    }
}
