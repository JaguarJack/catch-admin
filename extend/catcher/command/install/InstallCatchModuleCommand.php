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

    /**
     * @var Compress
     */
    protected $compress;

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

        $this->compress = new Compress();
        try {
            if ($this->download($this->searchModule())) {
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
        $this->output->info('find module zip');
        return 'http://api.catchadmin.com/hello.zip';
    }

    /**
     * 下载扩展包
     *
     * @param $resourceUrl
     * @return bool
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function download($resourceUrl)
    {
        $this->output->info('download module zip');
        if (!$this->compress->savePath($this->moduleZipPath)->download($resourceUrl)) {
            throw new FailedException('download module ' . $this->module . ' failed');
        }
        $this->output->info('download module zip successfully');
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
            }
            $this->output->info('install module successfully');
        } else {
            if (!$this->compress->update($this->module)) {
                throw new FailedException('install module ' . $this->module . ' failed');
            }
            $this->output->info('update module successfully');
        }

        return true;
    }


    protected function installComposerPackage()
    {
        try {
            if (file_exists($this->installPath() . 'module.json')) {
                $moduleInfo = \json_decode(file_get_contents($this->installPath() . 'module.json'), true);
                $requires = $moduleInfo['requires'];
                if (count($requires)) {
                    foreach ($requires as $require) {
                        list($package, $version) = explode(':', $require);
                        if (!$this->isInstalledProjectComposerPackage($package)) {
                            exec(sprintf('composer require "%s"', $require));
                            $this->output->info('install composer package ['.$package.']');
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }

        return true;
    }

    /**
     * 是否安装
     *
     * @param $package
     * @return array|bool
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    protected function isInstalledProjectComposerPackage($package)
    {
        $composer = \json_decode(file_get_contents(root_path() . 'composer.json'), true);

        return in_array($package, array_keys($composer['require']));
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
        (new Compress())->rmDir($this->installPath());

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
