<?php

declare (strict_types=1);

namespace catcher\command\Tools;

use catchAdmin\permissions\model\Users;
use catcher\library\BackUpDatabase;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class InitRootCommand extends Command
{
    protected $table;

    protected function configure()
    {
        // 指令配置
        $this->setName('catch:initAdmin')
            ->setDescription('backup data you need');
    }

    protected function execute(Input $input, Output $output)
    {
        if ($user = Users::where('id', config('catch.permissions.super_admin_id'))->find()) {

            $user->password = 'catchadmin';

            $user->save();
        }
    }
}
