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

use Swoole\Process;

trait RegisterSignal
{
    /**
     * Register 信号
     *
     * @time 2019年08月06日
     */
    protected function registerSignal()
    {
        // Process::signal(SIGALRM, $this->restartProcess());

        Process::signal(SIGCHLD, $this->waitingForWorkerExit());

        Process::signal(SIGTERM, $this->smoothExit());

        Process::signal(SIGUSR2, $this->smoothReloadWorkers());

        Process::signal(SIGUSR1, $this->workerStatus());

        Process::signal(SIGPIPE, $this->catchPipeError());
    }

    /**
     * 重新拉起子进程
     *
     * @time 2019年08月06日
     * @return \Closure
     */
    protected function restartProcess()
    {
        return function () {
            // var_dump('alarm here');
            /**$count = count($this->process);
            if ($count < $this->staticNum) {
            $process = $this->createStaticProcess();
            $this->workerInfo($process);
            }*/
        };
    }

    /**
     * 等待子进程退出 防止僵尸
     *
     * @time 2019年08月06日
     * @return \Closure
     */
    protected function waitingForWorkerExit()
    {
        return function () {
            while ($res = Process::wait(false)) {
                if (isset($this->processes[$res['pid']])) {
                    $this->unsetWorkerStatus($res['pid']);
                    unset($this->processes[$res['pid']]);
                } else {
                    // 临时进程数目减少一次
                    $this->temporaryNum -= 1;
                }
            }
        };
    }

    /**
     * 注册 SIGTERM
     *
     * @time 2019年08月06日
     * @return \Closure
     */
    protected function smoothExit()
    {
        return function () {
            // 发送停止信号给子进程 等待结束后自动退出
            foreach ($this->processes as $pid => $process) {
                Process::kill($pid, SIGTERM);
            }
            // 退出 master
            Process::kill($this->master_pid, SIGKILL);
        };
    }

    /**
     * 输出 worker 的状态
     *
     * @time 2020年07月06日
     * @return \Closure
     */
    protected function workerStatus()
    {
        return function () {
           foreach ($this->processes as $pid => $process) {
                Process::kill($pid, SIGUSR1);
           }
           usleep(100);
           $this->saveProcessStatus();
        };
    }

    /**
     * 平滑重启子进程
     *
     * @time 2020年07月06日
     * @return \Closure
     */
    protected function smoothReloadWorkers()
    {
        return function () {
            // 使用队列， 会发生主进程往一个不存在的进程发送消息吗？
            foreach ($this->processes as $process) {
                Process::kill((int)$process['pid'], SIGTERM);
            }
        };
    }

    /**
     * 管道破裂信号
     *
     * @time 2020年07月06日
     * @return \Closure
     */
    public function catchPipeError()
    {
        return function () {
           // todo
        };
    }
}