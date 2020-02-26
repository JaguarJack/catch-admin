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
namespace catcher\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class InstallCatchModuleCommand extends Command
{
    protected function configure()
    {
      $this->setName('install:module')
           ->addArgument('module', Argument::REQUIRED, 'module name')
           ->addOption('modue', '-r',Option::VALUE_NONE, 'reinstall back')
           ->setDescription('install catch module');
    }

    protected function execute(Input $input, Output $output)
    {
    }

    protected function searchModule()
    {

    }

    protected function getModule()
    {

    }


    protected function download()
    {

    }


    protected function install()
    {

    }

    
    protected function update()
    {

    }

}
