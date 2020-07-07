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
namespace catcher\library\crontab;

use catcher\exceptions\FailedException;

class Schedule
{

    protected $crons = [];

    /**
     * 新增 command 任务
     *
     * @time 2020年07月04日
     * @param $command
     * @param array $arguments
     * @return Cron
     */
    public function command($command, $arguments = []): Cron
    {
        $this->crons[] = $cron = new Cron($command);

        return $cron;
    }

    /**
     * 新增 task 任务
     *
     * @time 2020年07月04日
     * @param $task
     * @param array $argument
     * @return Cron
     */
    public function task($task, $argument = []): Cron
    {
        if (is_string($task)) {
           if (!class_exists($task)) {
               throw new FailedException("[$task] not found");
           }

           $task = new $task(...$argument);
        }

        $this->crons[] = $cron = new Cron($task);

        return $cron;
    }


    public function getCronTask()
    {
        return $this->crons;
    }
}


