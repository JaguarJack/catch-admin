<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\command\install;


use catcher\library\InstallLocalModule;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class InstallLocalModuleCommand extends Command
{
    protected function configure()
    {
        $this->setName('local:install')
            ->addArgument('module', Argument::REQUIRED, 'module name')
            ->setDescription('install catch local module');
    }

    protected function execute(Input $input, Output $output)
    {
        $installedModule = $input->getArgument('module');

        $install = new InstallLocalModule($installedModule);

        if (!$install->localModuleExist()) {
            while (true) {
                $modules = $install->getLocalModulesInfo(false);
                if (!count($modules)) {
                    $output->error('Input module not found and All local modules had been enabled');exit;
                }
                $choose = '';
                $i = 1;
                foreach ($modules as $k => $module) {
                    $choose .= ($i++) . ':' . ($module['name']) . ($module['enable'] ? '(开启)' : '(未开启)') . PHP_EOL;
                }
                $answer = $output->ask($input, $choose);
                if (isset($modules[$answer-1])) {
                    $installedModule = $modules[$answer - 1]['name'];
                    break;
                }
            }
        }

        $install = new InstallLocalModule($installedModule);

        if (!$install->isModuleEnabled()) {
            $output->error($installedModule . ' has been enabled!');
            exit;
        }

        if (!$install->done()) {
            $output->error(sprintf('module [%s] has been installed, You can use [php think enable:module $module] to start it.', $installedModule));
        }

        $output->info(sprintf('module [%s] installed successfully', $installedModule));
    }
}