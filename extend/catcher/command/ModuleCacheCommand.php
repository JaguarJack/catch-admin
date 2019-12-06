<?php
declare (strict_types = 1);

namespace catcher\command;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class ModuleCacheCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('catch:cache')
            ->setDescription('cache routes, views, services of module');
    }

    protected function execute(Input $input, Output $output)
    {
        CatchAdmin::cacheRoutes();
        CatchAdmin::cacheServices();
        CatchAdmin::cacheViews();
    	// 指令输出
    	$output->info('succeed!');
    }
}
