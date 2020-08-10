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

use catchAdmin\permissions\model\Permissions;
use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use catcher\library\Composer;
use catcher\library\Compress;
use catcher\facade\FileSystem;
use catcher\library\InstallCatchModule;
use catcher\library\Zip;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Console;

class InstallCatchModuleCommand extends Command
{
    protected function configure()
    {
      $this->setName('catch-install:module')
           ->addArgument('module', Argument::REQUIRED, 'module name')
           ->addOption('app', '-app', Option::VALUE_NONE, 'module install at [app] path')
           ->setDescription('install catch module');
    }

    protected function execute(Input $input, Output $output)
    {
        $module = $input->getArgument('module');

        $install = (new InstallCatchModule())->setModule($module)
                                             ->setInstallPath($input->getOption('app'));

        $output->info('start download module ' . $module);

        if (!$install->download()) {
            exit($output->error("install module [$module] failed"));
        }

        $install->install();

        $output->info("install module [ $module ] successfully");
    }
}
