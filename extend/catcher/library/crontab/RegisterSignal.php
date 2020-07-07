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
                if (isset($this->process[$res['pid']])) {
                    unset($this->process[$res['pid']]);
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
            foreach ($this->process as $process) {
                Process::kill($process['pid'], SIGTERM);
            }

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
            $this->storeStatus();
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
            foreach ($this->process as $process) {
                var_dump($process['pid']);
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