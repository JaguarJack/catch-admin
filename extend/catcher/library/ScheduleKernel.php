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
namespace catcher\library;

use catcher\library\crontab\Schedule;

class ScheduleKernel
{
    protected $schedule;

    public function __construct()
    {
        $this->schedule = new Schedule();
    }

    protected function run()
    {
        $this->schedule->command('catch:cache')->everyThirtySeconds();
        $this->schedule->command('test')->everyTenSeconds();

    }


    public function tasks()
    {
        $this->run();

        return $this->schedule->getCronTask();
    }
}