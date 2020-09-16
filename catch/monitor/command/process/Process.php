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

use catcher\exceptions\FailedException;

class Process
{
    /**
     * 保存工作进程 PID
     *
     * @var
     */
    public $pid;

    /**
     * 用户自定义方法
     *
     * @var callable
     */
    protected $callable;

    /**
     * 申请最大内存 给出缓冲期
     *
     * @var string
     */
    protected $initMemory = '256M';

    /**
     * 超过最大内存报警
     *
     * @var float|int
     */
    protected $allowMaxMemory = 128 * 1024 * 1024;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * 守护进程
     *
     * @time 2020年07月21日
     * @return void
     * @throws FailedException
     */
    public static function daemon()
    {
        $pid = pcntl_fork();

        if ($pid < 0) {
            throw new FailedException('fork process failed');
        }
        // 退出父进程
        if ($pid > 0) {
            exit(0);
        }
        // 设置新的会话组
        if (posix_setsid() < 0) {
            exit(0);
        }
        chdir('/');
        // 重置掩码 权限问题
        umask(0);
    }

    /**
     * 启动进程
     *
     * @time 2020年07月21日
     * @return void
     */
    public function start()
    {
        $pid = pcntl_fork();

        if ($this->pid < 0) {
            exit('fork failed');
        }

        if ($pid > 0) {
            $this->pid = $pid;
        } else {
            call_user_func_array($this->callable, [$this]);
        }
    }

    /**
     * 信号
     *
     * @time 2020年07月21日
     * @param $signal
     * @param $callable
     * @param $restartSysCalls
     * @return void
     */
    public static function signal($signal, $callable, $restartSysCalls = false)
    {
        pcntl_signal($signal, $callable, $restartSysCalls);
    }

    /**
     * default 1 second
     *
     * @param $interval
     * @return mixed
     */
    public static function alarm($interval = 1)
    {
        return pcntl_alarm($interval);
    }

    /**
     * linux 进程下设置进程名称
     *
     * @time 2020年07月21日
     * @param $title
     * @return void
     */
    public static function setWorkerName($title)
    {
        if (strtolower(PHP_OS) === 'linux') {
            cli_set_process_title($title);
        }
    }


    /**
     * 安全退出
     *
     * @time 2020年07月21日
     * @param int $status
     * @return void
     */
    public function exit($status = 0)
    {
        exit($status);
    }

    /**
     * kill
     *
     * @time 2020年07月22日
     * @param $pid
     * @param $signal
     * @return bool
     */
    public static function kill($pid, $signal)
    {
        return posix_kill($pid, $signal);
    }

    /**
     *
     * @time 2020年07月22日
     * @return void
     */
    public static function dispatch()
    {
        pcntl_signal_dispatch();
    }


    /**
     * 是否存活
     *
     * @time 2020年07月22日
     * @param $pid
     * @return bool
     */
    public static function isAlive($pid)
    {
        return posix_kill($pid, 0);
    }

    /**
     * 初始化进程内存
     *
     * @time 2020年07月22日
     * @param int $memory
     * @return void
     */
    public function initMemory($memory = 0)
    {
        if (ini_get('memory_limit') != $this->initMemory) {
            // 这里申请一块稍微大的内存
            ini_set('memory_limit', $memory ?: $this->initMemory);
        }
    }

    /**
     * 是否超过最大内存
     *
     * @time 2020年07月22日
     * @return mixed
     */
    public function isMemoryOverflow()
    {
        // 一旦超过了允许的内存 直接退出进程
        return memory_get_usage() > $this->allowMaxMemory;
    }
}
