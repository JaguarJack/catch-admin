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
namespace catchAdmin\monitor\command\process;

use catcher\facade\FileSystem;
use think\exception\ClassNotFoundException;
use think\helper\Str;

trait ParseTask
{
    /**
     * 获取任务
     *
     * @time 2020年09月15日
     * @param $crontab
     * @return mixed
     */
    protected function getTaskObject($crontab)
    {
        $class = $this->getTaskNamespace() . ucfirst(Str::camel($crontab['task']));

        if (class_exists($class)) {
            return app()->make($class)->setCrontab($crontab);
        }

        throw new ClassNotFoundException('Task '. $crontab['task'] . ' not found');
    }

    /**
     * 获取任务命名空间
     *
     * @time 2020年09月15日
     * @return mixed
     */
    protected function getTaskNamespace()
    {
        return config('catch.crontab.task_namespace');
    }
}