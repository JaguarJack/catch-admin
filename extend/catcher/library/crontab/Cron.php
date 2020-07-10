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

use Cron\CronExpression;
use think\facade\Console;

/**
 * Class Cron
 * @package catcher\library\crontab
 *
 * // cron 表达式
 *    *    *    *    *
 * -    -    -    -    -
* |    |    |    |    |
* |    |    |    |    |
* |    |    |    |    +----- day of week (0 - 6) (Sunday=0)
* |    |    |    +---------- month (1 - 12)
* |    |    +--------------- day of month (1 - 31)
* |    +-------------------- hour (0 - 23)
* +------------------------- min (0 - 59)
 *
 *
 *
 */
class Cron
{
    use Frequencies;

    /**
     * crontab 表达式
     *
     * @var string
     */
    protected $expression = '* * * * *';

    /**
     * task 任务
     *
     * @var string
     */
    protected $task;

    /**
     * console 命令
     *
     * @var string
     */
    protected $console;

    /**
     * console 参数
     *
     * @var array
     */
    protected $arguments;

    /**
     * 秒级支持
     *
     * @var int
     */
    protected $second;

    public function __construct($name, $arguments = [])
    {
        if (is_string($name)) {
            $this->console = $name;
        }

        if (is_object($name)) {
            $this->task = $name;
        }

        $this->arguments = $arguments;
    }

    /**
     * 运行 cron 任务
     *
     * @time 2020年07月04日
     * @return void
     */
    public function run()
    {
        if ($this->console) {
            Console::call($this->console, $this->arguments);
        }

        if ($this->task && method_exists($this->task, 'run')) {
            $this->task->run();
        }
    }

    /**
     * 是否可运行
     *
     * @time 2020年07月04日
     * @return bool
     */
    public function can()
    {
        if ($this->second) {
            $now = date('s', time());

            return  ($now % $this->second) == 0;
        }

        if ($this->expression) {
            $cron = CronExpression::factory($this->expression);
            return $cron->getNextRunDate(date('Y-m-d H:i:s'), 0 , true)->getTimestamp() == time();
        }

        return  false;
    }

}