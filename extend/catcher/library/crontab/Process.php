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
use think\facade\Log;

trait Process
{
    /**
     * quit 退出
     *
     * @var boolean
     */
    protected $quit =false;

    /**
     * 设置最大内存/256M
     *
     * @var [type]
     */
    protected $maxMemory = 256 * 1024 * 1024;

    /**
     * 创建进程
     *
     * @return \Closure
     */
    protected function createProcessCallback()
    {
        return function (\Swoole\Process $process) {
            // 必须使用 pcntl signal 注册捕获
            // Swoole\Process::signal ignalfd 和 EventLoop 是异步 IO，不能用于阻塞的程序中，会导致注册的监听回调函数得不到调度
            // 同步阻塞的程序可以使用 pcntl 扩展提供的 pcntl_signal
            // 安全退出进程
            pcntl_signal(SIGTERM, function() {
                $this->quit = true;
            });

            pcntl_signal(SIGUSR1, function () use ($process){
                // todo
                $this->updateTask($process->pid);
            });

            while (true) {
                $cron = $process->pop();
                if ($cron && is_string($cron)) {
                    $cron = unserialize($cron);
                    $this->beforeTask($process->pid);
                    try {
                        $cron->run();
                    } catch (\Throwable $e) {
                        $this->addErrors($process->pid);
                        Log::error($e->getMessage() . ': at ' . $e->getFile() . ' ' . $e->getLine() . '行'.
                            PHP_EOL . $e->getTraceAsString());
                    }
                    $this->afterTask($process->pid);
                }
                pcntl_signal_dispatch();
                sleep(1);

                // 超过最大内存
                if (memory_get_usage() > $this->maxMemory) {
                    $this->quit = true;
                }

                // 如果收到安全退出的信号，需要在最后任务处理完成之后退出
                if ($this->quit) {
                    Log::info('worker quit');
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
            'memory' => memory_get_usage(),
            'start_at' => time(),
            'running_time' => 0,
            'status' => self::WAITING,
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

        // $processIds
        foreach ($this->table as $process) {
            if ($process['status'] == self::WAITING) {
                $pid = $process['pid'];
                break;
            }
        }

        // 获取相应的进程投递任务
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
        if ($process = $this->table->get($this->getColumnKey($pid))) {
            $process['status'] = self::BUSYING;
            $process['running_time'] = time() - $process['start_at'];
            $process['memory'] = memory_get_usage();
            $this->table->set($this->getColumnKey($pid), $process);
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
        if ($process = $this->table->get($this->getColumnKey($pid))) {
            $process['status'] = self::WAITING;
            $process['running_time'] = time() - $process['start_at'];
            $process['memory'] = memory_get_usage();
            $process['deal_tasks'] += 1;
            $this->table->set($this->getColumnKey($pid), $process);
        }
    }

    /**
     * 更新信息
     *
     * @time 2020年07月09日
     * @param $pid
     * @return void
     */
    protected function updateTask($pid)
    {
        if ($process = $this->table->get($this->getColumnKey($pid))) {
            $process['running_time'] = time() - $process['start_at'];
            $process['memory'] = memory_get_usage();
            $this->table->set($this->getColumnKey($pid), $process);
        }
    }

    /**
     * 增加错误
     *
     * @time 2020年07月09日
     * @param $pid
     * @return void
     */
    protected function addErrors($pid)
    {
        if ($process = $this->table->get($this->getColumnKey($pid))) {
            $process['errors'] += 1;
            $this->table->set($this->getColumnKey($pid), $process);
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
    public function renderProcessesStatusToString()
    {
        $scheduleV = self::VERSION;
        $adminV = CatchAdmin::VERSION;
        $phpV = PHP_VERSION;

        $processNumber = $this->table->count();
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

        foreach ($this->table as $process) {
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