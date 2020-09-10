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

use catchAdmin\permissions\model\Permissions;
use catcher\CatchAdmin;
use catcher\library\InstallLocalModule;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class EnableModuleCommand extends Command
{
    protected function configure()
    {
        $this->setName('enable:module')
            ->addArgument('module', Argument::REQUIRED, 'module name')
            ->setDescription('enable catch module');
    }

    protected function execute(Input $input, Output $output)
    {
        $module = $input->getArgument('module');

        if (empty(CatchAdmin::getModuleInfo(CatchAdmin::directory() .$module))) {
            $output->error("module [$module] not exist");
        } else {
            (new InstallLocalModule($module))->enableModule();
            $output->info("module [$module] enabled");
        }
    }
}