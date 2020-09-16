<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\monitor\command\process;

use catcher\base\CatchCronTask;

trait Attributes
{
    /**
     * 是否以守护进程方式运行
     *
     * @var bool
     */
    protected $daemon = false;

    /**
     * 静态进程数量
     *
     * @var int
     */
    protected $static;

    /**
     * 动态进程数量
     *
     * @var int
     */
    protected $dynamic;

    /**
     * 定时器触发时间
     *
     * @var int
     */
    protected $interval;

    /**
     * set name
     *
     * @var
     */
    protected $name = 'catch-crontab';

    /**
     * @var string
     */
    protected $crontabQueueName = 'catch-crontab-task';

    /**
     * 安全退出
     *
     * @var bool
     */
    protected $exitSafely = false;

    /**
     * 设置守护进程
     *
     * @time 2020年07月21日
     * @param bool $daemon
     * @return $this
     */
    public function asDaemon($daemon = false)
    {
        $this->daemon = $daemon;

        return $this;
    }

    public function staticNumber($n)
    {
        $this->static = $n;

        return $this;
    }

    /**
     * 可扩容
     *
     * @time 2020年07月21日
     * @param $n
     * @return $this
     */
    public function dynamic($n)
    {
        $this->dynamic = $n;

        return $this;
    }

    /**
     * 定时
     *
     * @time 2020年07月21日
     * @param $n
     * @return $this
     */
    public function interval($n)
    {
        $this->interval = $n;

        return $this;
    }


    /**
     * 设置 name
     *
     * @time 2020年07月23日
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * 设置报告错误
     *
     * @time 2020年07月24日
     * @return void
     */
    public function displayErrors()
    {
        ini_set('display_errors', 1);

        error_reporting(E_ALL & ~E_WARNING);

        ini_set('display_startup_errors', 1);

        ini_set('ignore_repeated_errors', 1);
    }
}
