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

use catcher\CatchAdmin;
use think\console\Table;

trait Process
{
    protected function createProcessCallback()
    {
        return function (\Swoole\Process $process) {
            $quit = false;
            // 必须使用 pcntl signal 注册捕获
            // Swoole\Process::signal ignalfd 和 EventLoop 是异步 IO，不能用于阻塞的程序中，会导致注册的监听回调函数得不到调度
            // 同步阻塞的程序可以使用 pcntl 扩展提供的 pcntl_signal
            // 安全退出进程
            pcntl_signal(SIGTERM, function() use (&$quit){
                $quit = true;
            });

            while (true) {
                //$data = $worker->pop();
                $this->beforeTask($process->pid);
                $this->afterTask($process->pid);

                var_dump($this->process);
                var_dump(isset($this->process[$process->pid]), $process->pid);
                // 处理任务前
                // 处理任务中
                // 处理任务后
                //var_dump(unserialize($data));
                // echo  "来自主进程的消息:". $worker->pop().',来自管道'.$worker->pipe.',当前的进程id为'.$worker->pid.PHP_EOL;
                // $worker->push('hello 主进程'); //不要当做管道使用

                // 睡眠一秒 让出 CPU 调度

                // var_dump(123);

                pcntl_signal_dispatch();
                sleep(5);

                // 如果收到安全退出的信号，需要在最后任务处理完成之后退出
                if ($quit) {
                    var_dump(1000);
                    $process->exit(0);
                }
            }
        };
    }

    /**
     * 进程信息
     *
     * @time 2020年07月05日
     * @param $process
     * @return array
     */
    protected function processInfo($process)
    {
        return [
            'name' => $process,
            'pid'  => $process->pid,
            'status' => self::WAITING,
            'start_at' => time(),
            'deal_num' => 0,
            'error' => 0,
        ];
    }

    /**
     * 是否有等待的 Process
     *
     * @time 2020年07月07日
     * @return array
     */
    protected function hasWaitingProcess()
    {
        $waiting = [false, null];

        foreach ($this->process as $process) {
            if ($process['status'] == self::WAITING) {
                $waiting = [true, $process];
                break;
            }
        }

        return $waiting;
    }

    /**
     * 处理任务前
     *
     * @time 2020年07月07日
     * @param $pid
     * @return void
     */
    protected function beforeTask($pid)
    {
        if (isset($this->process[$pid])) {
            $this->process[$pid]['status'] = self::BUSYING;
        }
    }

    /**
     * 处理任务后
     *
     * @time 2020年07月07日
     * @param $pid
     * @return void
     */
    protected function afterTask($pid)
    {
        if (isset($this->process[$pid])) {
            $this->process[$pid]['status'] = self::WAITING;
            $this->process[$pid]['deal_num'] += 1;
            var_dump($this->process);
        }
    }

    /**
     * 退出服务
     *
     * @time 2020年07月07日
     * @return void
     */
    public function stop()
    {
        \Swoole\Process::kill($this->getMasterPid(), SIGTERM);
    }

    /**
     * 状态输出
     *
     * @time 2020年07月07日
     * @return void
     */
    public function status()
    {
        \Swoole\Process::kill($this->getMasterPid(), SIGUSR1);
    }

    /**
     * 子进程重启
     *
     * @time 2020年07月07日
     * @return void
     */
    public function reload()
    {
        \Swoole\Process::kill($this->getMasterPid(), SIGUSR2);
    }

    /**
     * 输出 process 信息
     *
     * @time 2020年07月05日
     * @return string
     */
    public function getWorkerStatus()
    {
        $scheduleV = self::VERSION;
        $adminV = CatchAdmin::VERSION;
        $phpV = PHP_VERSION;

        $info =  <<<EOT
-------------------------------------------------------------------------------------------------------
|   ____      _       _        _       _           _         ____       _              _       _       | 
|  / ___|__ _| |_ ___| |__    / \   __| |_ __ ___ (_)_ __   / ___|  ___| |__   ___  __| |_   _| | ___  |
| | |   / _` | __/ __| '_ \  / _ \ / _` | '_ ` _ \| | '_ \  \___ \ / __| '_ \ / _ \/ _` | | | | |/ _ \ |
| | |__| (_| | || (__| | | |/ ___ \ (_| | | | | | | | | | |  ___) | (__| | | |  __/ (_| | |_| | |  __/ |
|  \____\__,_|\__\___|_| |_/_/   \_\__,_|_| |_| |_|_|_| |_| |____/ \___|_| |_|\___|\__,_|\__,_|_|\___| |
| ----------------------------------------- CatchAdmin Schedule ---------------------------------------|                                                                                                   
|  Schedule Version: $scheduleV         CatchAdmin Version: $adminV         PHP Version: $phpV         |
|------------------------------------------------------------------------------------------------------|
EOT;

        $table = new Table();
        $table->setHeader([
            'Pid', 'StartAt', 'Status', 'DealTaskNumber', 'Errors'
        ], 3);

        $processes = [];

        foreach ($this->process as $process) {
            $processes[] = [
                'pid' => $process['pid'],
                'start_at' => date('Y-m-d H:i', $process['start_at']),
                'status' => $process['status'],
                'deal_num' => $process['deal_num'],
                'error' => $process['error'],
            ];
        }

        $table->setRows($processes, 3);

        $table->render();

        return  $info . PHP_EOL . $table->render();
    }
}