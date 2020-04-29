<?php
declare (strict_types = 1);

namespace catcher\command\Tools;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class BackUpDataCommand extends Command
{
    protected $table;

    protected function configure()
    {
        // 指令配置
        $this->setName('backup:data')
            ->addArgument('tables', Argument::REQUIRED, 'backup tables')
            ->addOption('zip', '-z',Option::VALUE_NONE, 'is need zip')
            ->setDescription('backup data you need');
    }

    protected function execute(Input $input, Output $output)
    {

        $output->info('succeed!');
    }
}
