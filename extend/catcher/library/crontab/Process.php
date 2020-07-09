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

            pcntl_signal(SIGUSR1, function () use ($process){
                // todo
                $this->afterTask($process->pid);
            });

            while (true) {
                if ($cron = $process->pop()) {
                    if (is_string($cron) && $cron) {

                        $cron = unserialize($cron);

                        $this->beforeTask($process->pid);

                        try {
                            $cron->run();
                        } catch (\Throwable $e) {
                            file_put_contents($this->schedulePath() . 'schedule.log', $e . PHP_EOL, FILE_APPEND);
;                        }

                        $this->afterTask($process->pid);
                    }
                }
                pcntl_signal_dispatch();
                sleep(1);

                // 如果收到安全退出的信号，需要在最后任务处理完成之后退出
                if ($quit) {
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
            'pid'  => $process->pid,
            'status' => self::WAITING,
            'start_at' => time(),
            'running_time' => 0,
            'memory' => memory_get_usage(),
            'deal_tasks' => 0,
            'errors' => 0,
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

        $pid = 0;

        // 获取等待状态的 worker
        $processes = $this->getProcessesStatus();

        // $processIds
        foreach ($processes as $process) {
            if ($process['status'] == self::WAITING) {
                $pid = $process['pid'];
                break;
            }
        }

        // 获取相应的状态
        if (isset($this->processes[$pid])) {
            return [true, $this->processes[$pid]];
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
        $processes = $this->getProcessesStatus();

        foreach ($processes as &$process) {
            if ($process['pid'] == $pid) {
                $process['status'] = self::BUSYING;
                $process['running_time'] = time() - $process['start_at'];
                $process['memory'] = memory_get_usage();
                break;
            }
        }

        $this->writeStatusToFile($processes);
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
        $processes = $this->getProcessesStatus();

        foreach ($processes as &$process) {
            if ($process['pid'] == $pid) {
                $process['status'] = self::WAITING;
                $process['running_time'] = time() - $process['start_at'];
                $process['memory'] = memory_get_usage();
                break;
            }
        }

        $this->writeStatusToFile($processes);
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
    public function renderProcessesStatusToString()
    {
        $scheduleV = self::VERSION;
        $adminV = CatchAdmin::VERSION;
        $phpV = PHP_VERSION;

        $processNumber = count($this->processes);
        $memory = (int)(memory_get_usage()/1024/1024). 'M';
        $startAt = date('Y-m-d H:i:s', $this->master_start_at);
        $runtime = gmstrftime('%H:%M:%S', time() - $this->master_start_at);
        $info =  <<<EOT
-------------------------------------------------------------------------------------------------------
|   ____      _       _        _       _           _         ____       _              _       _       | 
|  / ___|__ _| |_ ___| |__    / \   __| |_ __ ___ (_)_ __   / ___|  ___| |__   ___  __| |_   _| | ___  |
| | |   / _` | __/ __| '_ \  / _ \ / _` | '_ ` _ \| | '_ \  \___ \ / __| '_ \ / _ \/ _` | | | | |/ _ \ |
| | |__| (_| | || (__| | | |/ ___ \ (_| | | | | | | | | | |  ___) | (__| | | |  __/ (_| | |_| | |  __/ |
|  \____\__,_|\__\___|_| |_/_/   \_\__,_|_| |_| |_|_|_| |_| |____/ \___|_| |_|\___|\__,_|\__,_|_|\___| |
| ----------------------------------------- CatchAdmin Schedule ---------------------------------------|                                                                                                   
|  Schedule Version: $scheduleV         CatchAdmin Version: $adminV         PHP Version: $phpV         |      
|  Process Number: $processNumber           Memory: $memory                     Start at: $startAt     |
|  Running Time: $runtime                                                                              |
|------------------------------------------------------------------------------------------------------|
EOT;

        $table = new Table();
        $table->setHeader([
            'pid', 'memory', 'start_at', 'running_time', 'status', 'deal_tasks','errors'
        ], 2);

        $processes = [];

        foreach ($this->getProcessesStatus() as $process) {
            $processes[] = [
                 $process['pid'],
                (int)($process['memory']/1024/1024) . 'M',
                date('Y-m-d H:i', $process['start_at']),
                gmstrftime('%H:%M:%S', $process['running_time']),
                $process['status'],
                $process['deal_tasks'],
                $process['errors'],
            ];
        }

        $table->setRows($processes, 2);

        $table->render();

        return  $info . PHP_EOL . $table->render();
    }
}